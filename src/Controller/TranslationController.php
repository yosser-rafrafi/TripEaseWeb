<?php

namespace App\Controller;

use App\Service\TranslationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TranslationController extends AbstractController
{
    #[Route('/translate', name: 'translate_text', methods: ['POST'])]
    public function translate(Request $request, TranslationService $translationService): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $text = $data['text'] ?? '';

        if (empty($text)) {
            return new JsonResponse(['error' => 'Texte vide.'], 400);
        }

        // Optionnel : vous pouvez recevoir la langue cible dans la requête
        $targetLanguage = $data['targetLanguage'] ?? 'en';

        $translated = $translationService->translate($text, $targetLanguage);

        return new JsonResponse(['translatedText' => $translated]);
    }
}
