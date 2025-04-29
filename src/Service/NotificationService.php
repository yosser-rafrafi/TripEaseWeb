<?php

namespace App\Service;

use App\Entity\User;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class NotificationService
{
    private $mailer;
    private $entityManager;
    private $params;

    public function __construct(
        MailerInterface $mailer,
        EntityManagerInterface $entityManager,
        ParameterBagInterface $params
    ) {
        $this->mailer = $mailer;
        $this->entityManager = $entityManager;
        $this->params = $params;
    }

    /**
     * Notifie les administrateurs d'un nouvel utilisateur
     */
    public function notifyAdminsAboutNewUser(User $user): void
    {
        $admins = $this->entityManager->getRepository(User::class)
            ->findBy(['role' => 'ADMIN']);

        foreach ($admins as $admin) {
            $this->sendEmail(
                $admin->getEmail(),
                'Nouvel utilisateur inscrit - TripEase',
                $this->getNewUserTemplate($user)
            );
        }
    }

    /**
     * Notifie un utilisateur du changement de statut de son compte
     */
    public function notifyUserAboutStatusChange(User $user): void
    {
        $status = $user->isActive() ? 'activé' : 'désactivé';
        $this->sendEmail(
            $user->getEmail(),
            'Changement de statut de votre compte - TripEase',
            $this->getStatusChangeTemplate($user, $status)
        );
    }

    /**
     * Envoie un email
     */
    private function sendEmail(string $to, string $subject, string $htmlContent): void
    {
        $email = (new Email())
            ->from($this->params->get('app.mailer_from'))
            ->to($to)
            ->subject($subject)
            ->html($htmlContent);

        $this->mailer->send($email);
    }

    /**
     * Template pour la notification de nouvel utilisateur
     */
    private function getNewUserTemplate(User $user): string
    {
        return "
            <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; background-color: #ffffff; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1);'>
                <div style='text-align: center; margin-bottom: 30px;'>
                    <h1 style='color: #007B8A; margin-bottom: 10px;'>Nouvel utilisateur inscrit</h1>
                    <p style='color: #666; font-size: 16px;'>TripEase - Administration</p>
                </div>
                
                <div style='margin-bottom: 30px;'>
                    <p style='color: #333; font-size: 16px; line-height: 1.5;'>Un nouvel utilisateur s'est inscrit sur TripEase :</p>
                    <ul style='list-style: none; padding: 0;'>
                        <li style='margin-bottom: 10px;'><strong>Nom :</strong> {$user->getNom()}</li>
                        <li style='margin-bottom: 10px;'><strong>Prénom :</strong> {$user->getPrenom()}</li>
                        <li style='margin-bottom: 10px;'><strong>Email :</strong> {$user->getEmail()}</li>
                        <li style='margin-bottom: 10px;'><strong>Rôle :</strong> {$user->getRole()}</li>
                    </ul>
                </div>

                <div style='margin-top: 30px; text-align: center;'>
                    <a href='{$this->params->get('app.admin_url')}/users' style='background-color: #007B8A; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Voir le profil</a>
                </div>
            </div>
        ";
    }

    /**
     * Template pour la notification de changement de statut
     */
    private function getStatusChangeTemplate(User $user, string $status): string
    {
        return "
            <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; background-color: #ffffff; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1);'>
                <div style='text-align: center; margin-bottom: 30px;'>
                    <h1 style='color: #007B8A; margin-bottom: 10px;'>Changement de statut de votre compte</h1>
                    <p style='color: #666; font-size: 16px;'>TripEase - Notification</p>
                </div>
                
                <div style='margin-bottom: 30px;'>
                    <p style='color: #333; font-size: 16px; line-height: 1.5;'>Bonjour {$user->getPrenom()},</p>
                    <p style='color: #333; font-size: 16px; line-height: 1.5;'>Votre compte a été {$status} par un administrateur.</p>
                </div>

                <div style='margin-top: 30px; text-align: center;'>
                    <a href='{$this->params->get('app.website_url')}/contact' style='background-color: #007B8A; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Contacter le support</a>
                </div>
            </div>
        ";
    }
} 