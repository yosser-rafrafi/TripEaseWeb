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
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

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
            'statuts' => $statutRepository->findAllOrderedByDateDesc(),
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
            $statut->setDateCreation(new \DateTime());
            $statut->setTypeContenu('texte');
    
            // Set anonymous flag based on the form input
            $isAnonymous = $request->request->get('anonymous') === 'on';
            $statut->setAnonymous($isAnonymous);
            $statut->setUser($this->getUser()); // Always set user, anonymous or not
    
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
        // Check if user has permission to edit - only the owner can edit
        if ($this->getUser()->getUserIdentifier() !== $statut->getUser()->getUserIdentifier()) {
            throw new AccessDeniedException('You do not have permission to edit this post.');
        }
    
        // Handle AJAX request
        if ($request->isMethod('POST')) {
            try {
                $content = $request->request->get('contenu');
                $mediaFile = $request->files->get('media');
                
                if ($content) {
                    $statut->setContenu($content);
                }
    
                if ($mediaFile) {
                    $mediaUrl = $this->cloudinaryService->upload($mediaFile);
                    $statut->setMediaUrl($mediaUrl);
                    $statut->setTypeContenu($mediaFile->getMimeType() === 'video/mp4' ? 'video' : 'image');
                }
    
                $entityManager->flush();
    
                return new JsonResponse([
                    'success' => true,
                    'contenu' => $statut->getContenu(),
                    'mediaUrl' => $statut->getMediaUrl(),
                    'typeContenu' => $statut->getTypeContenu()
                ]);
            } catch (\Exception $e) {
                return new JsonResponse([
                    'success' => false,
                    'message' => $e->getMessage()
                ], Response::HTTP_BAD_REQUEST);
            }
        }
    
        // Handle regular form submission
        $form = $this->createForm(StatutType::class, $statut);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Post updated successfully!');
            return $this->redirectToRoute('app_statut_index', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->render('forum/statut/edit.html.twig', [
            'statut' => $statut,
            'form' => $form,
        ]);
    }
    #[Route('/{id}/remove-media', name: 'app_statut_remove_media', methods: ['POST'])]
public function removeMedia(Request $request, Statut $statut, EntityManagerInterface $entityManager): JsonResponse
{
    // Check if user has permission to edit
    if (!$this->isGranted('ROLE_ADMIN') && 
        !$this->isGranted('ROLE_MANAGER') && 
        $this->getUser()->getUserIdentifier() !== $statut->getUser()->getUserIdentifier()) {
        return new JsonResponse([
            'success' => false,
            'message' => 'You do not have permission to edit this post.'
        ], Response::HTTP_FORBIDDEN);
    }

    try {
        $statut->setMediaUrl(null);
        $statut->setTypeContenu('texte');
        $entityManager->flush();

        return new JsonResponse([
            'success' => true
        ]);
    } catch (\Exception $e) {
        return new JsonResponse([
            'success' => false,
            'message' => $e->getMessage()
        ], Response::HTTP_BAD_REQUEST);
    }
}
#[Route('/voice-to-text', name: 'app_voice_to_text', methods: ['GET'])]
public function voiceToText(): Response
{
    return $this->render('forum/statut/_voice_modal.html.twig');
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

    #[Route('/statut/{id}/like', name: 'statut_like', methods: ['POST'])]
    public function like(Request $request, Statut $statut, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $existingReaction = $em->getRepository(Reactions::class)->findOneBy([
            'user' => $user,
            'statut' => $statut,
        ]);
    
        if ($existingReaction) {
            if ($existingReaction->getType() === 'LIKE') {
                $em->remove($existingReaction);
                $userReaction = null;
            } else {
                $existingReaction->setType('LIKE');
                $em->persist($existingReaction);
                $userReaction = 'LIKE';
            }
        } else {
            $reaction = new Reactions();
            $reaction->setUser($user)
                    ->setStatut($statut)
                    ->setType('LIKE')
                    ->setCreationDate(new \DateTime());
            $em->persist($reaction);
            $userReaction = 'LIKE';
        }
        
        $em->flush();
    
        if ($request->isXmlHttpRequest()) {
            return $this->json([
                'success' => true,
                'likeCount' => count($statut->getReactions()->filter(fn($r) => $r->getType() === 'LIKE')),
                'dislikeCount' => count($statut->getReactions()->filter(fn($r) => $r->getType() === 'DISLIKE')),
                'userReaction' => $userReaction
            ]);
        }
    
        return $this->redirectToRoute('app_statut_index');
    }

    #[Route('/statut/{id}/dislike', name: 'statut_dislike', methods: ['POST'])]
    public function dislike(Request $request, Statut $statut, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $existingReaction = $em->getRepository(Reactions::class)->findOneBy([
            'user' => $user,
            'statut' => $statut,
        ]);
    
        if ($existingReaction) {
            if ($existingReaction->getType() === 'DISLIKE') {
                $em->remove($existingReaction);
                $userReaction = null;
            } else {
                $existingReaction->setType('DISLIKE');
                $em->persist($existingReaction);
                $userReaction = 'DISLIKE';
            }
        } else {
            $reaction = new Reactions();
            $reaction->setUser($user)
                    ->setStatut($statut)
                        ->setType('DISLIKE')
                    ->setCreationDate(new \DateTime());
            $em->persist($reaction);
            $userReaction = 'DISLIKE';
        }
        
        $em->flush();
    
        if ($request->isXmlHttpRequest()) {
            return $this->json([
                'success' => true,
                'likeCount' => count($statut->getReactions()->filter(fn($r) => $r->getType() === 'LIKE')),
                'dislikeCount' => count($statut->getReactions()->filter(fn($r) => $r->getType() === 'DISLIKE')),
                'userReaction' => $userReaction
            ]);
        }
    
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
