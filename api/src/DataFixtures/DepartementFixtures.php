<?php

/**
 * Charge les données du fichier departements-france.csv 
 * (numéro de dep, nom du dép, numéro de region, nom région)
 * dans la table departement
 */

namespace App\DataFixtures;

use App\Entity\Departement;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use League\Csv\Reader;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\ConsoleOutput;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

class DepartementFixtures extends Fixture implements FixtureGroupInterface
{

    public static function getGroups(): array
    {
        return ['group1'];
    }

    public function load(ObjectManager $manager)
    {
        $output = new ConsoleOutput();

        // Read a CSV file
        $reader = Reader::createFromPath('%kernel.root_dir%/../Donnees_En_Attente/departements-france.csv');

        // Optionally, you can keep the number of the line where
        // the loop its currently iterating over
        $lineNumber = 1;

        //get the all row from file ($reader) 
        $results = $reader->getRecords();

        $size = iterator_count($results);
        $progressBar = new ProgressBar($output, $size);

        //split the output into 3 lines: status, progress bar and information like ETA and memory usage
        $progressBar->setFormat(
            "<fg=white;bg=cyan> %status:-45s%</>\n%current%/%max% [%bar%] %percent:3s%%\n 🏁 %estimated:-20s%  %memory:60s%\n"
        );
            $progressBar->setMessage("Ficher en cours de traitement : departements-france.csv", 'status');

   
        $progressBar->start();


        // Iterate over every line of the file
        foreach ($results as $row) {

            // skip the column name
            if ($lineNumber > 1) {

                $departement = new Departement();
                $departement->setDepCode(intval($row[0]));
                $departement->setDepName($row[1]);
                $departement->setRegionCode(intval($row[2]));
                $departement->setRegionName($row[3]);

                $manager->persist($departement);
               
            }
            // Increase the current line
            $lineNumber++;
            $progressBar->advance(1);
        }
        //save into DB
        $manager->flush();
        $progressBar->finish();
    }
}
