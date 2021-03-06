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

    /**
     * @return FrenchEchoppe[]
     */
    public function findAllOrderByRenewalDate(): array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT fe
            FROM App\Entity\FrenchEchoppe fe
            ORDER BY fe.renewalDate ASC'
        );

        // returns an array of Product objects
        return $query->getResult();
    }
}
