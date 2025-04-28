<?php
// src/Controller/StatFraisController.php
namespace App\Controller;

use App\Repository\FraiRepository;
use App\Repository\AvanceFraiRepository;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\BarChart;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StatFraisController extends AbstractController
{
    #[Route('/stats/frais', name: 'stats_frais')]
    public function index(
        FraiRepository $fraisRepo,
        AvanceFraiRepository $avanceRepo
    ): Response {
        // ─────────────── PieChart : répartition des frais par type ───────────────
        $rawFrais = $fraisRepo->findTotalByType();
        $dataFrais = [['Type', 'Montant']];
        foreach ($rawFrais as $r) {
            $dataFrais[] = [$r['type'], (float) $r['total']];
        }
        $pieChart = new PieChart();
        $pieChart->getData()->setArrayToDataTable($dataFrais);
        $pieChart->getOptions()
            ->setTitle('Répartition des frais par type')
            ->setPieHole(0.4)
            ->setWidth(500)
            ->setHeight(300)
        ;

        // ──────── BarChart : Top 5 employés par nombre de demandes ─────────
        $rawTop = $avanceRepo->findTop5ByRequests();
        $dataTop = [['Employé', 'Nombre de demandes']];
        foreach ($rawTop as $r) {
            $dataTop[] = [$r['employeeName'], (int) $r['total']];
        }
        $barChartTop5 = new BarChart();
        $barChartTop5->getData()->setArrayToDataTable($dataTop);
        $barChartTop5->getOptions()
            ->setTitle('Top 5 des employés – demandes d’avances')
            ->setWidth(600)
            ->setHeight(400)
        ;

        return $this->render('back/manager/stats/frais.html.twig', [
            'pieChart'     => $pieChart,
            'barChartTop5' => $barChartTop5,
        ]);
    }
}
