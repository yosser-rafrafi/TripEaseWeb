<?php

namespace App\Controller;

use App\Controller\VoyageController;
use App\Entity\Voyage;
use App\Entity\Mission;
use App\Form\MissionType;
use App\Repository\MissionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/mission')]
final class MissionController extends AbstractController{
    #[Route(name: 'app_mission_index', methods: ['GET'])]
    public function index(MissionRepository $missionRepository): Response
    {
        return $this->render('back/manager/mission/index.html.twig', [
            'missions' => $missionRepository->findAll(),
        ]);
    }




    #[Route('/new/{voyageId}', name: 'app_mission_new')]
    public function new(Request $request, EntityManagerInterface $entityManager , int $voyageId): Response
    {
        $voyage = $entityManager->getRepository(Voyage::class)->find($voyageId);
    
    if (!$voyage) {
        throw $this->createNotFoundException('Voyage non trouvé.');
    }
       
        $mission = new Mission();
    $mission->setVoyage($voyage); // Associe la mission au voyage

    $form = $this->createForm(MissionType::class, $mission);
    $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($mission);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_voyage_show', ['id' => $voyage->getId()]);
        }

        return $this->render('back/manager/mission/new.html.twig', [
            'mission' => $mission,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_mission_show', methods: ['GET'])]
    public function show(Mission $mission): Response
    {
        return $this->render('back/manager/mission/show.html.twig', [
            'mission' => $mission,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_mission_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Mission $mission, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MissionType::class, $mission);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_mission_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('back/manager/mission/edit.html.twig', [
            'mission' => $mission,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_mission_delete', methods: ['POST'])]
    public function delete(Request $request, Mission $mission, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mission->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($mission);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_mission_index', [], Response::HTTP_SEE_OTHER);
    }
}
