<?php
// src/Service/Ocr/AzureFormRecognizerService.php

namespace App\Service\Ocr;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class AzureFormRecognizerService
{
    private string $endpoint;
    private string $apiKey;
    private HttpClientInterface $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->endpoint = 'https://yalla.cognitiveservices.azure.com/'; // ✅ Ton endpoint
        $this->apiKey = 'DPrjzQKeSB0CXIil5asR98bNCKFvgFgP5HHSONRfTkaQSJ6mTBltJQQJ99BCAC5T7U2XJ3w3AAALACOGQr7S'; // ✅ Ta clé API
        $this->httpClient = $httpClient;
    }

    public function extractTextFromInvoice(string $filePath): ?string
    {
        $fileContents = file_get_contents($filePath);
        if (!$fileContents) {
            throw new \Exception("Impossible de lire le fichier : $filePath");
        }

        $response = $this->httpClient->request('POST', $this->endpoint . 'formrecognizer/v2.1/layout/analyze', [
            'headers' => [
                'Ocp-Apim-Subscription-Key' => $this->apiKey,
                'Content-Type' => 'application/pdf', // Ou image/jpeg etc selon ton fichier
            ],
            'body' => $fileContents,
        ]);

        $operationLocation = $response->getHeaders()['operation-location'][0] ?? null;

        if (!$operationLocation) {
            throw new \Exception('Operation-Location header manquant dans la réponse.');
        }

        // Azure fonctionne en 2 étapes : on doit attendre que l’analyse soit terminée (polling)
        sleep(3); // Petite pause pour attendre

        $resultResponse = $this->httpClient->request('GET', $operationLocation, [
            'headers' => [
                'Ocp-Apim-Subscription-Key' => $this->apiKey,
            ],
        ]);

        $data = $resultResponse->toArray(false);

        if (!isset($data['analyzeResult']['readResults'])) {
            throw new \Exception('Erreur dans la réponse Azure OCR.');
        }

        $text = '';
        foreach ($data['analyzeResult']['readResults'] as $page) {
            foreach ($page['lines'] as $line) {
                $text .= $line['text'] . "\n";
            }
        }

        return $text;
    }
}
