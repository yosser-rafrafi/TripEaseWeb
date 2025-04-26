<?php

namespace App\Controller;

use App\Entity\Voyage;
use App\Entity\Flight;
use App\Service\AviationStackService;
use Symfony\Component\Form\FormError;
use App\Form\VoyageType;
use App\Repository\VoyageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Security;
use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;





#[Route('/voyage')]
final class VoyageController extends AbstractController{
    #[Route(name: 'app_voyage_index', methods: ['GET'])]
    public function index(VoyageRepository $voyageRepository): Response
    {
        // Récupère l'utilisateur connecté
        $user = $this->getUser();

        // Récupère uniquement les voyages de cet utilisateur
        $voyages = $voyageRepository->findBy(['user' => $user]);

        return $this->render('back/manager/voyage/index.html.twig', [
            'voyages' => $voyages,
        ]);
        
    }

    #[Route('/new', name: 'app_voyage_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager, Security $security, AviationStackService $aviationStackService): Response
{
    $voyage = new Voyage();
    $form = $this->createForm(VoyageType::class, $voyage);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $user = $security->getUser();
        $voyage->setUser($user);

        $flightNumber = $voyage->getNumeroVol(); // change si ton getter a un nom différent

        // Vérification si vol rempli
        if ($flightNumber) {
            $flightData = $aviationStackService->getFlightData($flightNumber);

            if ($flightData === null) {
                $this->addFlash('warning', 'Numéro de vol invalide ou introuvable.');
                return $this->render('/back/manager/voyage/new.html.twig', [
                    'voyage' => $voyage,
                    'form' => $form->createView(),
                    'selected_users' => [],
                ]);
            }

            // Création d'une entité Flight
            $flight = new Flight();
            $flight
                ->setFlight_number($flightData['flight']['iata'] ?? $flightNumber)
                ->setAirline($flightData['airline']['name'] ?? 'Inconnue')
                ->setDepartureAirport($flightData['departure']['airport'] ?? 'Inconnu')
                ->setArrivalAirport($flightData['arrival']['airport'] ?? 'Inconnu')
                ->setDepartureDatetime(new \DateTime($flightData['departure']['scheduled'] ?? 'now'))
                ->setArrivalDatetime(new \DateTime($flightData['arrival']['scheduled'] ?? 'now'))
                ->setVoyage($voyage);
            $voyage->setNumeroVol($flightData['flight']['iata'] ?? $flightNumber);

            $entityManager->persist($flight);
        }

        // Gestion des utilisateurs sélectionnés
        $selectedUserIds = explode(',', $request->request->get('selected_users', ''));
        foreach ($selectedUserIds as $userId) {
            if ($userId) {
                $userToAdd = $entityManager->getRepository(User::class)->find($userId);
                if ($userToAdd) {
                    $voyage->addUser($userToAdd);
                }
            }
        }

        $entityManager->persist($voyage);
        $entityManager->flush();

        return $this->redirectToRoute('app_voyage_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->render('/back/manager/voyage/new.html.twig', [
        'voyage' => $voyage,
        'form' => $form->createView(),
        'selected_users' => [],
    ]);
}
    #[Route('/{id}', name: 'app_voyage_show', methods: ['GET'])]
    public function show(Voyage $voyage): Response
    {
        return $this->render('/back/manager/voyage/show.html.twig', [
            'voyage' => $voyage,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_voyage_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Voyage $voyage, EntityManagerInterface $entityManager): Response
    {
        // Récupère les utilisateurs déjà associés au voyage
        $originalUsers = new ArrayCollection();
        foreach ($voyage->getUsers() as $user) {
            $originalUsers->add($user);
        }
    
        $form = $this->createForm(VoyageType::class, $voyage);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Gestion des utilisateurs sélectionnés
            $selectedUserIds = explode(',', $request->request->get('selected_users', ''));
            
            // Supprime les utilisateurs qui ont été enlevés
            foreach ($originalUsers as $user) {
                if (!in_array($user->getId(), $selectedUserIds)) {
                    $voyage->removeUser($user);
                }
            }
            
            // Ajoute les nouveaux utilisateurs
            foreach ($selectedUserIds as $userId) {
                if ($userId && !$voyage->getUsers()->contains($entityManager->getReference(User::class, $userId))) {
                    $userToAdd = $entityManager->getRepository(User::class)->find($userId);
                    if ($userToAdd) {
                        $voyage->addUser($userToAdd);
                    }
                }
            }
    
            $entityManager->flush();
    
            return $this->redirectToRoute('app_voyage_index', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->render('/back/manager/voyage/edit.html.twig', [
            'voyage' => $voyage,
            'form' => $form->createView(),
            'selected_users' => $voyage->getUsers()->map(fn($user) => $user->getId())->toArray()
        ]);
    }

   
    #[Route('/{id}', name: 'app_voyage_delete', methods: ['POST'])]
    public function delete(Request $request, Voyage $voyage, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$voyage->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($voyage);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_voyage_index', [], Response::HTTP_SEE_OTHER);
    }
}
