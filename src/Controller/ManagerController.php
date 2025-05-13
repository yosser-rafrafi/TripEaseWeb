<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class ManagerController extends AbstractController
{
    #[Route('/dashboard', name: 'app_manager')]
    #[IsGranted('ROLE_MANAGER')]
    public function index(): Response
    {
        return $this->render('back/dashboard/index.html.twig');
    }
} 