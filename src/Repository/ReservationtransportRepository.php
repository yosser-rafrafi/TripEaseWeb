<?php
namespace App\Repository;

use App\Entity\Reservationtransport;
use App\Entity\Transport;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Reservationtransport>
 */
class ReservationtransportRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservationtransport::class);
    }

    // Check if any reservation exists for a specific transport
    public function hasReservations(Transport $transport): bool
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.transport = :transport')
            ->setParameter('transport', $transport)
            ->select('COUNT(r.id)') // Count the number of reservations
            ->getQuery()
            ->getSingleScalarResult() > 0; // Returns true if any reservations exist
    }
}
