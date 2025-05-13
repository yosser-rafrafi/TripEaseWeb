<?php

namespace App\Controller;

use App\Service\StatisticsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class StatisticsController extends AbstractController
{
    #[Route('/admin/statistics', name: 'app_statistics')]
    #[IsGranted('ROLE_ADMIN')]
    public function index(StatisticsService $statisticsService): Response
    {
        $stats = $statisticsService->getUserStatistics();

        return $this->render('back/statistics/index.html.twig', [
            'stats' => $stats
        ]);
    }
} 