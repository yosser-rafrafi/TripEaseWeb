<?php

namespace App\Repository;

use App\Entity\Chambre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Chambre>
 */
class ChambreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Chambre::class);
    }

//    /**
//     * @return Chambre[] Returns an array of Chambre objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Chambre
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }



public function findAvailableRooms(\DateTime $startDate, \DateTime $endDate, int $hotelId): array
{
    $qb = $this->createQueryBuilder('c');

    $subQb = $this->getEntityManager()->createQueryBuilder()
        ->select('1')
        ->from('App\Entity\Reservationhotel', 'r')
        ->where('r.chambre = c')
        ->andWhere('r.date_debut <= :endDate')
        ->andWhere('r.date_fin >= :startDate');

    $qb->andWhere('c.hotel = :hotelId')
        ->andWhere($qb->expr()->not($qb->expr()->exists($subQb->getDQL())))
        ->setParameter('hotelId', $hotelId)
        ->setParameter('startDate', $startDate)
        ->setParameter('endDate', $endDate);

    return $qb->getQuery()->getResult();
}



}
