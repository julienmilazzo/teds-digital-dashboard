<?php

namespace App\Repository;

use App\Entity\FrenchEchoppe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FrenchEchoppe|null find($id, $lockMode = null, $lockVersion = null)
 * @method FrenchEchoppe|null findOneBy(array $criteria, array $orderBy = null)
 * @method FrenchEchoppe[]    findAll()
 * @method FrenchEchoppe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FrenchEchoppeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FrenchEchoppe::class);
    }

    // /**
    //  * @return FrenchEchoppe[] Returns an array of FrenchEchoppe objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?FrenchEchoppe
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
