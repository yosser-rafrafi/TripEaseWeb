<?php

namespace App\Controller;

use App\Entity\AvanceFrai;
use App\Entity\User;
use App\Form\AvanceFraiType;
use App\Repository\AvanceFraiRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Security; 

#[Route('/avance/frais', name: 'app_avance_frai_')]
class AvanceFraiController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(AvanceFraiRepository $avanceFraiRepository, Security $security): Response
    {
        
        $user = $security->getUser();
    /** @var \App\Entity\User $user */
        // Récupérer l'ID de l'utilisateur connecté
        $employe_id = $user->getId();
    
        
        $avanceFrais = $avanceFraiRepository->findBy(['employe_id' => $employe_id]);
    
        return $this->render('front/avance_frai/index.html.twig', [
            'avance_frais' => $avanceFrais,
        ]);
    }
    



    

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, AvanceFraiRepository $avanceFraiRepository, Security $security): Response
    {
        $avanceFrai = new AvanceFrai();
    
        $form = $this->createForm(AvanceFraiType::class, $avanceFrai);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
           
         /** @var \App\Entity\User $user */
            $user = $security->getUser();
        
           $avanceFrai->setEmployeId($user->getId());
    
            $avanceFraiRepository->save($avanceFrai, true);
    
            return $this->redirectToRoute('app_avance_frai_index', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->renderForm('front/avance_frai/new.html.twig', [
            'avance_frai' => $avanceFrai,
            'form' => $form,
        ]);
    }
    

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(AvanceFrai $avanceFrai): Response
    {
        return $this->render('front/avance_frai/show.html.twig', [
            'avance_frai' => $avanceFrai,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, AvanceFrai $avanceFrai, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AvanceFraiType::class, $avanceFrai);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_avance_frai_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('front/avance_frai/edit.html.twig', [
            'avance_frai' => $avanceFrai,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, AvanceFrai $avanceFrai, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$avanceFrai->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($avanceFrai);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_avance_frai_index', [], Response::HTTP_SEE_OTHER);
    }


}
