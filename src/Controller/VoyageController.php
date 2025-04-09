<?php

namespace App\Controller;

use App\Entity\Voyage;
use App\Form\VoyageType;
use App\Repository\VoyageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Security;




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
    public function new(Request $request, EntityManagerInterface $entityManager , Security $security): Response
    {
        $voyage = new Voyage();
        $form = $this->createForm(VoyageType::class, $voyage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            

            $user = $security->getUser(); // Ou $this->getUser() si tu es dans un AbstractController
            $voyage->setUser($user);
            $entityManager->persist($voyage);
            $entityManager->flush();

            return $this->redirectToRoute('app_voyage_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('/back/manager/voyage/new.html.twig', [
            'voyage' => $voyage,
            'form' => $form,
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
        $form = $this->createForm(VoyageType::class, $voyage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_voyage_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('/back/manager/voyage/edit.html.twig', [
            'voyage' => $voyage,
            'form' => $form,
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
