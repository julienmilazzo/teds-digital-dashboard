<?php

namespace App\Repository;

use App\Entity\SiteClientToServicesBinder;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SiteClientToServicesBinder|null find($id, $lockMode = null, $lockVersion = null)
 * @method SiteClientToServicesBinder|null findOneBy(array $criteria, array $orderBy = null)
 * @method SiteClientToServicesBinder[]    findAll()
 * @method SiteClientToServicesBinder[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SiteClientToServicesBinderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SiteClientToServicesBinder::class);
    }
}
