<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class EmployeeHomeController extends AbstractController
{
    #[Route('/employee/home', name: 'app_employee_home')]
    public function index(): Response
    {
        return $this->render('employee_home/index.html.twig', [
            'controller_name' => 'EmployeeHomeController',
        ]);
    }
}
