<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DepartementRepository::class)
 */
class Departement
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="smallint")
     */
    private $dep_code;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $dep_name;

    /**
     * @ORM\Column(type="smallint")
     */
    private $region_code;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $region_name;

    /**
     * @ORM\OneToMany(targetEntity=Mutation::class, mappedBy="region")
     */
    private $mutations;

    public function __construct()
    {
        $this->mutations = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDepCode(): ?int
    {
        return $this->dep_code;
    }

    public function setDepCode(int $dep_code): self
    {
        $this->dep_code = $dep_code;

        return $this;
    }

    public function getDepName(): ?string
    {
        return $this->dep_name;
    }

    public function setDepName(string $dep_name): self
    {
        $this->dep_name = $dep_name;

        return $this;
    }

    public function getRegionCode(): ?int
    {
        return $this->region_code;
    }

    public function setRegionCode(int $region_code): self
    {
        $this->region_code = $region_code;

        return $this;
    }

    public function getRegionName(): ?string
    {
        return $this->region_name;
    }

    public function setRegionName(string $region_name): self
    {
        $this->region_name = $region_name;

        return $this;
    }

    /**
     * @return Collection|Mutation[]
     */
    public function getMutations(): Collection
    {
        return $this->mutations;
    }


}