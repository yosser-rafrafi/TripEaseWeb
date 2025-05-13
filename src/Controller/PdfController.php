<?php

namespace App\Controller;

use App\Entity\AvanceFrai;
use App\Repository\UserRepository;
use App\Service\PdfGeneratorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class PdfController extends AbstractController
{
    #[Route('/pdf/test', name: 'pdf_test')]
    public function testPdf(PdfGeneratorService $pdfGenerator): Response
    {
        $html = '<h1>Fiche Avance Frais</h1><p>Employé : John Doe</p><p>Montant : 1000€</p>';
        $pdfContent = $pdfGenerator->generatePdf($html);

        return new Response($pdfContent, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="fiche-avance.pdf"',
        ]);
    }

    #[Route('/avance/{id}/facture', name: 'avance_facture')]
    public function facture(
        AvanceFrai $avance_frai,
        UserRepository $userRepo,
        PdfGeneratorService $pdfGenerator
    ): Response {
     
        $user = $userRepo->find($avance_frai->getEmployeId());
    
      
        $fraisList = $avance_frai->getFrais();
    
      
        $html = $this->renderView('pdf/facture.html.twig', [
            'avance_frai' => $avance_frai,
            'user' => $user,
            'fraisList' => $fraisList,
        ]);
    
       
        $pdfContent = $pdfGenerator->generatePdf($html);
    
       
        return new Response($pdfContent, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="facture_avance_'.$avance_frai->getId().'.pdf"',
        ]);
    }
    
    
}
