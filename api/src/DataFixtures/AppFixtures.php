<?php

namespace App\DataFixtures;

use App\Entity\Property;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\ConsoleOutput;

class AppFixtures extends Fixture {
    private $region;
    private $memory;
    private $memory_count;

    public function load(ObjectManager $manager): void {
       ini_set('memory_limit', '-1');
       gc_enable();
       /*
           $this->loadYear($manager, 2017);
           $this->loadYear($manager, 2018);
           $this->loadYear($manager, 2019);
           $this->loadYear($manager, 2020);
       */
       $this->loadYear($manager, 2021);
    }

    public function loadYear(ObjectManager $manager, int $year): void {
        $filename = 'data/valeursfoncieres-' . strval($year) . '.txt';
        $handle = fopen($filename, "r");
        if ($handle) {
            $output = new ConsoleOutput();
            $output->writeln('<info>' . $year .'</info>');
            $size = self::getLines($filename);
            $progressBar = new ProgressBar($output, $size);
            $progressBar->setBarCharacter('<fg=green>⚬</>');
            $progressBar->setEmptyBarCharacter("<fg=red>⚬</>");
            $progressBar->setProgressCharacter("<fg=blue>➤</>");
            $progressBar->setBarWidth(50);
            $progressBar->start();

            while ($line = stream_get_line($handle, 1024 * 1024, "\n")) {
                $row_data = explode('|', $line);
                if ($row_data[9] == "Vente" && ($row_data[35] == '1' || $row_data[35] == '2')) {
                    $property = new Property();
                    $date_info = explode('/', $row_data[8]);
                    $property->setSellDay($date_info[0]);
                    $property->setSellMonth($date_info[1]);
                    $property->setSellYear($date_info[2]);
                    $property->setPrice(floatval($row_data[10]));
                    $property->setRegion($this->getRegion($row_data[18]));
                    $property->setSurface(intval($row_data[38]));
                    $property->setCount(0);
                    $this->add($property);
                    gc_collect_cycles();
                }
                $progressBar->advance();
            }
            $progressBar->finish();
            echo "\n";
            fclose($handle);
        }

        $this->reformat();
        gc_collect_cycles();
        $this->insert($manager, $this->memory);
        gc_collect_cycles();
        $this->reset();
        gc_collect_cycles();
    }

    public function getLines($filename) {
        $handle = fopen($filename, "r");
        $lines = 0;

        while(!feof($handle)){
            $line = fgets($handle);
            $lines++;
        }

        fclose($handle);
        return $lines;
    }

    public function reset() {
        $this->memory_count = array();
        $this->memory = array();
    }

    public function add(Property $property) {
        if ($this->memory == null) {
            $this->memory = array();
            $this->memory_count = array();
        }

        $title = $property->getSellDate() . "-" . $property->getRegion();
        if (array_key_exists($title, $this->memory)) {
            $pro = $this->memory[$title];
            $pro->setPrice($pro->getPrice() + $property->getPrice());
            $pro->setSurface($pro->getSurface() + $property->getSurface());
            $pro->setCount($pro->getCount() + 1);
            $this->memory_count[$title] = $this->memory_count[$title] + 1;
        } else {
            $this->memory[$title] = $property;
            $this->memory_count[$title] = 1;
        }
    }

    public function insert(ObjectManager $manager, $array) {
        $i = 0;
        $output = new ConsoleOutput();
        foreach ($array as $key => $pro) {
            $manager->persist($pro);
            if(++$i % 500 == 0) {
                $manager->flush();
                gc_collect_cycles();
            }
        }
        $output->writeln('<info>Insertion successful</info>');
        $manager->flush();
    }

    public function reformat() {
        foreach ($this->memory as $key => $pro) {
            $pro->setPrice($pro->getPrice() / $this->memory_count[$key]);
            $pro->setSurface($pro->getSurface() / $this->memory_count[$key]);
        }
    }

    public function init() {
        if($this->region == null) {
            $this->region = array();
            $this->region['Auvergne-Rhône-Alpes'] = '01,03,07,15,26,38,42,43,63,69,73,74';
            $this->region['Bourgogne-Franche-Comté'] = '21,25,39,58,70,71,89,90';
            $this->region['Bretagne'] = '22,29,35,56';
            $this->region['Centre-Val de Loire'] = '18,28,36,37,41,45';
            $this->region['Corse'] = '2A,2B';
            $this->region['Grand Est'] = '08,10,51,52,54,55,57,67,68,88';
            $this->region['Hauts-de-France'] = '02,59,60,62,80';
            $this->region['Île-de-France'] = '75,77,78,91,92,93,94,95';
            $this->region['Normandie'] = '14,27,50,61,76';
            $this->region['Nouvelle-Aquitaine'] = '16,17,19,23,24,33,40,47,64,79,86,87';
            $this->region['Occitanie'] = '09,11,12,30,31,32,34,46,48,65,66,81,82';
            $this->region['Pays de la Loire'] = '44,49,53,72,85';
            $this->region['Provence-Alpes-Côte d\'Azur'] = '04,05,06,13,83,84';
            $this->region['DOM'] = '971,972,973,974,976';
        }
    }

    public function getRegion($dep) {
        $this->init();
        foreach ($this->region as $key => $value) {
            foreach (explode(',', $value) as $d) {
                if ($dep == $d)
                    return $key;
            }
        }
    }
}
