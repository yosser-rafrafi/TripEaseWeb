<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\VoyageRepository;
use App\Entity\Voyage;
use Symfony\Component\Routing\Annotation\Route;

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
    public function myTravels(VoyageRepository $voyageRepository): Response
    {

        $user = $this->getUser();

        $voyages = $voyageRepository->findVoyagesByUser($user);
     
         return $this->render('front/Voyage/my_travels.html.twig', [
             'voyages' => $voyages,
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
        return $this->render('front/employee_home/expenses.html.twig');
    }
}
