<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserSettingsType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\NotificationService;

class UserController extends AbstractController
{
    #[Route('/user/settings', name: 'user_settings')]
    #[IsGranted('ROLE_USER')]
    public function settings(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $form = $this->createForm(UserSettingsType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->get('plainPassword')->getData();
            if ($plainPassword) {
                $user->setPassword($passwordHasher->hashPassword($user, $plainPassword));
            }

            $entityManager->flush();
            $this->addFlash('success', 'Your settings have been updated successfully.');
            return $this->redirectToRoute('user_settings');
        }

        return $this->render('user/settings.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/users', name: 'app_users_list')]
    #[IsGranted('ROLE_ADMIN')]
    public function listUsers(EntityManagerInterface $entityManager): Response
    {
        // Récupérer tous les utilisateurs pour le débogage
        $allUsers = $entityManager->getRepository(User::class)->findAll();
        
        // Afficher les rôles de tous les utilisateurs pour le débogage
        foreach ($allUsers as $user) {
            dump($user->getEmail() . ' - Role: ' . $user->getRole());
        }

        // Récupérer les utilisateurs avec le rôle EMPLOYE
        $users = $entityManager->getRepository(User::class)
            ->createQueryBuilder('u')
            ->where('u.role = :role')
            ->setParameter('role', 'EMPLOYE')  // Changé de ROLE_EMPLOYE à EMPLOYE
            ->getQuery()
            ->getResult();

        return $this->render('back/users/list.html.twig', [
            'users' => $users
        ]);
    }

    #[Route('/admin/users/{id}/delete', name: 'app_user_delete', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
            $this->addFlash('success', 'User deleted successfully.');
        }

        return $this->redirectToRoute('app_users_list');
    }

    #[Route('/admin/users/{id}/toggle-active', name: 'app_user_toggle_active', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function toggleActive(
        User $user, 
        EntityManagerInterface $entityManager, 
        Request $request,
        NotificationService $notificationService
    ): Response
    {
        if ($this->isCsrfTokenValid('toggle' . $user->getId(), $request->request->get('_token'))) {
            $oldStatus = $user->isActive();
            $user->setIsActive(!$oldStatus);
            $entityManager->flush();

            // Envoyer une notification à l'utilisateur
            $notificationService->notifyUserAboutStatusChange($user);
        }
        return $this->redirectToRoute('app_users_list');
    }

    #[Route('/admin/users/search', name: 'app_users_search', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function searchUsers(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $searchTerm = $request->query->get('q', '');
        $role = $request->query->get('role', '');
        $status = $request->query->get('status', '');
        $sortBy = $request->query->get('sortBy', 'id');
        $sortOrder = $request->query->get('sortOrder', 'ASC');
        $page = $request->query->get('page', 1);
        $limit = $request->query->get('limit', 10);
        
        $qb = $entityManager->getRepository(User::class)
            ->createQueryBuilder('u');
        
        // Condition de recherche textuelle
        if ($searchTerm) {
            $qb->andWhere('u.nom LIKE :search OR u.prenom LIKE :search OR u.email LIKE :search OR u.numTel LIKE :search')
               ->setParameter('search', '%' . $searchTerm . '%');
        }
        
        // Filtre par rôle
        if ($role) {
            $qb->andWhere('u.role = :role')
               ->setParameter('role', $role);
        }
        
        // Filtre par statut
        if ($status !== '') {
            $isActive = $status === 'active';
            $qb->andWhere('u.isActive = :status')
               ->setParameter('status', $isActive);
        }

        // Tri
        $allowedSortFields = ['id', 'nom', 'prenom', 'email', 'role', 'isActive'];
        if (in_array($sortBy, $allowedSortFields)) {
            $qb->orderBy('u.' . $sortBy, $sortOrder);
        }

        // Pagination
        $totalUsers = count($qb->getQuery()->getResult());
        $qb->setFirstResult(($page - 1) * $limit)
           ->setMaxResults($limit);
        
        $users = $qb->getQuery()->getResult();
        
        $results = [];
        foreach ($users as $user) {
            $results[] = [
                'id' => $user->getId(),
                'nom' => $user->getNom(),
                'prenom' => $user->getPrenom(),
                'email' => $user->getEmail(),
                'numTel' => $user->getNumTel(),
                'role' => $user->getRole(),
                'isActive' => $user->isActive(),
                'createdAt' => $user->getCreatedAt() ? $user->getCreatedAt()->format('Y-m-d H:i:s') : null,
                'lastLogin' => $user->getLastLogin() ? $user->getLastLogin()->format('Y-m-d H:i:s') : null
            ];
        }
        
        return new JsonResponse([
            'users' => $results,
            'pagination' => [
                'total' => $totalUsers,
                'page' => $page,
                'limit' => $limit,
                'totalPages' => ceil($totalUsers / $limit)
            ]
        ]);
    }
} 