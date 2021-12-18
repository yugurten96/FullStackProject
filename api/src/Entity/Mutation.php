<?php

namespace App\Entity;

use App\Repository\MutationRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Serializer\Filter\PropertyFilter;

/**
 * @ORM\Entity(repositoryClass=MutationRepository::class)
 * @ApiResource(
 * itemOperations={
 *         },   
 *  collectionOperations={
 *  "avg_m2"={"route_name"="mutation_avg_m2",  "methode"="get","openapi_context" = {
 *                  "parameters" = {
 *                      {
 *                          "name" = "local_type_code",
 *                          "in" = "query",
 *                          "description" = "define the type of good (House,Appartment...)",
 *                          "required" = true,
 *                          "type" : "integer"
 *                      }
 *                  }
 *              }
 * },
 * 
 *  "number_mutation"={"route_name"="mutation_number_btw_by_period", "methode"="get","openapi_context" = {
 *                  "parameters" = {
 *                      {
 *                          "name" = "startDate",
 *                          "in" = "query",
 *                          "description" = "begin of the period",
 *                          "required" = true,
 *                          "type": "string",
 *                           "format": "date-time"
 *                      },
 *                      {
 *                          "name" = "endDate",
 *                          "in" = "query",
 *                          "description" = "end of the period",
 *                          "required" = true,
 *                          "type": "string",
 *                           "format": "date-time"
 *                      },
 *                      {
 *                          "name" = "period",
 *                          "in" = "query",
 *                          "description" = "Time period",
 *                          "required" = true,
 *                          "type" : "integer"
 *                      }
 *                  }
 *              }
 * },
 * 
 *  "number_mutation_by_region"={"route_name"="mutation_number_by_region", "methode"="get","openapi_context" = {
 *                  "parameters" = {
 *                      {
 *                          "name" = "year",
 *                          "in" = "query",
 *                          "description" = "define year",
 *                          "required" = true,
 *                          "type" : "integer"
 *                      }
 *                  }
 *              }
 * },
 * 
 * 
 *  }
 * )
 * 
 * 
 *
 * 
 */
class Mutation
{
    //avoid to put string in database
    const NATURE = [
        0 => "Vente"
    ];

    //avoid to put string in database
    const LOCAL_TYPE_CODE = [
        0 => "NC",
        1 => "Maison",
        2 => "Appartement",
        3 => "Dependance (isolée)",
        4 => "Local industriel et commercial ou assimilés"
    ];

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="smallint")
     */
    private $nature;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $price;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $dep_code;

    /**
     * @ORM\Column(type="smallint")
     */
    private $local_type_code;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $surface;

    /**
     * @ORM\ManyToOne(targetEntity=Departement::class, inversedBy="mutations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $region;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getNature(): ?int
    {
        return $this->nature;
    }

    public function setNature(int $nature): self
    {
        $this->nature = $nature;
        return $this;
    }

    public function getNatureType(): string
    {
        return self::NATURE[$this->nature];
    }

    public function setNatureType(string $nature): self
    {
        $this->nature = array_search($nature, self::NATURE);
        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getDepCode(): ?int
    {
        return $this->dep_code;
    }

    public function setDepCode(?int $dep_code): self
    {
        $this->dep_code = $dep_code;

        return $this;
    }

    public function getLocalTypeCode(): ?int
    {
        return $this->local_type_code;
    }

    public function setLocalTypeCode(int $local_type_code): self
    {
        $this->local_type_code = $local_type_code;
        return $this;
    }

    public function getLocalTypeCodeType(): string
    {
        return self::LOCAL_TYPE_CODE[$this->local_type_code];
    }

    public function getSurface(): ?int
    {
        return $this->surface;
    }

    public function setSurface(?int $surface): self
    {
        $this->surface = $surface;

        return $this;
    }

    public function getRegion(): ?Departement
    {
        return $this->region;
    }

    public function setRegion(?Departement $region): self
    {
        $this->region = $region;

        return $this;
    }

}