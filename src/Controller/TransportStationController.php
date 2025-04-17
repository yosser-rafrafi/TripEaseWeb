<?php
namespace App\Controller;

use App\Service\WashingtonDatabaseService;
use App\Service\SwissTransportApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class TransportStationController extends AbstractController
{
    private $washingtonService;
    private $swissService;

    public function __construct(WashingtonDatabaseService $washingtonService, SwissTransportApiService $swissService)
    {
        $this->washingtonService = $washingtonService;
        $this->swissService = $swissService;
    }

    #[Route('/stations', name: 'get_stations')]
    public function getStations()
    {
        $washingtonData = $this->washingtonService->getStations();
        $swissData = $this->swissService->getStations();
    
        $washingtonDataArray = [];
        $swissDataArray = [];
    
        if ($washingtonData instanceof JsonResponse) {
            $washingtonDataArray = json_decode($washingtonData->getContent(), true);
        }
    
        if ($swissData instanceof JsonResponse) {
            $swissDataArray = json_decode($swissData->getContent(), true);
        }
    
        $stations = [
            'swiss' => $swissDataArray,
            'washington' => $washingtonDataArray
        ];
    
        return $this->render('back/manager/transport/stations.html.twig', [
            'stations' => $stations
        ]);
    }
    
}

