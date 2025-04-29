<?php
namespace App\Controller;

use App\Entity\Transport;
use App\Entity\Reservationtransport;
use App\Entity\Notification;
use App\Form\ReservationtransportType;
use App\Repository\ReservationtransportRepository;
use App\Repository\NotificationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;

#[Route('/employee/reservationtransport')]
final class ReservationtransportController extends AbstractController
{
    private NotificationRepository $notificationRepository;

    public function __construct(NotificationRepository $notificationRepository)
    {
        $this->notificationRepository = $notificationRepository;
    }

    // Helper method to get notifications for the logged-in user
    private function getNotificationsForUser(): array
    {
        $user = $this->getUser();
        return $this->notificationRepository->findRecentByEmploye($user, 10);
    }

    #[Route('/reservation', name: 'app_reservationtransport_index', methods: ['GET'])]
    public function index(Request $request, ReservationtransportRepository $reservationtransportRepository): Response
    {
        // Get the logged-in user (employe)
        $user = $this->getUser();

        // Fetch reservations for the logged-in user
        $reservations = $reservationtransportRepository->findBy(['employe' => $user]);

        // Fetch recent notifications
        $notifications = $this->getNotificationsForUser();

        // Check if success query parameter is set to show success message
        $successMessage = $request->query->get('success') ? 'Votre réservation a été ajoutée avec succès !' : null;

        // Pass both notifications and other variables to the template
        return $this->render('front/reservationtransport/index.html.twig', [
            'reservationtransports' => $reservations,
            'successMessage' => $successMessage, // Pass success message to the template
            'notifications' => $notifications,  // Pass notifications to the template
        ]);
    }

    #[Route('/', name: 'app_transport_list', methods: ['GET'])]
    public function transportList(EntityManagerInterface $entityManager): Response
    {
        // Get the logged-in user (employe)
        $user = $this->getUser();

        // Fetch transports
        $transports = $entityManager->getRepository(Transport::class)->findAll();

        // Fetch notifications for the logged-in user
        $notifications = $this->getNotificationsForUser();

        return $this->render('front/reservationtransport/transport_list.html.twig', [
            'transports' => $transports, 
            'notifications' => $notifications,  // Pass notifications to the template
        ]);
    }

    #[Route('/new/{transport_id}', name: 'app_reservationtransport_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, $transport_id, HubInterface $hub): Response
    {
        // Fetch the transport using the provided ID
        $transport = $entityManager->getRepository(Transport::class)->find($transport_id);

        if (!$transport) {
            throw $this->createNotFoundException('No transport found for id ' . $transport_id);
        }

        // Create new Reservation
        $reservationtransport = new Reservationtransport();
        $reservationtransport->setStatusDePaiement('non payé');
        $reservationtransport->setTransport($transport);

        // Get the currently logged-in user (employe)
        $user = $this->getUser();

        if ($user instanceof User) {
            $reservationtransport->setEmploye($user);
        } else {
            throw $this->createAccessDeniedException('You must be logged in to make a reservation.');
        }

        // Create form and handle request
        $form = $this->createForm(ReservationtransportType::class, $reservationtransport);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Persist the reservation to the database
            $entityManager->persist($reservationtransport);
            $entityManager->flush();

            // Create the notification for the user
            $notification = new Notification();
            $notification->setEmploye($user);
            $notification->setMessage('Votre réservation pour ' . $transport->getTransportName() . ' a été confirmée.');
            $notification->setCreatedAt(new \DateTimeImmutable());

            // Persist the notification
            $entityManager->persist($notification);
            $entityManager->flush();

            // Publish the notification via Mercure
            $update = new Update(
                sprintf('/notifications/%d', $user->getId()), // Targeting the user topic
                json_encode([
                    'message' => $notification->getMessage(),
                    'createdAt' => $notification->getCreatedAt()->format('Y-m-d H:i:s')
                ])
            );

            // Send the update to the Mercure hub
            $hub->publish($update);

            // Redirect with success message
            return $this->redirectToRoute('app_reservationtransport_index', [
                'success' => true,
            ]);
        }

        // Pass necessary data to the template
        $transport_name = $transport->getTransportName();

        return $this->render('front/reservationtransport/new.html.twig', [
            'reservationtransport' => $reservationtransport,
            'form' => $form->createView(),
            'transport_name' => $transport_name,
            'notifications' => $this->getNotificationsForUser(), // Pass notifications to the template
        ]);
    }

    #[Route('/show/{id}', name: 'app_reservationtransport_show', methods: ['GET'])]
    public function show(Reservationtransport $reservationtransport): Response
    {
        return $this->render('front/reservationtransport/show.html.twig', [
            'reservationtransport' => $reservationtransport,
            'notifications' => $this->getNotificationsForUser(), // Pass notifications to the template
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
            'notifications' => $this->getNotificationsForUser(), // Pass notifications to the template
            'transport_name' => $reservationtransport->getTransport()->getTransportName()
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

    #[Route('/payment/success/{id}', name: 'app_reservationtransport_payment_success')]
    public function markAsPaid(
        Reservationtransport $reservationtransport,
        EntityManagerInterface $em
    ): Response {
        // Set status to "payé"
        $reservationtransport->setStatusDePaiement('payé');
        $em->flush();
    
        return $this->render('front/reservationtransport/payment_success.html.twig', [
            'reservationtransport' => $reservationtransport,
            'notifications' => $this->getNotificationsForUser(),
            'message' => 'Votre paiement a été confirmé ! ✅',
        ]);
        
    }

    #[Route('/payment/cancel/{id}', name: 'app_reservationtransport_payment_cancel')]
    public function paymentCancelled(
        Reservationtransport $reservationtransport
    ): Response {
        return $this->render('front/reservationtransport/payment_cancel.html.twig', [
            'reservationtransport' => $reservationtransport,
            'notifications' => $this->getNotificationsForUser(),
            'message' => 'Le paiement a été annulé. ❌',
        ]);
        
    }
    
}
