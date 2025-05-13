<?php
// src/Controller/CurrencyController.php
namespace App\Controller;

use App\Service\CurrencyApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

#[Route('/currency', name: 'currency_')]
class CurrencyController extends AbstractController
{
    public function __construct(private CurrencyApiService $currencyApi)
    {
    }

    #[Route('/rates', name: 'rates', methods: ['GET'])]
    public function rates(): JsonResponse
    {
        // Récupère les taux live
        $this->currencyApi->fetchExchangeRates();

        // Prépare la réponse JSON
        return $this->json([
            'TND_USD' => $this->currencyApi->convert(1, 'TND', 'USD'),
            'TND_EUR' => $this->currencyApi->convert(1, 'TND', 'EUR'),
        ]);
    }

    #[Route('/convert', name: 'convert', methods: ['GET'])]
    public function convert(Request $request): JsonResponse
    {
        $amount = $request->query->get('amount');
        $from   = $request->query->get('from');
        $to     = $request->query->get('to');

        if (!is_numeric($amount) || !$from || !$to) {
            return $this->json(['error' => 'Paramètres invalides'], 400);
        }

        $this->currencyApi->fetchExchangeRates();
        $converted = $this->currencyApi->convert((float)$amount, $from, $to);

        return $this->json(['converted' => $converted]);
    }
}
