<?php

namespace App\Repository;

use App\Entity\Server;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Server|null find($id, $lockMode = null, $lockVersion = null)
 * @method Server|null findOneBy(array $criteria, array $orderBy = null)
 * @method Server[]    findAll()
 * @method Server[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ServerRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Server::class);
    }

    /**
     * @return Server[]
     */
    public function findAllOrderByRenewalDate(): array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT s
            FROM App\Entity\Server s
            ORDER BY s.renewalDate ASC'
        );

        // returns an array of Product objects
        return $query->getResult();
    }
}
