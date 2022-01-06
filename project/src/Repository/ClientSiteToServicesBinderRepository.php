<?php

namespace App\Repository;

use App\Entity\ClientSiteToServicesBinder;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ClientSiteToServicesBinder|null find($id, $lockMode = null, $lockVersion = null)
 * @method ClientSiteToServicesBinder|null findOneBy(array $criteria, array $orderBy = null)
 * @method ClientSiteToServicesBinder[]    findAll()
 * @method ClientSiteToServicesBinder[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClientSiteToServicesBinderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ClientSiteToServicesBinder::class);
    }

    // /**
    //  * @return ClientSiteToServicesBinder[] Returns an array of ClientSiteToServicesBinder objects
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
    public function findOneBySomeField($value): ?ClientSiteToServicesBinder
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
