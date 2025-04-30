<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\VoyageRepository;
use Symfony\Component\Routing\Annotation\Route;
use App\Calendar\VoyageCalendar;
use App\Entity\Mission;
use App\Entity\Voyage;
use Symfony\Component\HttpFoundation\Request;
use CalendarBundle\Event\CalendarEvent;
use CalendarBundle\Entity\Event;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\UX\Map\Map;
use Symfony\UX\Map\Point;
use App\Entity\Notification;
use Doctrine\ORM\EntityManagerInterface;


class EmployeeHomeController extends AbstractController
{
    #[Route('/employee/home', name: 'app_employee_home')]
    public function index(EntityManagerInterface $entityManager): Response
    {
         // Récupère l'utilisateur connecté
         $user = $this->getUser();

         // Récupérer toutes les notifications non lues de l'utilisateur connecté
         $notifications = $entityManager->getRepository(Notification::class)->findBy([
            'user' => $user,
            'isRead' => 0
        ], ['createdAt' => 'DESC']);


       

        return $this->render('front/employee_home/index.html.twig', [
            
            'notifications' => $notifications,
        ]);
    }

    #[Route('/notification/{id}' , name:'notification_mark_as_read')]    
   public function markAsRead(Notification $notification, EntityManagerInterface $entityManager): Response
   {
       // Marquer la notification comme lue
       $notification->setIsRead(true);
       $entityManager->persist($notification);
       $entityManager->flush();
   
       // Récupérer le voyage associé à la notification (si nécessaire, ajustez selon votre logique)
       $voyage = $notification->getVoyage(); // Assurez-vous que vous avez un lien entre Notification et Voyage
   
       $notification->setIsRead(1);
       // Rediriger vers la page de détails du voyage
       return $this->redirectToRoute('app_voyage_show_employee', ['id' => $voyage->getId()]);
   }

    #[Route('/employee/travel-request', name: 'app_travel_request')]
    public function travelRequest(): Response
    {
        return $this->render('front/employee_home/travel_request.html.twig');
    }
    
   

#[Route('/employee/my-travels', name: 'app_my_travels')]
public function myTravels(Request $request, VoyageRepository $voyageRepository, VoyageCalendar $voyageCalendar): Response
{
    $user = $this->getUser();
    $etat = $request->query->get('etat'); 
    //$voyages = $voyageRepository->findVoyagesByUser($user);

    if ($etat) {
        // Filtrer selon l'état
        $voyages = $voyageRepository->findVoyagesByUserAndEtat($user , $etat);
    } else {
        // Aucun filtre => tout afficher
        $voyages = $voyageRepository->findVoyagesByUser($user);
    }
    

    return $this->render('front/Voyage/my_travels.html.twig', [
        'voyages' => $voyages,
       
    ]);
}
    #[Route('/show/{id}', name: 'app_voyage_show_employee', methods: ['GET'])]
    public function show(Voyage $voyage , ParameterBagInterface $params): Response
    {
        $map = (new Map())
            ->center(new Point(48.856614,2.352222))
            ->zoom(12)
        ;
        return $this->render('/front/Voyage/show.html.twig', [
            'voyage' => $voyage,
            'googleMapsApiKey' => $params->get('google_maps_api_key'),
            'map' => $map,

        ]);
    }

    #[Route('/employee/expenses', name: 'app_expenses')]
    public function expenses(): Response
    {
        return $this->redirectToRoute('app_avance_frai_index');
       // return $this->render('front/employee_home/expenses.html.twig');
    }

    #[Route('/mission/show/{id}', name: 'app_mission_employee_show', methods: ['GET'])]
    public function showMission(Mission $mission): Response
    {
        if (!$mission) {
            throw $this->createNotFoundException('Mission non trouvée');
        }
        else
        {
            return $this->render('front/Voyage/missionDetails.html.twig', [
                'mission' => $mission,
            ]);
        }
       
    }

   
}
