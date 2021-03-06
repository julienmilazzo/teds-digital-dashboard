<?php

namespace App\Repository;

use App\Entity\DomainName;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DomainName|null find($id, $lockMode = null, $lockVersion = null)
 * @method DomainName|null findOneBy(array $criteria, array $orderBy = null)
 * @method DomainName[]    findAll()
 * @method DomainName[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DomainNameRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DomainName::class);
    }

    /**
     * @return DomainName[]
     */
    public function findAllOrderByRenewalDate(): array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT dn
            FROM App\Entity\DomainName dn
            ORDER BY dn.renewalDate ASC'
        );

        // returns an array of Product objects
        return $query->getResult();
    }
}
