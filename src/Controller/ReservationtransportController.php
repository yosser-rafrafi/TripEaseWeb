<?php
namespace App\Controller;

use App\Entity\Transport;
use App\Entity\Reservationtransport;
use App\Form\ReservationtransportType;
use App\Repository\ReservationtransportRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;

#[Route('/employee/reservationtransport')]
final class ReservationtransportController extends AbstractController
{
    #[Route('/reservation', name: 'app_reservationtransport_index', methods: ['GET'])]
public function index(ReservationtransportRepository $reservationtransportRepository): Response
{
    $user = $this->getUser(); 

    
    $reservations = $reservationtransportRepository->findBy(['employe' => $user]);

    return $this->render('front/reservationtransport/index.html.twig', [
        'reservationtransports' => $reservations,
    ]);
} 
    #[Route('/', name: 'app_transport_list', methods: ['GET'])]
    public function transportList(EntityManagerInterface $entityManager): Response
    {
        $transports = $entityManager->getRepository(Transport::class)->findAll();
    
        return $this->render('front/reservationtransport/transport_list.html.twig', [
            'transports' => $transports, 
        ]);
    }

    #[Route('/new/{transport_id}', name: 'app_reservationtransport_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, $transport_id): Response
    {
        $transport = $entityManager->getRepository(Transport::class)->find($transport_id);
    
        if (!$transport) {
            throw $this->createNotFoundException('No transport found for id ' . $transport_id);
        }
    
        $reservationtransport = new Reservationtransport();
        $reservationtransport->setTransport($transport); 
    
        $user = $this->getUser();
    
        if ($user instanceof User) {
            $reservationtransport->setEmploye($user); 
            throw $this->createAccessDeniedException('You must be logged in to make a reservation.'); // Set to null if no user is logged in
        }
    
        // Create the form for the reservation
        $form = $this->createForm(ReservationtransportType::class, $reservationtransport);
        $form->handleRequest($request);
    
        // Handle form submission
        if ($form->isSubmitted() && $form->isValid()) {
            // Persist the reservationtransport entity to the database
            $entityManager->persist($reservationtransport);
            $entityManager->flush();
    
            // Redirect to the reservation transport index page after successful creation
            return $this->redirectToRoute('app_reservationtransport_index', [], Response::HTTP_SEE_OTHER);
        }
    
        // Render the form in the template
        return $this->render('front/reservationtransport/new.html.twig', [
            'reservationtransport' => $reservationtransport,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/show/{id}', name: 'app_reservationtransport_show', methods: ['GET'])]
    public function show(Reservationtransport $reservationtransport): Response
    {
        return $this->render('front/reservationtransport/show.html.twig', [
            'reservationtransport' => $reservationtransport,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_reservationtransport_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reservationtransport $reservationtransport, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReservationtransportType::class, $reservationtransport);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_reservationtransport_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('front/reservationtransport/edit.html.twig', [
            'reservationtransport' => $reservationtransport,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_reservationtransport_delete', methods: ['POST'])]
    public function delete(Request $request, Reservationtransport $reservationtransport, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $reservationtransport->getId(), $request->request->get('_token'))) {
            $entityManager->remove($reservationtransport);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reservationtransport_index', [], Response::HTTP_SEE_OTHER);
    }

    
}
