<?php

namespace App\Controller\Admin;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/users')]
class UserController extends AbstractController
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    #[Route('', name: 'admin_users_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('admin/users/list.html.twig');
    }

    #[Route('/ajax', name: 'admin_users_ajax', methods: ['GET'])]
    public function getUsers(Request $request): JsonResponse
    {
        try {
            // Récupération des paramètres de la requête
            $search = $request->query->get('search', '');
            $sort = $request->query->get('sort', 'email');
            $order = $request->query->get('order', 'asc');
            $page = (int) $request->query->get('page', 1);
            $limit = (int) $request->query->get('limit', 10);
            $role = $request->query->get('role', '');

            // Calcul de l'offset pour la pagination
            $offset = ($page - 1) * $limit;

            // Récupération des utilisateurs filtrés
            $users = $this->userRepository->findByFilters($search, $sort, $order, $role, $limit, $offset);

            // Récupération des statistiques
            $stats = [
                'total' => $this->userRepository->countTotal(),
                'active' => $this->userRepository->countByStatus(true),
                'inactive' => $this->userRepository->countByStatus(false),
                'roles' => [
                    'ADMIN' => $this->userRepository->countByRole('ADMIN'),
                    'MANAGER' => $this->userRepository->countByRole('MANAGER'),
                    'EMPLOYE' => $this->userRepository->countByRole('EMPLOYE')
                ]
            ];

            // Préparation des données de pagination
            $totalUsers = $this->userRepository->countTotal();
            $pagination = [
                'total' => $totalUsers,
                'page' => $page,
                'limit' => $limit,
                'pages' => ceil($totalUsers / $limit)
            ];

            // Formatage des données des utilisateurs pour le JSON
            $formattedUsers = array_map(function ($user) {
                return [
                    'id' => $user->getId(),
                    'email' => $user->getEmail(),
                    'prenom' => $user->getPrenom(),
                    'adresse' => $user->getAdresse(),
                    'numTel' => $user->getNumTel(),
                    'role' => $user->getRole(),
                    'is_active' => $user->isActive()
                ];
            }, $users);

            // Retour de la réponse JSON
            return $this->json([
                'users' => $formattedUsers,
                'stats' => $stats,
                'pagination' => $pagination
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/{id}', name: 'admin_users_delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $user = $this->userRepository->find($id);

        if (!$user) {
            return $this->json(['message' => 'Utilisateur non trouvé'], Response::HTTP_NOT_FOUND);
        }

        try {
            $this->userRepository->remove($user, true);
            return $this->json(['message' => 'Utilisateur supprimé avec succès']);
        } catch (\Exception $e) {
            return $this->json(['message' => 'Erreur lors de la suppression'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/{id}/toggle-active', name: 'admin_users_toggle_active', methods: ['POST'])]
    public function toggleActive(int $id): JsonResponse
    {
        $user = $this->userRepository->find($id);
        if (!$user) {
            return $this->json(['message' => 'Utilisateur non trouvé'], Response::HTTP_NOT_FOUND);
        }
        $user->setIsActive(!$user->isActive());
        $this->userRepository->save($user, true);
        return $this->json([
            'id' => $user->getId(),
            'is_active' => $user->isActive(),
            'message' => 'Statut mis à jour avec succès'
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_users_edit', methods: ['POST'])]
    public function edit(int $id, Request $request): JsonResponse
    {
        try {
            $user = $this->userRepository->find($id);
            if (!$user) {
                return $this->json(['message' => 'Utilisateur non trouvé'], Response::HTTP_NOT_FOUND);
            }

            $data = json_decode($request->getContent(), true);

            $user->setPrenom($data['prenom'])
                 ->setEmail($data['email'])
                 ->setRole($data['role'])
                 ->setAdresse($data['adresse'])
                 ->setNumTel($data['numTel']);

            $this->userRepository->save($user, true);

            return $this->json([
                'message' => 'Utilisateur mis à jour avec succès',
                'user' => [
                    'id' => $user->getId(),
                    'prenom' => $user->getPrenom(),
                    'email' => $user->getEmail(),
                    'role' => $user->getRole(),
                    'adresse' => $user->getAdresse(),
                    'numTel' => $user->getNumTel(),
                    'is_active' => $user->isActive()
                ]
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'message' => 'Erreur lors de la mise à jour : ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
} 