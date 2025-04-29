<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

class LoginSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    private $urlGenerator;
    private $security;

    public function __construct(UrlGeneratorInterface $urlGenerator, Security $security)
    {
        $this->urlGenerator = $urlGenerator;
        $this->security = $security;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token): Response
    {
        if ($this->security->isGranted('ROLE_ADMIN')) {
            return new RedirectResponse($this->urlGenerator->generate('app_admin'));
        }

        if ($this->security->isGranted('ROLE_MANAGER')) {
            return new RedirectResponse($this->urlGenerator->generate('app_manager'));
        }

        if ($this->security->isGranted('ROLE_EMPLOYE')) {
            return new RedirectResponse($this->urlGenerator->generate('app_employee_home'));
        }

        // Default redirect if no specific role is matched
        return new RedirectResponse($this->urlGenerator->generate('app_employee_home'));
    }
} 