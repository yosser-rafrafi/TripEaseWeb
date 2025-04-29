<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\EmailService;

class MailerController extends AbstractController
{
    private const DEFAULT_SENDER = 'yasmineelamri37@gmail.com';

    #[Route('/email/test', name: 'app_test_email')]
    public function sendTestEmail(MailerInterface $mailer): Response
    {
        try {
            $email = (new Email())
                ->from(self::DEFAULT_SENDER)
                ->to(self::DEFAULT_SENDER)  // On envoie à la même adresse pour tester
                ->subject('Test Email Configuration - TripEase')
                ->html('
                    <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;">
                        <h1 style="color: #007B8A;">Test de Configuration Email</h1>
                        <p>Bonjour,</p>
                        <p>Si vous recevez cet email, cela signifie que votre configuration SMTP est correcte !</p>
                        <p>Vous pouvez maintenant utiliser le système d\'envoi d\'emails dans votre application.</p>
                        <p>Cordialement,<br>L\'équipe TripEase</p>
                    </div>
                ');

            $mailer->send($email);

            return new Response('Email de test envoyé avec succès! Vérifiez votre boîte de réception.');
        } catch (\Exception $e) {
            return new Response('Erreur lors de l\'envoi de l\'email: ' . $e->getMessage(), 500);
        }
    }

    #[Route('/email/test-reset-password', name: 'app_test_reset_password_email')]
    public function testResetPasswordEmail(EmailService $emailService): Response
    {
        try {
            // Code de test pour la réinitialisation
            $testCode = '123456';
            $testEmail = self::DEFAULT_SENDER;

            // Envoi de l'email de test
            $emailService->sendResetPasswordEmail($testEmail, $testCode);

            return new Response(
                'Email de réinitialisation de mot de passe envoyé avec succès!<br><br>' .
                'Détails du test :<br>' .
                '- Email envoyé à : ' . $testEmail . '<br>' .
                '- Code de réinitialisation : ' . $testCode . '<br>' .
                'Vérifiez votre boîte de réception pour voir le format de l\'email.'
            );
        } catch (\Exception $e) {
            return new Response('Erreur lors du test de réinitialisation : ' . $e->getMessage(), 500);
        }
    }

    #[Route('/email/custom', name: 'app_send_custom_email')]
    public function sendCustomEmail(MailerInterface $mailer, string $to, string $subject, string $content): Response
    {
        try {
            $email = (new Email())
                ->from(self::DEFAULT_SENDER)
                ->to($to)
                ->subject($subject)
                ->html($content);

            $mailer->send($email);

            return new Response('Email personnalisé envoyé avec succès!');
        } catch (\Exception $e) {
            return new Response('Erreur lors de l\'envoi de l\'email: ' . $e->getMessage(), 500);
        }
    }
} 