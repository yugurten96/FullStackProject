<?php

/**
 * Charge les données des fichier valeursfoncieres-{annee}.txt 
 * (date, nature (vente...), valeur (prix), code du departement (1..94 + DOMTOM), 
 * localtypecode (0:Maison, 1 :Appartement...), surface habitable dans la table mutation (vente)
 * A noter une relation ManyToOne avec la table departement pour savoir 
 * dans quelle région se trouve le département
 */

namespace App\DataFixtures;

use App\Entity\Mutation;
use App\Repository\DepartementRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Exception;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\ConsoleOutput;

// Pour éviter les problèmes de mémoires
ini_set('memory_limit', '3G');

class MutationFixtures extends Fixture implements FixtureGroupInterface
{

    public static function getGroups(): array
    {
        return ['group2'];
    }
    
    // initialize value
    public function __construct(DepartementRepository $departementRepository, string $projectDir)
    {
        $this->departementRepository = $departementRepository;
        $this->projectDir = $projectDir;
    }

    //Method call when : 
    public function load(ObjectManager $manager)
    {
        $finder = new Finder();
         //Disable SQL Logging => to avoid huge memory loss
        $manager->getConnection()->getConfiguration()->setSQLLogger(null);

        // find all files in the current directory 
        $project_dir = $this->projectDir . "/Donnees_A_Traiter";

        //ensure to have only .txt file => ignore csv
        $filter_txt = function (\SplFileInfo $file) {
            if ($file->getExtension() == "csv") {
                return false;
            }
        };

        // get only .txt file
        $finder->files()->in($project_dir)->filter($filter_txt);

        $size = iterator_count($finder);
       
        // check if there are any search results
        if ($finder->hasResults()) {

            $currentNbFile = 1;
            foreach ($finder as $file) {

                $this->importData($file->getRealPath(), $manager, $size, $currentNbFile);
                $currentNbFile++;

                $file = null;
                unset($raw_string);
            }
        }
    }


    // Handle to retrive data from .txtFile and persist them in DB
    private function importData(string $filepath, ObjectManager $manager, int $nbFile, int $currentNbFile)
    {

        $output = new ConsoleOutput();

        $size = self::getLines($filepath);

        // Read a CSV file
        $handle = fopen($filepath, "r");

        $batchSize = 25;

        // Optionally, you can keep the number of the line where
        // the loop its currently iterating over
        $lineNumber = 1;

        // Starting progress
        $progressBar = new ProgressBar($output, $size);

        //split the output into 3 lines: status, progress bar and information like ETA and memory usage
        $progressBar->setFormat(
            "<fg=white;bg=cyan> %status:-45s%</>\n%current%/%max% [%bar%] %percent:3s%%\n 🏁 %estimated:-20s%  %memory:20s%\n"
        );

        $progressBar->setMessage("($currentNbFile"."/".$nbFile.") Ficher en cours de traitement ".basename($filepath), 'status');

        $progressBar->start();

        $raw_string = null;
        unset($raw_string);

        $row = null;
        unset($row);        

        // Iterate over every line of the file
        while (($raw_string = fgets($handle)) !== false) {
            // Parse the raw txt string: "1| a| b| c"

            $row = str_getcsv($raw_string, "̣|");

            // into an array: ['1', 'a', 'b', 'c']
            // And do what you need to do with every line
            if ($lineNumber > 1) {

                $result = explode('|', $row[0]); // split string

                //new entity => new dbline
                $mutation = new Mutation();

                $dep = intval($result[18]);

                try {

                    // replace 07/12/2015 in 07-12-2015 otherweise whe have problem when we create the date from string
                    $date = str_replace("/","-",$result[8]);
                    $date =  new \DateTime($date);
                    $mutation->setDate($date);
                } catch (Exception $e) {
                }


                // Nature => col 9/43
                $mutation->setNatureType($result[9]);
                // Price => col 10/43
                $mutation->setPrice(floatval($result[10]));
                // Departement Code => col 18/43
                $mutation->setDepCode($dep);
                // localTypeCode => col 35/43
                $mutation->setLocalTypeCode(intval($result[35]));
                // Surface => col 38/43
                $mutation->setSurface(intval($result[38]));

                //get the region which in the department
                $region = $this->departementRepository->findRegionByDepCode($dep);

                //Link department to a region
                $mutation->setRegion($region);
                $manager->persist($mutation);

                //Flush and clear frequently instead of only once at the end.
                if ($lineNumber % $batchSize == 0) {
                    $manager->flush();
                    $manager->clear();



                    $progressBar->advance($batchSize);
                }
            }

            // Increase the current line
            $lineNumber++;
        }

        fclose($handle);
        // Ending the progress bar process
        $progressBar->finish();

        $progressBar = null;
        unset($progressBar);

        $output = null;
        unset($output);

    }

    //return number lines of file => not so good bc we open twice the file (count line and treatment)
   public function getLines($file)
    {
        $f = fopen($file, 'rb');
        $lines = 0;

        while (!feof($f)) {
            $lines += substr_count(fread($f, 8192), "\n");
        }

        fclose($f);

        return $lines;
    }

}
