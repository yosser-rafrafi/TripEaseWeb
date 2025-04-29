<?php

namespace App\Controller;

use App\Entity\Flight;
use App\Form\FlightType;
use App\Repository\FlightRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\AviationStackService;
use Symfony\UX\Map\Map;
use Symfony\UX\Map\Point;

#[Route('/flight')]
final class FlightController extends AbstractController{
    #[Route(name: 'app_flight_index', methods: ['GET'])]
    public function index(FlightRepository $flightRepository): Response
    {
        return $this->render('flight/index.html.twig', [
            'flights' => $flightRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_flight_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $flight = new Flight();
        $form = $this->createForm(FlightType::class, $flight);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($flight);
            $entityManager->flush();

            return $this->redirectToRoute('app_flight_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('flight/new.html.twig', [
            'flight' => $flight,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_flight_show', methods: ['GET'])]
    public function show(Flight $flight): Response
    {
        
        return $this->render('flight/show.html.twig', [
            'flight' => $flight,
            
        ]);

       
    }
  
    

    #[Route('/{id}/edit', name: 'app_flight_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Flight $flight, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FlightType::class, $flight);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_flight_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('flight/edit.html.twig', [
            'flight' => $flight,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_flight_delete', methods: ['POST'])]
    public function delete(Request $request, Flight $flight, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$flight->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($flight);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_flight_index', [], Response::HTTP_SEE_OTHER);
    }
}
