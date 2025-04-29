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
use App\Service\Ocr\AzureFormRecognizerService;
use Symfony\Component\Form\FormError;
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

    

    #[Route(path: '/frai/linked/{avance_frai_id}', name: 'app_frai_linked_frais', methods: ['GET'])]
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
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
        AvanceFraiRepository $avanceFraiRepository,
        AzureFormRecognizerService $ocrService,
        int $avance_frai_id
    ): Response {
        // Récupérer l'avance de frais
        $avanceFrai = $avanceFraiRepository->find($avance_frai_id);
        if (!$avanceFrai) {
            throw $this->createNotFoundException('Avance de frais non trouvée');
        }

        // Initialiser l'entité Frai
        $frai = new Frai();
        $frai->setEmployeId($avanceFrai->getEmployeId());
        $frai->setAvanceFrai($avanceFrai);

        $form = $this->createForm(FraiType::class, $frai);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $errors = [];
                // Traitement du PDF justificatif
                $pdfFile = $form->get('pdf')->getData();
                if ($pdfFile) {
                    $filePath = $pdfFile->getPathname();
                    $frai->setPdf(file_get_contents($filePath));

                    // Appel au service OCR Azure
                    $extractedText = $ocrService->extractTextFromInvoice($filePath);

                    // Extraction du montant détecté
                    $extractedAmount = null;
                    if (preg_match('/(\d+[\.,]\d{2})/', $extractedText, $m)) {
                        $extractedAmount = floatval(str_replace(',', '.', $m[1]));
                        if (abs($extractedAmount - $frai->getMontant()) > 0.01) {
                            $errors[] = sprintf(
                                'Le montant saisi (%.2f) ne correspond pas au montant détecté (%.2f).',
                                $frai->getMontant(),
                                $extractedAmount
                            );
                        }
                    }

                    // Extraction de la date détectée
                    $extractedDate = null;
                    if (preg_match('/(\d{2}[\/\-]\d{2}[\/\-]\d{4})/', $extractedText, $d)) {
                        $extractedDate = \DateTime::createFromFormat('d/m/Y', $d[1]) ?: new \DateTime($d[1]);
                        if ($frai->getDateDepense() && $extractedDate->format('Y-m-d') !== $frai->getDateDepense()->format('Y-m-d')) {
                            $errors[] = sprintf(
                                'La date saisie (%s) ne correspond pas à la date détectée (%s).',
                                $frai->getDateDepense()->format('Y-m-d'),
                                $extractedDate->format('Y-m-d')
                            );
                        }
                    }

                    // Ajouter les erreurs au formulaire
                    foreach ($errors as $errorMessage) {
                        $form->addError(new FormError($errorMessage));
                    }
                }

                // Si pas d'erreurs OCR, persister
                if (0 === count($form->getErrors(true))) {
                    $entityManager->persist($frai);
                    $entityManager->flush();
                    return $this->redirectToRoute('app_avance_frai_index', ['id' => $avanceFrai->getId()]);
                }
            }
            // En cas de soumission invalide ou erreurs OCR, rester sur le formulaire
        }

        return $this->render('front/frai/new.html.twig', [
            'frai' => $frai,
            'form' => $form->createView(),
        ]);
    } 
    
    


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
