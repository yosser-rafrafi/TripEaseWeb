<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use DateTime;

class StatisticsService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Récupère toutes les statistiques des utilisateurs
     */
    public function getUserStatistics(): array
    {
        return [
            'total_users' => $this->getTotalUsers(),
            'active_users' => $this->getActiveUsers(),
            'users_by_role' => $this->getUsersByRole(),
            'monthly_registrations' => $this->getMonthlyRegistrations(),
            'user_activity' => $this->getUserActivity(),
            'last_registrations' => $this->getLastRegistrations()
        ];
    }

    /**
     * Nombre total d'utilisateurs
     */
    private function getTotalUsers(): int
    {
        return $this->entityManager->getRepository(User::class)
            ->createQueryBuilder('u')
            ->select('COUNT(u.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Nombre d'utilisateurs actifs
     */
    private function getActiveUsers(): int
    {
        return $this->entityManager->getRepository(User::class)
            ->createQueryBuilder('u')
            ->select('COUNT(u.id)')
            ->where('u.isActive = :active')
            ->setParameter('active', true)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Répartition des utilisateurs par rôle
     */
    private function getUsersByRole(): array
    {
        $qb = $this->entityManager->getRepository(User::class)
            ->createQueryBuilder('u')
            ->select('u.role, COUNT(u.id) as count')
            ->groupBy('u.role');

        $results = $qb->getQuery()->getResult();
        
        $stats = [];
        foreach ($results as $result) {
            $stats[$result['role']] = $result['count'];
        }

        return $stats;
    }

    /**
     * Inscriptions mensuelles
     */
    private function getMonthlyRegistrations(): array
    {
        $qb = $this->entityManager->getRepository(User::class)
            ->createQueryBuilder('u')
            ->select('COUNT(u.id) as count, MONTH(u.createdAt) as month, YEAR(u.createdAt) as year')
            ->where('YEAR(u.createdAt) = :year')
            ->setParameter('year', date('Y'))
            ->groupBy('month, year')
            ->orderBy('year', 'ASC')
            ->addOrderBy('month', 'ASC');

        $results = $qb->getQuery()->getResult();
        
        $stats = [];
        foreach ($results as $result) {
            $monthName = date('F', mktime(0, 0, 0, $result['month'], 1));
            $stats[$monthName] = $result['count'];
        }

        return $stats;
    }

    /**
     * Activité des utilisateurs (dernière connexion)
     */
    private function getUserActivity(): array
    {
        $qb = $this->entityManager->getRepository(User::class)
            ->createQueryBuilder('u')
            ->select('COUNT(u.id) as count, DATE(u.lastLogin) as date')
            ->where('u.lastLogin IS NOT NULL')
            ->andWhere('u.lastLogin >= :date')
            ->setParameter('date', new DateTime('-30 days'))
            ->groupBy('date')
            ->orderBy('date', 'ASC');

        $results = $qb->getQuery()->getResult();
        
        $stats = [];
        foreach ($results as $result) {
            $stats[$result['date']] = $result['count'];
        }

        return $stats;
    }

    /**
     * Dernières inscriptions
     */
    private function getLastRegistrations(): array
    {
        return $this->entityManager->getRepository(User::class)
            ->createQueryBuilder('u')
            ->select('u.id, u.nom, u.prenom, u.email, u.role, u.createdAt')
            ->orderBy('u.createdAt', 'DESC')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult();
    }
} 