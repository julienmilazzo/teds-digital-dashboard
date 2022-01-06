<?php

namespace App\Repository;

use App\Entity\ClickAndCollect;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ClickAndCollect|null find($id, $lockMode = null, $lockVersion = null)
 * @method ClickAndCollect|null findOneBy(array $criteria, array $orderBy = null)
 * @method ClickAndCollect[]    findAll()
 * @method ClickAndCollect[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClickAndCollectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ClickAndCollect::class);
    }

    // /**
    //  * @return ClickAndCollect[] Returns an array of ClickAndCollect objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ClickAndCollect
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
