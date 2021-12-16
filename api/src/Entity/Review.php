<?php

namespace App\Entity;
 
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
 
/**
 * A review of a book.
 *
 * @ORM\Entity
 */
 #[ApiResource]
 class Review
 {
   /**
    * The id of this review.
    *
    * @ORM\Id
    * @ORM\GeneratedValue
    * @ORM\Column(type="integer")
    */
   private ?int $id = null;
 
   /**
    * The rating of this review (between 0 and 5).
    *
    * @ORM\Column(type="smallint")
    */
   #[Assert\Range(min: 0, max: 5)]
   public int $rating = 0;
 
   /**
    * The body of the review.
    *
    * @ORM\Column(type="text")
    */
   #[Assert\NotBlank]
   public string $body = '';
 
   /**
    * The author of the review.
    *
    * @ORM\Column
    */
   #[Assert\NotBlank]
   public string $author = '';
 
   /**
    * The date of publication of this review.
    *
    * @ORM\Column(type="datetime_immutable")
    */
   #[Assert\NotNull]
   public ?\DateTimeInterface $publicationDate = null;
 
   /**
    * The book this review is about.
    *
    * @ORM\ManyToOne(targetEntity="Book", inversedBy="reviews")
    */
   #[Assert\NotNull]
   public ?Book $book = null;
 
   public function getId(): ?int
   {
      return $this->id;
   }
}