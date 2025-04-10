<?php

namespace App\Repository;

use App\Entity\AvanceFrai;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AvanceFrai>
 */
class AvanceFraiRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AvanceFrai::class);
    }

    public function save(AvanceFrai $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);
    
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    
    public function remove(AvanceFrai $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);
    
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return AvanceFrai[] Returns an array of AvanceFrai objects
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

//    public function findOneBySomeField($value): ?AvanceFrai
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
