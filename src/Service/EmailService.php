<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class EmailService
{
    private $mailer;
    private $params;

    public function __construct(MailerInterface $mailer, ParameterBagInterface $params)
    {
        $this->mailer = $mailer;
        $this->params = $params;
    }

    public function sendResetPasswordEmail(string $to, string $resetCode): void
    {
        try {
            $email = (new Email())
                ->from('yasmineelamri37@gmail.com')
                ->to($to)
                ->subject('Réinitialisation de votre mot de passe - TripEase')
                ->html($this->getResetPasswordTemplate($resetCode));

            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            throw new \Exception('Erreur lors de l\'envoi de l\'email: ' . $e->getMessage());
        }
    }

    private function getResetPasswordTemplate(string $resetCode): string
    {
        return "
            <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; background-color: #ffffff; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1);'>
                <div style='text-align: center; margin-bottom: 30px;'>
                    <h1 style='color: #007B8A; margin-bottom: 10px;'>Réinitialisation de votre mot de passe</h1>
                    <p style='color: #666; font-size: 16px;'>TripEase - Votre partenaire de voyage</p>
                </div>
                
                <div style='margin-bottom: 30px;'>
                    <p style='color: #333; font-size: 16px; line-height: 1.5;'>Bonjour,</p>
                    <p style='color: #333; font-size: 16px; line-height: 1.5;'>Vous avez demandé la réinitialisation de votre mot de passe. Voici votre code de réinitialisation :</p>
                </div>

                <div style='text-align: center; margin: 30px 0;'>
                    <div style='background-color: #f5f5f5; padding: 20px; border-radius: 10px; display: inline-block;'>
                        <span style='font-size: 32px; letter-spacing: 5px; color: #007B8A; font-weight: bold;'>{$resetCode}</span>
                    </div>
                </div>

                <div style='margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee;'>
                    <p style='color: #666; font-size: 14px; line-height: 1.5;'>Ce code est valable pendant 1 heure. Si vous n'avez pas demandé cette réinitialisation, vous pouvez ignorer cet email.</p>
                    <p style='color: #666; font-size: 14px; margin-top: 20px;'>Pour des raisons de sécurité, ne partagez jamais ce code avec quelqu'un.</p>
                </div>

                <div style='margin-top: 30px; text-align: center;'>
                    <p style='color: #333; font-size: 16px;'>Cordialement,<br>L'équipe TripEase</p>
                </div>
            </div>
        ";
    }
} 