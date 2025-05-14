<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
// Ajout de l'import correct pour CustomCredentials :
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\CustomCredentials;

class LoginFormAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';

    public function __construct(private UrlGeneratorInterface $urlGenerator)
    {
    }

    public function authenticate(Request $request): Passport
    {
        // Récupération des données du formulaire
        $email     = $request->request->get('email', '');
        $password  = $request->request->get('password', '');
        $csrfToken = $request->request->get('_csrf_token', '');
    
        // Mémorisation du dernier username saisi
        $request->getSession()->set(Security::LAST_USERNAME, $email);
    
        return new Passport(
            new UserBadge($email),
            new CustomCredentials(
                // Callback : comparaison en clair
                function (string $credentials, $user): bool {
                    return $credentials === $user->getPassword();
                },
                // On fournit la saisie utilisateur
                $password
            ),
            [
                // On fournit maintenant la variable correctement définie
                new CsrfTokenBadge('authenticate', $csrfToken),
            ]
        );
    }
    
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        $user = $token->getUser();
        if (in_array('ROLE_ADMIN', $user->getRoles()) || in_array('ROLE_MANAGER', $user->getRoles())) {
            return new RedirectResponse($this->urlGenerator->generate('dashboard'));
        }

        return new RedirectResponse($this->urlGenerator->generate('app_employee_home'));
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
