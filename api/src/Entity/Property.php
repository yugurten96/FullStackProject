<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\PropertyController;
use App\Repository\PropertyRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PropertyRepository::class)]
#[ApiResource(itemOperations: [
    'get',
    'average' => [
        'method' => 'GET',
        'path' => '/property/average'
    ],
    'count' => [
        'method' => 'GET',
        'path' => '/property/count/{time}/{before}/{after}'
    ],
    'sell' => [
        'method' => 'GET',
        'path' => '/property/sell/{date}'
    ]
])]
class Property {
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $region;

    #[ORM\Column(type: 'integer')]
    private $surface;

    #[ORM\Column(type: 'float')]
    private $price;

    #[ORM\Column(type: 'string', length: 255)]
    private $day;

    #[ORM\Column(type: 'string', length: 255)]
    private $month;

    #[ORM\Column(type: 'string', length: 255)]
    private $year;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $count;

    public function getId(): ?int {
        return $this->id;
    }

    public function getDate(): ?string {
        return $this->day . "/" . $this->month . "/" . $this->year;
    }

    public function getRegion(): ?string {
        return $this->region;
    }

    public function setRegion(string $region): self {
        $this->region = $region;
        return $this;
    }

    public function getSurface(): ?int {
        return $this->surface;
    }

    public function setSurface(int $surface): self {
        $this->surface = $surface;
        return $this;
    }

    public function getPrice(): ?float {
        return $this->price;
    }

    public function setPrice(float $price): self {
        $this->price = $price;
        return $this;
    }

    public function getDay(): ?string {
        return $this->day;
    }

    public function setDay(string $day): self {
        $this->day = $day;
        return $this;
    }

    public function getMonth(): ?string {
        return $this->month;
    }

    public function setMonth(string $month): self {
        $this->month = $month;
        return $this;
    }

    public function getYear(): ?string {
        return $this->year;
    }

    public function setYear(string $year): self {
        $this->year = $year;
        return $this;
    }

    public function getCount(): ?int {
        return $this->count;
    }

    public function setCount(?int $count): self {
        $this->count = $count;
        return $this;
    }
}
