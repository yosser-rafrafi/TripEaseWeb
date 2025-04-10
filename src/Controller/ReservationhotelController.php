<?php

namespace App\Controller;

use App\Entity\Reservationhotel;
use App\Entity\Hotel;
use App\Service\QrCodeService;
use App\Service\EmailService;
use App\Form\ReservationhotelType;
use App\Entity\User;
use App\Repository\ReservationhotelRepository;
use App\Repository\HotelRepository;
use Symfony\Component\Form\FormError;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/reservationhotel')]
final class ReservationhotelController extends AbstractController
{/*
    private QrCodeService $qrCodeService;
    private EmailService $emailService;

    public function __construct(QrCodeService $qrCodeService, EmailService $emailService)
    {
        $this->qrCodeService = $qrCodeService;
        $this->emailService = $emailService;
    }*/

    #[Route(name: 'app_reservationhotel_index', methods: ['GET'])]
    #[Route('/', name: 'app_reservationhotel_index', methods: ['GET'])]
    public function index(ReservationhotelRepository $reservationhotelRepository): Response
    {
        $user = $this->getUser();
        
        // Vérification si l'utilisateur est connecté
        if (!$user instanceof User) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour voir vos réservations');
        }
    
        return $this->render('front/reservationhotel/index.html.twig', [
            'reservationhotels' => $reservationhotelRepository->findByUser($user),
        ]);
    }
    #[Route('/new/{hotelId}', name: 'app_reservationhotel_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, $hotelId): Response
    {
        // 1. Validation initiale de l'hôtel
        $hotel = $entityManager->getRepository(Hotel::class)->find($hotelId);
        if (!$hotel) {
            $this->addFlash('error', 'Hôtel non trouvé');
            return $this->redirectToRoute('app_hotel_index');
        }
    
        $reservationhotel = new Reservationhotel();
        $reservationhotel->setHotel($hotel);
        $reservationhotel->setDateReservation(new \DateTime());
    
        $user = $this->getUser(); // This retrieves the logged-in user
    
        // Check if $user is an instance of User
        if ($user instanceof User) {
            $reservationhotel->setUser($user);  // Set the logged-in user
        } else {
            throw $this->createAccessDeniedException('You must be logged in to make a reservation.'); // Set to null if no user is logged in
        }
    
      
        $form = $this->createForm(ReservationhotelType::class, $reservationhotel, [
            'hotel' => $hotel,
        ]);
    
        $form->handleRequest($request);
    
        if ($form->isSubmitted()) {
            // 2. Validation des dates doit être faite AVANT isValid()
            $dateDebut = $reservationhotel->getDateDebut();
            $dateFin = $reservationhotel->getDateFin();
    
            if ($dateFin < $dateDebut) {
                $form->get('date_fin')->addError(new FormError('La date de fin doit être postérieure à la date de début'));
            }
    
            if ($form->isValid()) { // Cette vérification doit venir APRÈS vos validations custom
                try {
                    $entityManager->persist($reservationhotel);
                    $entityManager->flush();
                    $this->addFlash('success', 'Réservation créée avec succès');
                    return $this->redirectToRoute('app_reservationhotel_index');
                } catch (\Exception $e) {
                    $this->addFlash('error', 'Erreur lors de la création : ' . $e->getMessage());
                }
            }
            // Pas besoin de else ici, les erreurs sont déjà attachées au formulaire
        }
    
        return $this->render('front/reservationhotel/new.html.twig', [
            'reservationhotel' => $reservationhotel,
            'form' => $form->createView(),
            'hotel' => $hotel,
        ]);
    }




    #[Route('/show/{id_reservation}', name: 'app_reservationhotel_show', methods: ['GET'])]
    public function show(int $id_reservation, EntityManagerInterface $entityManager): Response
    {
        $reservationhotel = $entityManager->getRepository(Reservationhotel::class)->find($id_reservation);
    
        if (!$reservationhotel) {
            throw $this->createNotFoundException('Réservation non trouvée.');
        }
    
        return $this->render('front/reservationhotel/show.html.twig', [
            'reservationhotel' => $reservationhotel,
        ]);
    }

    #[Route('/{id_reservation}/edit', name: 'app_reservationhotel_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reservationhotel $reservationhotel, EntityManagerInterface $entityManager): Response
    {
        // On récupère l'hôtel lié à la réservation existante
        $hotel = $reservationhotel->getHotel();
    
        // On passe l'hôtel comme option au formulaire
        $form = $this->createForm(ReservationhotelType::class, $reservationhotel, [
            'hotel' => $hotel,
        ]);
        
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
    
            return $this->redirectToRoute('app_reservationhotel_index', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->render('front/reservationhotel/edit.html.twig', [
            'reservationhotel' => $reservationhotel,
            'form' => $form,
            'button_label' => 'Mettre à jour'  // Définition de la variable button_label

        ]);
    }
    

    #[Route('/{id_reservation}', name: 'app_reservationhotel_delete', methods: ['POST'])]
    public function delete(Request $request, Reservationhotel $reservationhotel, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $reservationhotel->getId_reservation(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($reservationhotel);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reservationhotel_index', [], Response::HTTP_SEE_OTHER);
    }


    #[Route('/hotellist', name: 'app_hotel_list', methods: ['GET'])]
    public function hotelList(HotelRepository $hotelRepository): Response
    {
        return $this->render('front/reservationhotel/listhotel.html.twig', [
            'hotels' => $hotelRepository->findAll(),
        ]);
    }



/*
    #[Route('/reservation/qr-code/{id}', name: 'app_reservation_qr_code')]
    public function generateQrCode(Reservationhotel $reservationhotel): Response
    {
        // Détails de la réservation à encoder dans le QR code
        $details = sprintf(
            "Réservation #%d\nHôtel : %s\nChambre : %s\nDate de début : %s\nDate de fin : %s",
            $reservationhotel->getId_reservation(),
            $reservationhotel->getHotel()->getNom(),
            $reservationhotel->getChambre()->getTypeChambre(),
            $reservationhotel->getDateDebut()->format('Y-m-d'),
            $reservationhotel->getDateFin()->format('Y-m-d')
        );

        // Génère le QR code et retourne le chemin du fichier
        $qrCodeFile = $this->qrCodeService->generateQrCode($details);

        // Pour tester : retourne l'image dans le navigateur
        return new Response(file_get_contents($qrCodeFile), 200, ['Content-Type' => 'image/png']);
    }
/*
    #[Route('/reservation/send-email/{id}', name: 'app_reservation_send_email')]
    public function sendEmail(Reservationhotel $reservationhotel): RedirectResponse
    {
        // Utilise le service EmailService pour envoyer l'e-mail
        $this->emailService->sendEmailWithQrCode($reservationhotel);

        // Affiche un message de confirmation à l'utilisateur
        $this->addFlash('success', 'L\'e-mail avec le QR code a été envoyé avec succès !');

        // Redirige vers la liste des réservations
        return $this->redirectToRoute('app_reservationhotel_index');
    }*/

}