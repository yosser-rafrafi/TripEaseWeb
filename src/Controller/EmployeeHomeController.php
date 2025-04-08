<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
<<<<<<< HEAD
use Symfony\Component\Routing\Annotation\Route;

class EmployeeHomeController extends AbstractController
=======
use Symfony\Component\Routing\Attribute\Route;

final class EmployeeHomeController extends AbstractController
>>>>>>> 09b8388c89382e4ec195998d936bfb04cb5d37ed
{
    #[Route('/employee/home', name: 'app_employee_home')]
    public function index(): Response
    {
<<<<<<< HEAD
        return $this->render('front/employee_home/index.html.twig');
    }

    #[Route('/employee/travel-request', name: 'app_travel_request')]
    public function travelRequest(): Response
    {
        return $this->render('front/employee_home/travel_request.html.twig');
    }

    #[Route('/employee/my-travels', name: 'app_my_travels')]
    public function myTravels(): Response
    {
        return $this->render('front/employee_home/my_travels.html.twig');
    }

    #[Route('/employee/expenses', name: 'app_expenses')]
    public function expenses(): Response
    {
        return $this->render('front/employee_home/expenses.html.twig');
=======
        return $this->render('front/employee_home/index.html.twig', [
            'controller_name' => 'EmployeeHomeController',
        ]);
>>>>>>> 09b8388c89382e4ec195998d936bfb04cb5d37ed
    }
}
