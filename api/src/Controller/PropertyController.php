<?php

namespace App\Controller;

use App\Repository\PropertyRepository;
use App\Entity\Property;
use Faker\Core\Number;
use PhpParser\Builder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use tidy;

class PropertyController extends AbstractController
{

    #[Route('/property/average', name: 'average')]
    public function averageYear(PropertyRepository $propertyRepository): Response
    {

        $memory = array();
        $memory_count = array();
        $products = $propertyRepository
            ->findAll();

        foreach ($products as $key => $entity) {
            $title = $entity->getSellMonth() . "-" . $entity->getSellYear();
            if (array_key_exists($title, $memory)) {
                $pro = $memory[$title];
                $pro->setPrice($pro->getPrice() + $entity->getPrice());
                $pro->setSurface($pro->getSurface() + $entity->getSurface());
                $memory_count[$title] = $memory_count[$title] + 1;
            } else {
                $memory[$title] = $entity;
                $memory_count[$title] = 1;
            }
        }

        $this->reformat($memory, $memory_count);

        $map = array_map(function (Property $property): float {
            return round($property->getPrice() /  $property->getSurface(), 2);
        }, $memory);

        return $this->json(["data" => $this->makeArray($map)]);
    }

    #[Route('/property/count/{time}/{before}/{after}', name: 'count')]
    public function count(string $time, string $before, string $after, PropertyRepository $propertyRepository): Response
    {
        $memory_count = array();

        $filter = function (Property $property) use ($before, $after) {
            return $this->inDate($property, $before, $after);
        };

        $products = array_filter($propertyRepository->findAll(), $filter);



        foreach ($products as $key => $entity) {

            $title = "";

            if ($time == "month") {
                $title = $entity->getSellMonth() . "-" . $entity->getSellYear();
            } else if ($time == "year") {
                $title = $entity->getSellYear();
            } else {
                $title = $entity->getSellDay() . "-" . $entity->getSellMonth() . "-" . $entity->getSellYear();
            }

            if (array_key_exists($title, $memory_count)) {
                $memory_count[$title] = $memory_count[$title] + $entity->getCount();
            } else {
                $memory_count[$title] = $entity->getCount();
            }
        }

        return $this->json(["data" => $this->makeArray($memory_count)]);
    }


    #[Route('/property/sell/{date}', name: 'sell')]
    public function sell(string $date, PropertyRepository $propertyRepository): Response
    {
        $memory_count = array();

        $filter = function (Property $property) use ($date) {
            return $property->getSellYear() == $date;
        };

        $products = array_filter($propertyRepository->findAll(), $filter);

        $total = 0;

        foreach ($products as $key => $entity) {
            $title = $entity->getRegion();
            $total += $entity->getCount();
            if (array_key_exists($title, $memory_count)) {
                $memory_count[$title] = $memory_count[$title] + $entity->getCount();
            } else {
                $memory_count[$title] = $entity->getCount();
            }
        }

        $map = array_map(function (int $val) use ($total): float {
            return round($val / $total * 100, 2);
        }, $memory_count);

        return $this->json(["data" => $this->makeArray($map, "value")]);
    }

    public function reformat($memory, $memory_count)
    {
        foreach ($memory as $key => $pro) {
            $pro->setPrice($pro->getPrice() / $memory_count[$key]);
            $pro->setSurface($pro->getSurface() / $memory_count[$key]);
        }
    }

    public function inDate($entity, $date1, $date2)
    {
        return  $this->beforeDate($entity, $date2) && $this->afterDate($entity, $date1);
    }

    public function beforeDate(Property $entity, $date)
    {
        $date_info = explode('-', $date); // dd/mm/YY
        if (count($date_info) != 3) return false;
        if ($entity->getSellYear() == $date_info[2]) {
            if ($entity->getSellMonth() == $date_info[1]) {
                return intval($date_info[0]) >= intval($entity->getSellDay());
            } else {
                return intval($date_info[1]) >= intval($entity->getSellMonth());
            }
        } else {
            return intval($date_info[2]) >= intval($entity->getSellYear());
        }
    }

    public  function afterDate(Property $entity, $date)
    {
        $date_info = explode('-', $date); // dd/mm/YY
        if (count($date_info) != 3) return false;
        if ($entity->getSellYear() == $date_info[2]) {
            if ($entity->getSellMonth() == $date_info[1]) {
                return intval($date_info[0]) <= intval($entity->getSellDay());
            } else {
                return intval($date_info[1]) <= intval($entity->getSellMonth());
            }
        } else {
            return intval($date_info[2]) <= intval($entity->getSellYear());
        }
    }

    public function sortDate($date1, $date2)
    {
        $date_info1 = explode('-', $date1); // dd/mm/YY
        $date_info2 = explode('-', $date2); // dd/mm/YY
        if (count($date_info1) != count($date_info2)) return -1;
        for ($i = count($date_info1) - 1; $i > -1; $i--) {
            if ($date_info1[$i] != $date_info2[$i]) {
                return intval($date_info1[$i]) < intval($date_info2[$i]) ? -1 : 1;
            }
        }
        return 0;
    }

    public function makeEntity(string $date, float $value)
    {
        $entity = array();
        $entity["key"] = $date;
        $entity["value"] = $value;
        return $entity;
    }

    public function makeArray($array, string $sort = "key")
    {
        $result = array();
        foreach ($array as $key => $value) {
            array_push($result, $this->makeEntity($key, $value));
        }

        if ($sort == "key") {
            usort($result, function ($entity1, $entity2) {
                return $this->sortDate($entity1["key"], $entity2["key"]);
            });
        } else if($sort == "value") {
            usort($result, function ($entity1, $entity2) {
                return strnatcmp($entity1["value"], $entity2["value"]);
            });
            $result = array_reverse($result);
        }

        return $result;
    }
}
