<?php

// src/Controller/DashboardController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends BaseController
{
<<<<<<< HEAD
    #[Route('/dashboard', name: 'dashboard')]
=======
    #[Route('/', name: 'dashboard')]
>>>>>>> 09b8388c89382e4ec195998d936bfb04cb5d37ed
    public function index(): Response
    {
        return $this->render('back/dashboard/index.html.twig', [
            'stats' => [
                'visitors' => '10k',
                'volume' => '100%',
                'files' => '2000+',
                'mails' => '120',
                'ratings' => '4000+',
                'shares' => '1000',
                'network' => '600',
                'achievements' => '17'
            ],
            'support' => [
                'total' => '350',
                'open' => '10',
                'running' => '5',
                'solved' => '3'
            ],
            'projects' => [
                // Project data
            ],
            'updates' => [
                // Updates data
            ],
            'reviews' => [
                // Reviews data
            ],
            'messages' => [
                // Messages data
            ]
        ]);
    }

    #[Route('/back/form/elements', name: 'back_form_elements')]
    public function elements(): Response
    {
        return $this->render('back/form/elements.html.twig');
    }
}
