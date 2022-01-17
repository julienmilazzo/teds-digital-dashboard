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
}
