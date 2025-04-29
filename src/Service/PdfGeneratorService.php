<?php

namespace App\Service;

use Dompdf\Dompdf;
use Dompdf\Options;

class PdfGeneratorService
{
    public function generatePdf(string $html): string
    {
        try {
            $options = new Options();
            $options->set('defaultFont', 'Arial');
            $dompdf = new Dompdf($options);

            // Charge le contenu HTML
            $dompdf->loadHtml($html);

            // Définit le format de papier (A4, portrait)
            $dompdf->setPaper('A4', 'portrait');

            // Rend le PDF
            $dompdf->render();

            // Retourne le contenu binaire du PDF
            return $dompdf->output();
        } catch (\Exception $e) {
            // Gestion des erreurs
            throw new \RuntimeException("Erreur lors de la génération du PDF : " . $e->getMessage());
        }
    }
}
