<?php

/**
 * Script that load data from the file departement in db and then load each file.txt (valeur-financiere) inside DB
 * Doesn't work with Docker because Docker have a ram memory problem. In fact 1 file. txt = 2,1 GB of RAM
 * the 2 file.txt = 4.2 GB and so on. at the end computer crash because RAM is exceded. 
 * I write this script for Docker but it's doesn't work we need to do it manually and then execute datafixture --append
 */

namespace App\DataFixtures;


use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\Persistence\ObjectManager;
use PhpCsFixer\Console\Output\NullOutput;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\HttpKernel\KernelInterface;

class ScriptTest extends Command
{

    protected static $defaultName = 'app:load-files';

    public function __construct(MutationFixtures $mutationFixtures, DepartementFixtures $departementFixtures,
     string $projectDir,KernelInterface $kernel)
    {
        $this->mutationFixtures = $mutationFixtures;
        $this->departementFixtures = $departementFixtures;
        $this->projectDir = $projectDir;
        $this->kernel=$kernel;
      
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $kernel = $this->kernel;
        $application = new Application($kernel);
        $application->setAutoExit(false);

        $input_dep = new ArrayInput(array("doctrine:fixtures:load", "--group" => ["group1"]));

        $application->run($input_dep,null);
        $finder = new Finder();
        $fileSystem = new Filesystem();       

        // find all files in the current directory 
        $project_dir = $this->projectDir; //. "/Donnees_En_Attente";
        dump($project_dir);

        //ensure to have only .txt file => ignore csv
        $filter_txt = function (\SplFileInfo $file) {
            if ($file->getExtension() == "csv") {
                return false;
            }
        };

        // get only .txt file
        $finder->files()->in($project_dir."/Donnees_En_Attente")->filter($filter_txt);

        $size = iterator_count($finder);
       
        // check if there are any search results
        if ($finder->hasResults()) {

            //create Folder "Donnees_A_Traiter
            try{
                $fileSystem->mkdir($project_dir."/Donnees_A_Traiter");
            }catch (IOExceptionInterface $exception) {
                $output->writeln("Impossible de créer le fichier : ".$exception->getPath());
            }
            //on va déplacer les fichier un par un
           

            $input_Mutation = new ArrayInput(array("doctrine:fixtures:load", "--group" => ["group2"], "--append" => true));

            $currentNbFile = 1;
            foreach ($finder as $file) {
                $output->writeln("$currentNbFile/$size  fichiers");
                $fileSystem->copy($file,$project_dir."/Donnees_A_Traiter/".$file);
                $application->run($input_Mutation,null);
                $fileSystem->remove($project_dir."/Donnees_A_Traiter/".$file);
                //move file
                $currentNbFile++;
            }
            $fileSystem->remove($project_dir."/Donnees_A_Traiter");
        }
        return Command::SUCCESS;
    }
}
