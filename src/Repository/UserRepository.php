<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function save(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);

        $this->save($user, true);
    }

    public function findByFilters(string $search = '', string $sort = 'email', string $order = 'asc', string $role = '', int $limit = 10, int $offset = 0): array
    {
        $qb = $this->createQueryBuilder('u');

        // Appliquer le filtre de recherche
        if ($search) {
            $qb->andWhere('u.email LIKE :search OR u.prenom LIKE :search OR u.numTel LIKE :search')
               ->setParameter('search', '%' . $search . '%');
        }

        // Appliquer le filtre de rôle
        if ($role) {
            $qb->andWhere('u.role = :role')
               ->setParameter('role', $role);
        }

        // Appliquer le tri
        $qb->orderBy('u.' . $sort, $order);

        // Appliquer la pagination
        $qb->setFirstResult($offset)
           ->setMaxResults($limit);

        return $qb->getQuery()->getResult();
    }

    public function countTotal(): int
    {
        return $this->createQueryBuilder('u')
            ->select('COUNT(u.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function countByStatus(bool $isActive): int
    {
        return $this->createQueryBuilder('u')
            ->select('COUNT(u.id)')
            ->andWhere('u.isActive = :isActive')
            ->setParameter('isActive', $isActive)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function countByRole(string $role): int
    {
        return $this->createQueryBuilder('u')
            ->select('COUNT(u.id)')
            ->andWhere('u.role = :role')
            ->setParameter('role', $role)
            ->getQuery()
            ->getSingleScalarResult();
    }

//    /**
//     * @return User[] Returns an array of User objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?User
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
