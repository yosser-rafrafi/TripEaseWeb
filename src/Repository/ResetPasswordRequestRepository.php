<?php

namespace App\Repository;

use App\Entity\ResetPasswordRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ResetPasswordRequest>
 *
 * @method ResetPasswordRequest|null find($id, $lockMode = null, $lockVersion = null)
 * @method ResetPasswordRequest|null findOneBy(array $criteria, array $orderBy = null)
 * @method ResetPasswordRequest[]    findAll()
 * @method ResetPasswordRequest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ResetPasswordRequestRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ResetPasswordRequest::class);
    }

    public function findValidRequest(string $resetCode): ?ResetPasswordRequest
    {
        $request = $this->findOneBy(['resetCode' => $resetCode]);
        
        if (!$request || !$request->isValid()) {
            return null;
        }

        return $request;
    }

    public function removeExpiredRequests(): void
    {
        $now = new \DateTimeImmutable();
        $oneHourAgo = $now->modify('-1 hour');

        $qb = $this->createQueryBuilder('r')
            ->delete()
            ->where('r.createdAt <= :oneHourAgo')
            ->setParameter('oneHourAgo', $oneHourAgo);

        $qb->getQuery()->execute();
    }
} 