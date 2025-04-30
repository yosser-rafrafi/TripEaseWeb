<?php
namespace App\Service;

use App\Entity\User;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailingService
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function notifyUser(User $user, string $subject, string $message)
    {
        $email = (new Email())
            ->from('Yosser.Rafrafi@esprit.tn')
            ->to($user->getEmail())
            ->subject($subject)
            ->text($message);

        $this->mailer->send($email);
    }
}
