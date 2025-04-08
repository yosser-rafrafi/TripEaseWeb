<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Controller\StatutController;
use App\Entity\Statut;
use App\Form\CommentaireType;
use App\Repository\CommentaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('forum/commentaire')]
final class CommentaireController extends AbstractController{
    #[Route(name: 'app_commentaire_index', methods: ['GET'])]
    public function index(CommentaireRepository $commentaireRepository): Response
    {
        return $this->render('forum/commentaire/index.html.twig', [
            'commentaires' => $commentaireRepository->findAll(),
            'layout' => true,
        ]);
    }

    #[Route('/new/{statutId}', name: 'app_commentaire_new')]
public function new(Request $request, EntityManagerInterface $entityManager, int $statutId): Response
{
    // Récupérer le Statut à partir de l'ID
    $statut = $entityManager->getRepository(Statut::class)->find($statutId);

    // Vérifier que le Statut existe
    if (!$statut) {
        throw $this->createNotFoundException('Le Statut n\'a pas été trouvé.');
    }

    // Créer un nouveau commentaire et l'associer au Statut
    $commentaire = new Commentaire();
    $commentaire->setStatut($statut); // Associe le commentaire au statut

    // Créer et gérer le formulaire
    $form = $this->createForm(CommentaireType::class, $commentaire);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Persist le commentaire
        $entityManager->persist($commentaire);
        $entityManager->flush();

        // Redirige vers la page de détail du Statut
        return $this->redirectToRoute('app_statut_index', ['id' => $statut->getId()]);
    }

    // Afficher le formulaire
    return $this->render('forum/commentaire/new.html.twig', [
        'form' => $form->createView(),
        'statut' => $statut,  // Passer le Statut à la vue
    ]);
}

    #[Route('/{id}', name: 'app_commentaire_show', methods: ['GET'])]
    public function show(Commentaire $commentaire): Response
    {
        return $this->render('forum/commentaire/show.html.twig', [
            'commentaire' => $commentaire,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_commentaire_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Commentaire $commentaire, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_statut_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('forum/commentaire/edit.html.twig', [
            'commentaire' => $commentaire,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_commentaire_delete', methods: ['POST'])]
    public function delete(Request $request, Commentaire $commentaire, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commentaire->getId(), $request->get('_token'))) {
            $entityManager->remove($commentaire);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_statut_index', [], Response::HTTP_SEE_OTHER);
    }
}