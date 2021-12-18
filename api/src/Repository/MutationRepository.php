<?php

/**
 * Permet de requeter la table Mutation (ensemble des ventes) 
 * qui contient l'ensemble des données
 * relative aux ventes immobiliere en France
 */

namespace App\Repository;

use ApiPlatform\Core\Bridge\Doctrine\MongoDbOdm\Paginator;
use App\Entity\Mutation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Select;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Mutation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Mutation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Mutation[]    findAll()
 * @method Mutation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method Mutation[]    AverageMeterSquarePriceByLocalCodeType(integer $localCodeType)
 * @method Mutation[]    MutationNumberBetweenTwoDatesByDayOrMonthOrYear(DateTime $startDate, DateTime $endDate,
 * Integeger $period)
 * @method Mutation[]    MutationByRegionByYear(Integer $year)
 */
class MutationRepository extends ServiceEntityRepository
{

    const ITEMS_PER_PAGE = 20;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Mutation::class);
    }

    /**
     * return the average price by m2 groupby year and month by localTypeCode
     * Paramter :
     *  -localTypeCode : the type of mutation (maison,appartement...) => see Mutation entity
     * A voir si on peut pas passer le select directement dans le query builder => non
     */
    public function AverageMeterSquarePriceByLocalCodeType($localCodeType)
    {
        return $this->createQueryBuilder('m')
            ->select("m.date, avg(m.price/ NULLIF(m.surface,0)) as priceParM2") // nullif return NULL if two parameter are equal (avoid zero divion)
            ->andWhere('m.local_type_code = :localCodeType')
            ->setParameter('localCodeType', $localCodeType)
            ->addGroupBy('m.date')
            ->orderBy('m.date', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * return the number of mutations between two dates by period
     * Parameter :
     *  -startDate (DateTime) : begin of the period 
     *  -endDate (DateTime) : end of the period 
     *  -period (integer) : size of the period (day, week, month, year)
     * A voir si on peut pas passer le select directement dans le query builder
     */
    public function MutationNumberBetweenTwoDatesByDayOrMonthOrYear($startDate, $endDate, $period)
    {
        switch ($period){
            case 0 : //day
                return $this->createQueryBuilder('m')
            ->select("m.date AS date, count(m.id) AS number_row")
            ->andWhere('m.date BETWEEN :from AND :to')
            ->setParameter('from', $startDate )
            ->setParameter('to', $endDate)
            ->groupBy("m.date")
            ->orderBy('m.date')
            ->getQuery()
            ->getResult()
        ;
            break;
            case 1 : //week
                return $this->createQueryBuilder('m')
            ->select("(DATE_PART('week', m.date)) AS week, (DATE_PART('year', m.date)) AS year, count(m.id) AS number_row")
            ->andWhere('m.date BETWEEN :from AND :to')
            ->setParameter('from', $startDate )
            ->setParameter('to', $endDate)
            ->groupBy("year")
            ->addgroupBy("week")
            ->orderBy('year')
            ->addorderBy('week')
            ->getQuery()
            ->getResult()
        ;
            break;
            case 2 : // month
                return $this->createQueryBuilder('m')
            ->select("(DATE_PART('month', m.date)) AS month, (DATE_PART('year', m.date)) AS year, count(m.id) AS number_row")
            ->andWhere('m.date BETWEEN :from AND :to')
            ->setParameter('from', $startDate )
            ->setParameter('to', $endDate)
            ->groupBy("year")
            ->addgroupBy("month")
            ->orderBy('year')
            ->addorderBy('month')
            ->getQuery()
            ->getResult()
        ;
            break;
            default: //year
                return $this->createQueryBuilder('m')
            ->select("(DATE_PART('year', m.date)) AS year, count(m.id) AS number_row")
            ->andWhere('m.date BETWEEN :from AND :to')
            ->setParameter('from', $startDate )
            ->setParameter('to', $endDate)
            ->groupBy("year")
            ->orderBy('year')
            ->getQuery()
            ->getResult()
        ;
        }
        
    }   
    
     /**
     * return the number of mutation in each region
     * Parameter :
     *  -Year : get all mutation in each region did this year (2015-2020) 
     * A voir si on peut pas passer le select directement dans le query builder => non pas possible
     */
    public function MutationByRegionByYear($year)
    {
        return $this->createQueryBuilder('d')
            ->select("m.region_name, count(d) as number_mut")
            ->andWhere("DATE_PART('year', d.date) = :year")
            ->setParameter('year', $year)
            ->groupBy("m.region_name")
            ->leftJoin("d.region", "m")
            ->andWhere("m.id = d.region")
            ->getQuery()
            ->getResult()
        ;
    } 
}