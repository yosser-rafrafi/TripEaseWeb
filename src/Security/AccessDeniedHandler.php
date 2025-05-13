<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class AccessDeniedHandler implements AccessDeniedHandlerInterface
{
    private $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    public function handle(Request $request, AccessDeniedException $accessDeniedException): ?Response
    {
        // Si l'utilisateur n'est pas connecté, rediriger vers la page de connexion
        if (!$request->getSession()->get('_security_main')) {
            return new RedirectResponse($this->urlGenerator->generate('app_login'));
        }

        // Si l'utilisateur est connecté mais n'a pas les droits, rediriger vers le dashboard
        return new RedirectResponse($this->urlGenerator->generate('dashboard'));
    }
} 