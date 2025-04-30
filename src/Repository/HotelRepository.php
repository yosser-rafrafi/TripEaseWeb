<?php

namespace App\Repository;
use App\Entity\User;

use App\Entity\Hotel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Hotel>
 */
class HotelRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Hotel::class);
    }

//    /**
//     * @return Hotel[] Returns an array of Hotel objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('h')
//            ->andWhere('h.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('h.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Hotel
//    {
//        return $this->createQueryBuilder('h')
//            ->andWhere('h.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }


public function findByUser(User $user)
{
    return $this->createQueryBuilder('r')
        ->andWhere('r.user = :user')
        ->setParameter('user', $user)
        ->orderBy('r.date_reservation', 'DESC')
        ->getQuery()
        ->getResult();
}

public function findTopRatedHotelsByCity(string $ville, int $limit = 5): array
{
    return $this->createQueryBuilder('h')
        ->leftJoin('h.avis', 'a')
        ->addSelect('AVG(a.note) as HIDDEN avgNote')
        ->where('h.ville = :ville')
        ->setParameter('ville', $ville)
        ->groupBy('h.id')
        ->having('COUNT(a.idAvis) > 0')
        ->orderBy('avgNote', 'DESC')
        ->setMaxResults($limit)
        ->getQuery()
        ->getResult();
}

}
