<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Repository\UserRepository;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    #[IsGranted('ROLE_ADMIN')]
    public function index(): Response
    {
        return $this->render('back/dashboard/index.html.twig');
    }

    #[Route('/admin/users', name: 'app_admin_users')]
    #[IsGranted('ROLE_ADMIN')]
    public function listUsers(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();
        
        // Statistiques de base
        $stats = [
            'total' => count($users),
            'active' => count(array_filter($users, fn($user) => $user->isActive())),
            'managers' => count(array_filter($users, fn($user) => in_array('ROLE_MANAGER', $user->getRoles()))),
            'clients' => count(array_filter($users, fn($user) => count($user->getRoles()) === 1)),
            'by_role' => [
                'admin' => count(array_filter($users, fn($user) => in_array('ROLE_ADMIN', $user->getRoles()))),
                'manager' => count(array_filter($users, fn($user) => in_array('ROLE_MANAGER', $user->getRoles()))),
                'client' => count(array_filter($users, fn($user) => count($user->getRoles()) === 1))
            ],
            'by_status' => [
                'active' => count(array_filter($users, fn($user) => $user->isActive())),
                'inactive' => count(array_filter($users, fn($user) => !$user->isActive()))
            ]
        ];

        return $this->render('admin/users.html.twig', [
            'users' => $users,
            'stats' => $stats
        ]);
    }

    #[Route('/admin/users/{id}/edit', name: 'app_admin_edit_user', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function editUser(User $user, Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('POST')) {
            $user->setNom($request->request->get('nom'));
            $user->setPrenom($request->request->get('prenom'));
            $user->setEmail($request->request->get('email'));
            $user->setIsActive($request->request->has('is_active'));
            
            // Gestion des rôles
            $roles = [];
            if ($request->request->has('role_admin')) {
                $roles[] = 'ROLE_ADMIN';
            }
            if ($request->request->has('role_manager')) {
                $roles[] = 'ROLE_MANAGER';
            }
            if (empty($roles)) {
                $roles[] = 'ROLE_USER';
            }
            $user->setRoles($roles);

            $entityManager->flush();
            return new JsonResponse(['success' => true]);
        }

        return new JsonResponse([
            'id' => $user->getId(),
            'nom' => $user->getNom(),
            'prenom' => $user->getPrenom(),
            'email' => $user->getEmail(),
            'isActive' => $user->isActive(),
            'roles' => $user->getRoles()
        ]);
    }

    #[Route('/admin/users/{id}/delete', name: 'app_admin_delete_user', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function deleteUser(User $user, EntityManagerInterface $entityManager): JsonResponse
    {
        if ($user->getId() === $this->getUser()->getId()) {
            return new JsonResponse(['success' => false, 'message' => 'Vous ne pouvez pas supprimer votre propre compte']);
        }

        $entityManager->remove($user);
        $entityManager->flush();
        return new JsonResponse(['success' => true]);
    }

    #[Route('/admin/users/{id}/toggle-active', name: 'app_admin_toggle_active', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function toggleActive(User $user, EntityManagerInterface $entityManager): JsonResponse
    {
        $user->setIsActive(!$user->isActive());
        $entityManager->flush();
        return new JsonResponse(['success' => true]);
    }
} 