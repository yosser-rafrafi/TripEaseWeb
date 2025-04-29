<?php
// src/Controller/CalendarController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\VoyageRepository;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Request;

class CalendarController extends AbstractController
{
 
    
    #[Route('/fc-load-events', name: 'fc_load_events')]
    public function loadEvents(
        Request $request, 
        VoyageRepository $voyageRepo,
        Security $security
    ): JsonResponse {
        $user = $security->getUser();
        if (!$user) {
            return $this->json([], 403);
        }
    
        // Debug: vérifiez les paramètres de date
        error_log('Period: '.$request->query->get('start').' to '.$request->query->get('end'));
    
        $voyages = $voyageRepo->findVoyagesByUser($user);
        error_log('Voyages found: '.count($voyages));
    
        $events = [];
        foreach ($voyages as $voyage) {
            if (!$voyage->getDateDepart() || !$voyage->getDateRetour()) {
                continue;
            }
    
            $events[] = [
                'title' => $voyage->getTitle(),
                'start' => $voyage->getDateDepart()->format('Y-m-d'),
                'end' => $voyage->getDateRetour()->format('Y-m-d'),
                'url' => $this->generateUrl('app_voyage_show_employee', ['id' => $voyage->getId()])
            ];
        }
    
        return $this->json($events);
    }
}