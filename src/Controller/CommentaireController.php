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
use App\Entity\User; // Assuming User entity exists for comment author
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException; // For checking user login

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

    #[Route('/new/{statutId}', name: 'app_commentaire_new', methods: ['GET', 'POST'])] // Allow POST
public function new(Request $request, EntityManagerInterface $entityManager, CsrfTokenManagerInterface $csrfTokenManager, int $statutId): Response
{
    // Récupérer le Statut à partir de l'ID
    $statut = $entityManager->getRepository(Statut::class)->find($statutId);

    // Vérifier que le Statut existe
    if (!$statut) {
        $this->addFlash('error', 'Statut not found.');
        return $this->redirectToRoute('app_statut_index');
        // Alternatively: throw $this->createNotFoundException('Le Statut n\'a pas été trouvé.');
    }

    // Handle POST request from the index page form
    if ($request->isMethod('POST')) {
        $contenu = $request->request->get('contenu');
        $token = $request->request->get('_token');
        $csrfTokenId = 'comment-add' . $statut->getId(); // Matches the token ID in the twig template

        // 1. Validate CSRF Token
        if (!$this->isCsrfTokenValid($csrfTokenId, $token)) {
             $this->addFlash('error', 'Invalid security token. Please try again.');
             return $this->redirectToRoute('app_statut_index');
        }

        // 2. Check if user is logged in (adjust based on your User entity and security setup)
        $user = $this->getUser();
        if (!$user) {
             // Or redirect to login: return $this->redirectToRoute('app_login');
             throw new AccessDeniedException('You must be logged in to comment.');
        }

        // 3. Validate content
        if (empty(trim($contenu))) {
            $this->addFlash('warning', 'Comment cannot be empty.');
             // Redirect back even if empty to show the flash message
             return $this->redirectToRoute('app_statut_index');
        } else {
            // 4. Create comment
            $commentaire = new Commentaire();
            $commentaire->setContenu($contenu);
            $commentaire->setStatut($statut);
            $commentaire->setDatePublication(new \DateTimeImmutable()); // Use correct setter
            $commentaire->setUser($user); // Use the correct setter and user object

            // 4a. Check for and set Parent Comment ID if submitted from reply form
            $parentCommentId = $request->request->get('parentCommentId');
            if ($parentCommentId) {
                $parentComment = $entityManager->getRepository(Commentaire::class)->find($parentCommentId);
                if ($parentComment) {
                    // Ensure the parent comment belongs to the same status? (Optional check)
                    // if ($parentComment->getStatut() === $statut) {
                         $commentaire->setCommentaireParentId($parentComment->getId());
                    // } else {
                         // Handle error: trying to reply across different statuses?
                    // }
                } else {
                    // Handle error: parent comment not found?
                    $this->addFlash('error', 'Parent comment not found.');
                    // Decide whether to proceed without parent or stop
                }
            }

            // 5. Persist comment
            $entityManager->persist($commentaire);
            $entityManager->flush();

            $this->addFlash('success', 'Comment posted successfully!');
        }

        // 5. Redirect back to the status list after processing POST
        return $this->redirectToRoute('app_statut_index');
    }

    // --- GET Request Logic (or fallback if POST had issues and didn't redirect) ---
    // This part renders the separate 'new comment' page, which might not be needed
    // if all comments are added via the form on the index page.
    // You could potentially remove this or keep it for a dedicated "add comment" page.

    $commentaire = new Commentaire(); // Create a fresh instance for the form
    $commentaire->setStatut($statut);

    // Handle potential parent comment ID if passed via query for GET request
    $parentCommentId = $request->query->get('parentCommentId');
    if ($parentCommentId) {
        $parentComment = $entityManager->getRepository(Commentaire::class)->find($parentCommentId);
        if ($parentComment) {
            $commentaire->setCommentaireParentId($parentComment->getId());
        }
    }

    $form = $this->createForm(CommentaireType::class, $commentaire);

    // Render the separate form page (for GET requests to this route)
    return $this->render('forum/commentaire/new.html.twig', [
        'form' => $form->createView(),
        'statut' => $statut,
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
        // Authorization Check
        $user = $this->getUser();
        if (!$user || ($commentaire->getUser() !== $user && !$this->isGranted('ROLE_MANAGER') && !$this->isGranted('ROLE_ADMIN'))) {
            throw new AccessDeniedException('You are not allowed to edit this comment.');
        }
    
        if ($request->isMethod('POST')) {
            echo "token !!!!!!";
            echo $request->request->get('_token');
            if ($this->isCsrfTokenValid('edit'.$commentaire->getId(), $request->request->get('_token'))) {
                $newContent = $request->request->get('contenu');
                
                if (!empty(trim($newContent))) {
                    $commentaire->setContenu($newContent);
                    $entityManager->flush();
                    $this->addFlash('success', 'Comment updated successfully!');
                } else {
                    $this->addFlash('error', 'Comment content cannot be empty.');
                }
            } else {
                $this->addFlash('error', 'Invalid security token.');
            }
        }
    
        return $this->redirectToRoute('app_statut_index');
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

    #[Route('/reply/{statutId}/{commentaireId}', name: 'app_commentaire_reply')]
public function reply(Request $request, EntityManagerInterface $entityManager, int $statutId, int $commentaireId): Response
{
    // Récupérer le Statut et le commentaire parent à partir de l'ID
    $statut = $entityManager->getRepository(Statut::class)->find($statutId);
    $parentCommentaire = $entityManager->getRepository(Commentaire::class)->find($commentaireId);

    if (!$statut || !$parentCommentaire) {
        throw $this->createNotFoundException('Le Statut ou le commentaire n\'a pas été trouvé.');
    }

    // Créer un nouveau commentaire et l'associer au Statut et au commentaire parent
    $commentaire = new Commentaire();
    $commentaire->setStatut($statut);
    $commentaire->setCommentaireParentId($parentCommentaire->getId()); // Lien avec le parent

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
        'statut' => $statut,
    ]);
}
}
