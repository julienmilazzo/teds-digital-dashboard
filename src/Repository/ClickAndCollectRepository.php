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

    /**
     * @return ClickAndCollect[]
     */
    public function findAllOrderByRenewalDate(): array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT cc
            FROM App\Entity\ClickAndCollect cc
            ORDER BY cc.renewalDate ASC'
        );

        // returns an array of Product objects
        return $query->getResult();
    }
}
