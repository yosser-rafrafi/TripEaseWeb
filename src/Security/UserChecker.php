<?php
namespace App\Security;

use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;

class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user)
    {
        if (method_exists($user, 'isActive') && !$user->isActive()) {
            throw new CustomUserMessageAccountStatusException('Votre compte est désactivé. Veuillez contacter l\'administrateur.');
        }
    }

    public function checkPostAuth(UserInterface $user)
    {
        // Optionnel : autres vérifications après login
    }
} 