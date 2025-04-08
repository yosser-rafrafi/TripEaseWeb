<?php

namespace App\Controller;

use App\Entity\Commentaire;
<<<<<<< HEAD
=======
use App\Controller\StatutController;
use App\Entity\Statut;
>>>>>>> 09b8388c89382e4ec195998d936bfb04cb5d37ed
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

<<<<<<< HEAD
    #[Route('/new', name: 'app_commentaire_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $commentaire = new Commentaire();
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($commentaire);
            $entityManager->flush();

            return $this->redirectToRoute('app_commentaire_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('forum/commentaire/new.html.twig', [
            'commentaire' => $commentaire,
            'form' => $form,
        ]);
    }

=======
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

>>>>>>> 09b8388c89382e4ec195998d936bfb04cb5d37ed
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

<<<<<<< HEAD
            return $this->redirectToRoute('app_commentaire_index', [], Response::HTTP_SEE_OTHER);
=======
            return $this->redirectToRoute('app_statut_index', [], Response::HTTP_SEE_OTHER);
>>>>>>> 09b8388c89382e4ec195998d936bfb04cb5d37ed
        }

        return $this->render('forum/commentaire/edit.html.twig', [
            'commentaire' => $commentaire,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_commentaire_delete', methods: ['POST'])]
    public function delete(Request $request, Commentaire $commentaire, EntityManagerInterface $entityManager): Response
    {
<<<<<<< HEAD
        if ($this->isCsrfTokenValid('delete'.$commentaire->getId(), $request->getPayload()->getString('_token'))) {
=======
        if ($this->isCsrfTokenValid('delete'.$commentaire->getId(), $request->get('_token'))) {
>>>>>>> 09b8388c89382e4ec195998d936bfb04cb5d37ed
            $entityManager->remove($commentaire);
            $entityManager->flush();
        }

<<<<<<< HEAD
        return $this->redirectToRoute('app_commentaire_index', [], Response::HTTP_SEE_OTHER);
=======
        return $this->redirectToRoute('app_statut_index', [], Response::HTTP_SEE_OTHER);
>>>>>>> 09b8388c89382e4ec195998d936bfb04cb5d37ed
    }
}
