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
} 