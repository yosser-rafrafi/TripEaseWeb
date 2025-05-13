<?php

namespace App\Repository;

use App\Entity\Transport;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Transport>
 */
class TransportRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Transport::class);
    }

    // Find transports by selected agency and transport type
    public function findByFilters(?string $selectedAgency, ?string $selectedTransportType)
    {
        $queryBuilder = $this->createQueryBuilder('t');

        if ($selectedAgency) {
            $queryBuilder->andWhere('t.transport_agency = :agency')
                         ->setParameter('agency', $selectedAgency);
        }

        if ($selectedTransportType) {
            $queryBuilder->andWhere('t.transport_type = :type')
                         ->setParameter('type', $selectedTransportType);
        }

        return $queryBuilder->getQuery()->getResult();
    }
}
