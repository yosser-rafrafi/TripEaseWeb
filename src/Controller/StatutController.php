<?php

namespace App\Controller;

use App\Entity\Statut;
use App\Form\StatutType;
use App\Repository\StatutRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('forum/statut')]
final class StatutController extends AbstractController{
    #[Route(name: 'app_statut_index', methods: ['GET'])]
    public function index(StatutRepository $statutRepository): Response
    {
        $isAdmin = $this->isGranted('ROLE_ADMIN') || $this->isGranted('ROLE_MANAGER');
        $template = !$isAdmin 
            ? 'front/employee_home/index.html.twig' 
            : 'back/base.html.twig';
        $isEmployee = !$isAdmin;
        return $this->render("/forum/statut/index.html.twig", [
            'statuts' => $statutRepository->findAll(),
            'base_template' => $template,
            'is_employee' => $isEmployee
        ]);
    }

    #[Route('/new', name: 'app_statut_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $statut = new Statut();
        $form = $this->createForm(StatutType::class, $statut);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($statut);
            $entityManager->flush();

            return $this->redirectToRoute('app_statut_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('forum/statut/new.html.twig', [
            'statut' => $statut,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_statut_show', methods: ['GET'])]
    public function show(Statut $statut): Response
    {
        return $this->render('forum/statut/show.html.twig', [
            'statut' => $statut,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_statut_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Statut $statut, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(StatutType::class, $statut);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_statut_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('forum/statut/edit.html.twig', [
            'statut' => $statut,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_statut_delete', methods: ['POST'])]
        public function delete(Request $request, Statut $statut, EntityManagerInterface $entityManager): Response
        {
            if ($this->isCsrfTokenValid('delete'.$statut->getId(), $request->request->get('_token'))) {
                try {
                    $entityManager->remove($statut);
                    $entityManager->flush();
                    $this->addFlash('success', 'Statut deleted successfully.');
                } catch (\Exception $e) {
                    $this->addFlash('error', 'Error deleting statut.');
                }
            }
            
        return $this->redirectToRoute('app_statut_index', [], Response::HTTP_SEE_OTHER);
    }
}
