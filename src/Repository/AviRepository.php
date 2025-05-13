<?php

namespace App\Repository;

use App\Entity\Avi;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Avi>
 */
class AviRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Avi::class);
    }

//    /**
//     * @return Avi[] Returns an array of Avi objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Avi
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

public function getAvisQuery()
{
    return $this->createQueryBuilder('a')
        ->orderBy('a.dateAvis', 'DESC') // exemple : trier par date descendante
        ->getQuery();
}
public function getAvisByHotelQuery(int $hotelId)
{
    return $this->createQueryBuilder('a')
        ->where('a.hotel = :hotelId')
        ->setParameter('hotelId', $hotelId)
        ->orderBy('a.dateAvis', 'DESC')
        ->getQuery();
}

}
