<?php

/**
 * Permet de requeter la table Departement
 * qui contient l'ensemble des départements
 * ainsi que les régions auquels ils appartiennent
 */

namespace App\Repository;

use App\Entity\Departement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Departement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Departement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Departement[]    findAll()
 * @method Departement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method Departement[]    findRegionByDepCode(Integer $value): ?Departement
 */
class DepartementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Departement::class);
    }

    // /**
    // Permet de savoir à quelle région appartient un département par son numéro de département
    // * @return Departement
    // */
    public function findRegionByDepCode($value): ?Departement
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.dep_code = :val')
            ->setParameter('val', $value)
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleResult()
        ;
    }    
}