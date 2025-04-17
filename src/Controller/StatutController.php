<?php

namespace App\Controller;

use App\Entity\Reactions;
use App\Entity\Statut;
use App\Form\StatutType;
use App\Repository\StatutRepository;
use App\Service\CloudinaryService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('forum/statut')]
final class StatutController extends AbstractController{
    public function __construct(
        private readonly CloudinaryService $cloudinaryService
    ) {
    }
    
    #[Route(name: 'app_statut_index', methods: ['GET'])]
    public function index(StatutRepository $statutRepository): Response
    {
        $isAdmin = $this->isGranted('ROLE_ADMIN') || $this->isGranted('ROLE_MANAGER');
        $template = !$isAdmin 
            ? 'front/employee_home/index.html.twig' 
            : 'back/base.html.twig';
        $isEmployee = !$isAdmin;

        // Get the current user
        $currentUser = $this->getUser();

        return $this->render("/forum/statut/index.html.twig", [
            'statuts' => $statutRepository->findAll(),
            'base_template' => $template,
            'is_employee' => $isEmployee,
            'current_user' => $currentUser 
        ]);
    }

    #[Route('/new', name: 'app_statut_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator): Response
{
    $statut = new Statut();

    if ($request->isMethod('POST')) {
        $content = $request->request->get('contenu');
        $mediaFile = $request->files->get('media');

        $statut->setContenu($content);
        $statut->setUser($this->getUser());
        $statut->setDateCreation(new \DateTime());
        $statut->setTypeContenu('texte');

        if ($mediaFile) {
            try {
                $mediaUrl = $this->cloudinaryService->upload($mediaFile);
                $statut->setMediaUrl($mediaUrl);
                $statut->setTypeContenu($mediaFile->getMimeType() === 'video/mp4' ? 'video' : 'image');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Erreur lors de l\'upload : ' . $e->getMessage());
            }
        }

        // Valider les contraintes via ValidatorInterface
        $errors = $validator->validate($statut);

        if (count($errors) > 0) {
            // Parcourir les erreurs et les afficher
            foreach ($errors as $error) {
                $this->addFlash('warning', $error->getMessage());
            }

            return $this->redirectToRoute('app_statut_index');
        }

        $entityManager->persist($statut);
        $entityManager->flush();

        $this->addFlash('success', 'Post créé avec succès !');
        return $this->redirectToRoute('app_statut_index');
    }

    return $this->render('statut/new.html.twig', [
        'statut' => $statut,
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

    #[Route('/statut/{id}/like', name: 'statut_like')]
    public function like(Statut $statut, EntityManagerInterface $em): Response
    {
        $reaction = new Reactions();
        $reaction->setUser($this->getUser())
                 ->setStatut($statut)
                 ->setType('LIKE')
                 ->setCreationDate(new \DateTime());

        $em->persist($reaction);
        $em->flush();

        return $this->redirectToRoute('app_statut_index');
    }

    #[Route('/statut/{id}/dislike', name: 'statut_dislike')]
    public function dislike(Statut $statut, EntityManagerInterface $em): Response
    {
        $reaction = new Reactions();
        $reaction->setUser($this->getUser())
                 ->setStatut($statut)
                 ->setType('DISLIKE')
                 ->setCreationDate(new \DateTime());

        $em->persist($reaction);
        $em->flush();

        return $this->redirectToRoute('app_statut_index');
    }

    #[Route('/statut/{id}/favorite', name: 'statut_favorite')]
    public function favorite(Statut $statut, EntityManagerInterface $em): Response
    {
        // Logic to add to favorites (similar to like/dislike)
        // You might need to adjust your Reactions entity to handle favorites if necessary

        return $this->redirectToRoute('app_statut_index');
    }

    #[Route('/favorites', name: 'statut_favorites')]
    public function favorites(EntityManagerInterface $em): Response
    {
        $favorites = $em->getRepository(Reactions::class)->findBy([
            'user' => $this->getUser(),
            'type' => 'FAVORITE',
        ]);

        return $this->render('forum/statut/favorites.html.twig', [
            'favorites' => $favorites,
        ]);
    }
}
