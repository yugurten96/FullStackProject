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

class PropertyController extends AbstractController {

    #[Route('/property/average', name: 'average')]
    public function averageYear(PropertyRepository $propertyRepository): Response {
        $tab = array();
        $tab_count = array();
        $products = $propertyRepository->findAll();

        foreach ($products as $key => $entity) {
            $title = $entity->getYear();
            if (array_key_exists($title, $tab)) {
                $pro = $tab[$title];
                $pro->setPrice($pro->getPrice() + $entity->getPrice());
                $pro->setSurface($pro->getSurface() + $entity->getSurface());
                $tab_count[$title] = $tab_count[$title] + 1;
            } else {
                $tab[$title] = $entity;
                $tab_count[$title] = 1;
            }
        }

        $this->reformat($tab, $tab_count);

        $map = array_map(function (Property $property): float {
            return round($property->getPrice() /  $property->getSurface(), 2);
        }, $tab);

        return $this->json(["data" => $this->makeArray($map)]);
    }

    #[Route('/property/count/{time}/{before}/{after}', name: 'count')]
    public function count(string $time, string $before, string $after, PropertyRepository $propertyRepository): Response {
        $tab_count = array();

        $filter = function (Property $property) use ($before, $after) {
            return $this->inDate($property, $before, $after);
        };

        $products = array_filter($propertyRepository->findAll(), $filter);

        foreach ($products as $key => $entity) {
            $title = "";

            if ($time == "month")
                $title = $entity->getMonth() . "-" . $entity->getYear();
            else if ($time == "year")
                $title = $entity->getYear();
            else
                $title = $entity->getDay() . "-" . $entity->getMonth() . "-" . $entity->getYear();

            if (array_key_exists($title, $tab_count))
                $tab_count[$title] = $tab_count[$title] + $entity->getCount();
            else
                $tab_count[$title] = $entity->getCount();
        }
        return $this->json(["data" => $this->makeArray($tab_count)]);
    }

    #[Route('/property/sell/{date}', name: 'sell')]
    public function sell(string $date, PropertyRepository $propertyRepository): Response {
        $tab_count = array();

        $filter = function (Property $property) use ($date) {
            return $property->getYear() == $date;
        };

        $products = array_filter($propertyRepository->findAll(), $filter);
        $total = 0;

        foreach ($products as $key => $entity) {
            $title = $entity->getRegion();
            $total += $entity->getCount();
            if (array_key_exists($title, $tab_count))
                $tab_count[$title] = $tab_count[$title] + $entity->getCount();
            else
                $tab_count[$title] = $entity->getCount();
        }

        $map = array_map(function (int $val) use ($total): float {
            return round($val / $total * 100, 2);
        }, $tab_count);

        return $this->json(["data" => $this->makeArray($map, "value")]);
    }

    public function reformat($tab, $tab_count) {
        foreach ($tab as $key => $pro) {
            $pro->setPrice($pro->getPrice() / $tab_count[$key]);
            $pro->setSurface($pro->getSurface() / $tab_count[$key]);
        }
    }

    public function inDate($entity, $date1, $date2) {
        return  $this->beforeDate($entity, $date2) && $this->afterDate($entity, $date1);
    }

    public function beforeDate(Property $entity, $date) {
        $date_info = explode('-', $date);

        if (count($date_info) != 3)
            return false;

        if ($entity->getYear() == $date_info[2]) {
            if ($entity->getMonth() == $date_info[1])
                return intval($date_info[0]) >= intval($entity->getDay());
            else
                return intval($date_info[1]) >= intval($entity->getMonth());
        } else
            return intval($date_info[2]) >= intval($entity->getYear());
    }

    public  function afterDate(Property $entity, $date) {
        $date_info = explode('-', $date);

        if (count($date_info) != 3)
            return false;

        if ($entity->getYear() == $date_info[2]) {
            if ($entity->getMonth() == $date_info[1])
                return intval($date_info[0]) <= intval($entity->getDay());
            else
                return intval($date_info[1]) <= intval($entity->getMonth());
        } else
            return intval($date_info[2]) <= intval($entity->getYear());
    }

    public function sortDate($date1, $date2) {
        $date_info1 = explode('-', $date1); 
        $date_info2 = explode('-', $date2);

        if (count($date_info1) != count($date_info2))
            return -1;

        for ($i = count($date_info1) - 1; $i > -1; $i--) {
            if ($date_info1[$i] != $date_info2[$i])
                return intval($date_info1[$i]) < intval($date_info2[$i]) ? -1 : 1;
        }
        return 0;
    }

    public function makeEntity(string $date, float $value) {
        $entity = array();
        $entity["key"] = $date;
        $entity["value"] = $value;
        return $entity;
    }

    public function makeArray($array, string $sort = "key") {
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
