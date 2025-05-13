<?php

namespace App\Controller;

use App\Entity\Chambre;
use App\Form\ChambreType;
use App\Repository\ChambreRepository;
use App\Repository\HotelRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route('/chambre')]
final class ChambreController extends AbstractController
{
    #[Route(name: 'app_chambre_index', methods: ['GET'])]
    public function index(ChambreRepository $chambreRepository): Response
    {
        return $this->render('chambre/index.html.twig', [
            'chambres' => $chambreRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_chambre_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $chambre = new Chambre();
        $form = $this->createForm(ChambreType::class, $chambre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($chambre);
            $entityManager->flush();

            return $this->redirectToRoute('app_chambre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('chambre/new.html.twig', [
            'chambre' => $chambre,
            'form' => $form,
        ]);
    }

    #[Route('/{id_chambre}', name: 'app_chambre_show', methods: ['GET'])]
    public function show(Chambre $chambre): Response
    {
        return $this->render('chambre/show.html.twig', [
            'chambre' => $chambre,
        ]);
    }

    #[Route('/{id_chambre}/edit', name: 'app_chambre_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Chambre $chambre, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ChambreType::class, $chambre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_chambre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('chambre/edit.html.twig', [
            'chambre' => $chambre,
            'form' => $form,
        ]);
    }

    #[Route('/{id_chambre}', name: 'app_chambre_delete', methods: ['POST'])]
    public function delete(Request $request, Chambre $chambre, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$chambre->getId_chambre(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($chambre);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_chambre_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/api/hotel/{id}/chambres-disponibles', name: 'api_hotel_chambres_disponibles', methods: ['GET'])]
public function getChambresDisponibles(
    int $id,
    Request $request,
    HotelRepository $hotelRepository,
    ChambreRepository $chambreRepository
): JsonResponse {
    // Récupérer les paramètres de la requête (dates)
    $dateDebut = new \DateTime($request->query->get('date_debut'));
    $dateFin = new \DateTime($request->query->get('date_fin'));

    // Vérifier si l'hôtel existe
    $hotel = $hotelRepository->find($id);
    if (!$hotel) {
        return new JsonResponse(['error' => 'Hôtel non trouvé'], 404);
    }

    // Récupérer les chambres disponibles pour cet hôtel
    $chambresDisponibles = $chambreRepository->findAvailableRooms($dateDebut, $dateFin, $id);

    // Formater les données pour la réponse JSON
    $data = [];
    foreach ($chambresDisponibles as $chambre) {
        $data[] = [
            'id' => $chambre->getId_chambre(),
            'type' => $chambre->getTypeChambre(),
            'prix_par_nuit' => $chambre->getPrixParNuit(),
        ];
    }

    return new JsonResponse($data);
}
}
