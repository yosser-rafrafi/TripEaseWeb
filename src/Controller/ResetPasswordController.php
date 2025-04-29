<?php

namespace App\Controller;

use App\Entity\ResetPasswordRequest;
use App\Entity\User;
use App\Form\ResetPasswordType;
use App\Repository\ResetPasswordRequestRepository;
use App\Repository\UserRepository;
use App\Service\EmailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class ResetPasswordController extends AbstractController
{
    #[Route('/forgot-password', name: 'app_forgot_password')]
    public function forgotPassword(
        Request $request,
        UserRepository $userRepository,
        EntityManagerInterface $entityManager,
        EmailService $emailService
    ): Response {
        if ($request->isMethod('POST')) {
            $email = $request->request->get('email');
            $user = $userRepository->findOneBy(['email' => $email]);

            if ($user) {
                // Generate reset code
                $resetCode = substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 6);

                // Create reset password request
                $resetRequest = new ResetPasswordRequest();
                $resetRequest->setUser($user);
                $resetRequest->setResetCode($resetCode);

                $entityManager->persist($resetRequest);
                $entityManager->flush();

                // Send email
                $emailService->sendResetPasswordEmail($email, $resetCode);

                $this->addFlash('success', 'Un email contenant les instructions de réinitialisation a été envoyé.');
                return $this->redirectToRoute('app_login');
            }

            $this->addFlash('error', 'Aucun compte n\'est associé à cette adresse email.');
        }

        return $this->render('security/forgot_password.html.twig');
    }

    #[Route('/send-reset-code', name: 'app_send_reset_code', methods: ['POST'])]
    public function sendResetCode(
        Request $request,
        UserRepository $userRepository,
        EntityManagerInterface $entityManager,
        EmailService $emailService
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);
        $email = $data['email'] ?? null;

        if (!$email) {
            return new JsonResponse(['success' => false, 'message' => 'Email requis']);
        }

        $user = $userRepository->findOneBy(['email' => $email]);

        if (!$user) {
            return new JsonResponse(['success' => false, 'message' => 'Aucun compte trouvé avec cet email']);
        }

        // Générer un code de 6 chiffres
        $resetCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Sauvegarder le code dans l'utilisateur
        $user->setResetPassword($resetCode);
        $entityManager->flush();

        // Envoyer l'email
        $emailService->sendResetPasswordEmail($email, $resetCode);

        return new JsonResponse(['success' => true]);
    }

    #[Route('/reset-password', name: 'app_reset_password_ajax', methods: ['POST'])]
    public function resetPassword(
        Request $request,
        UserRepository $userRepository,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);
        $code = $data['code'] ?? null;
        $password = $data['password'] ?? null;

        if (!$code || !$password) {
            return new JsonResponse(['success' => false, 'message' => 'Code et mot de passe requis']);
        }

        // Trouver l'utilisateur avec ce code de réinitialisation
        $user = $userRepository->findOneBy(['reset_password' => $code]);

        if (!$user) {
            return new JsonResponse(['success' => false, 'message' => 'Code invalide']);
        }

        // Vérifier si le code n'a pas plus d'une heure
        if ($user->getResetPassword() === null) {
            return new JsonResponse(['success' => false, 'message' => 'Code de réinitialisation expiré']);
        }

        // Mettre à jour le mot de passe
        $hashedPassword = $passwordHasher->hashPassword($user, $password);
        $user->setPassword($hashedPassword);
        
        // Effacer le code de réinitialisation
        $user->setResetPassword(null);
        
        $entityManager->flush();

        return new JsonResponse(['success' => true]);
    }
} 