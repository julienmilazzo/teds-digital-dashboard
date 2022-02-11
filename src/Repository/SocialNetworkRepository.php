<?php

namespace App\Repository;

use App\Entity\SocialNetwork;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SocialNetwork|null find($id, $lockMode = null, $lockVersion = null)
 * @method SocialNetwork|null findOneBy(array $criteria, array $orderBy = null)
 * @method SocialNetwork[]    findAll()
 * @method SocialNetwork[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SocialNetworkRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SocialNetwork::class);
    }

    /**
     * @return SocialNetwork[]
     */
    public function findAllOrderByRenewalDate(): array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT sn
            FROM App\Entity\SocialNetwork sn
            ORDER BY sn.renewalDate ASC'
        );

        // returns an array of Product objects
        return $query->getResult();
    }
}
