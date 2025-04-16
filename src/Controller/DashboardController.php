<?php

// src/Controller/DashboardController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends BaseController
{
    #[Route('/dashboard', name: 'dashboard')]
    public function index(): Response
    {
        // Redirige vers la page d'index des voyages (app_voyage_index)
        return $this->redirectToRoute('app_voyage_index');
    }
}
