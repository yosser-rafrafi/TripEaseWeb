<?php

namespace App\Controller;

use App\Entity\Frai;
use App\Entity\AvanceFrai;
use App\Form\FraiType;
use App\Repository\FraiRepository;
use App\Repository\AvanceFraiRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/frai')]
final class FraiController extends AbstractController
{
    #[Route('/', name: 'app_frai_index', methods: ['GET'])]
    public function index(FraiRepository $fraiRepository): Response
    {
        return $this->render('front/frai/index.html.twig', [
            'frais' => $fraiRepository->findAll(),
        ]);
    }

    

    #[Route('/frai/linked/{avance_frai_id}', name: 'app_frai_linked_frais', methods: ['GET'])]
    public function linkedFrais($avance_frai_id, FraiRepository $fraiRepository, AvanceFraiRepository $avanceFraiRepository): Response
    {
        // Trouver l'AvanceFrai par son ID
        $avanceFrai = $avanceFraiRepository->find($avance_frai_id);
    
        if (!$avanceFrai) {
            throw $this->createNotFoundException('Avance de frais non trouvée');
        }
    
        // Récupérer les frais liés à cette avance
        $frais = $fraiRepository->findBy(['avanceFrai' => $avanceFrai]);
    
        // ✅ Ici tu ajoutes 'avanceFrai' => $avanceFrai
        return $this->render('front/frai/index.html.twig', [
            'frais' => $frais,
            'avanceFrai' => $avanceFrai,
        ]);
    }
    

#[Route('/new/{avance_frai_id}', name: 'app_frai_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager, AvanceFraiRepository $avanceFraiRepository, $avance_frai_id): Response
{
    // Récupérer l'avance de frais correspondant à l'ID passé en paramètre
    $avanceFrai = $avanceFraiRepository->find($avance_frai_id);
    
    if (!$avanceFrai) {
        throw $this->createNotFoundException('Avance de frais non trouvée');
    }

    $frai = new Frai();
    
    // Assigner l'ID de l'employé de l'AvanceFrai au Frai
    $frai->setEmployeId($avanceFrai->getEmployeId());  // Utilisation de l'employe_id de l'AvanceFrai
    
    $frai->setAvanceFrai($avanceFrai);  // Lier le frais à l'avance de frais

    // Créer le formulaire lié à l'entité Frai
    $form = $this->createForm(FraiType::class, $frai);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Gérer le téléchargement du fichier PDF
        $pdfFile = $form->get('pdf')->getData();
        
        if ($pdfFile) {
            // Lire le fichier PDF en binaire et le stocker dans la base de données
            $pdfContent = file_get_contents($pdfFile->getPathname());
            $frai->setPdf($pdfContent); // Sauvegarder le contenu du PDF dans l'entité
        }

        // Persister l'entité Frai dans la base de données
        $entityManager->persist($frai);
        $entityManager->flush();

        // Rediriger vers la page de détails de l'avance de frais après succès
        return $this->redirectToRoute('app_avance_frai_show', ['id' => $avanceFrai->getId()]);
    }

    // Si le formulaire n'est pas soumis ou pas valide, afficher à nouveau le formulaire
    return $this->render('front/frai/new.html.twig', [
        'frai' => $frai,
        'form' => $form->createView(),
    ]);
}
    
// src/Controller/FraiController.php

#[Route('/frai/{id}/pdf', name: 'frai_pdf')]
public function viewPdf(Frai $frai): Response
{
    $pdfStream = $frai->getPdf();

    // ⚠️ Si la BDD retourne un resource (pas une string)
    if (is_resource($pdfStream)) {
        $pdfStream = stream_get_contents($pdfStream);
    }

    return new Response($pdfStream, 200, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'inline; filename="justificatif.pdf"',
    ]);
}



    #[Route('/{id}', name: 'app_frai_show', methods: ['GET'])]
    public function show(Frai $frai): Response
    {
        return $this->render('front/frai/show.html.twig', [
            'frai' => $frai,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_frai_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Frai $frai, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FraiType::class, $frai);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Si un fichier PDF est envoyé, on met à jour le champ PDF
            $pdfFile = $form->get('pdf')->getData();
            
            if ($pdfFile) {
                // Lire le fichier et le stocker en binaire dans la base de données
                $pdfContent = file_get_contents($pdfFile->getPathname());
                $frai->setPdf($pdfContent); // Mise à jour du champ PDF
            }
    
            // Sauvegarder les modifications
            $entityManager->flush();
    
            // Rediriger vers la liste des frais liés à l'avance de frais
            return $this->redirectToRoute('app_frai_linked_frais', [
                'avance_frai_id' => $frai->getAvanceFrai()->getId()
            ]);
        }
    
        return $this->render('front/frai/edit.html.twig', [
            'frai' => $frai,
            'form' => $form->createView(),
        ]);
    }
    
    
    

    #[Route('/{id}', name: 'app_frai_delete', methods: ['POST'])]
    public function delete(Request $request, Frai $frai, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$frai->getId(), $request->request->get('_token'))) {
            $entityManager->remove($frai);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_frai_index', [], Response::HTTP_SEE_OTHER);
    }
}
