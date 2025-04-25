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


class EmployeeHomeController extends AbstractController
{
    #[Route('/employee/home', name: 'app_employee_home')]
    public function index(): Response
    {
        return $this->render('front/employee_home/index.html.twig');
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
    $voyages = $voyageRepository->findVoyagesByUser($user);

    // Récupérez les événements du calendrier
   // $calendarEvents = $voyageCalendar->getEvents($user);

    return $this->render('front/Voyage/my_travels.html.twig', [
        'voyages' => $voyages,
       // 'calendarEvents' => $calendarEvents, // Passer les événements directement
    ]);
}
    #[Route('/show/{id}', name: 'app_voyage_show_employee', methods: ['GET'])]
    public function show(Voyage $voyage): Response
    {
        return $this->render('/front/Voyage/show.html.twig', [
            'voyage' => $voyage,
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
