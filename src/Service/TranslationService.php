<?php
namespace App\Service;

use Stichoza\GoogleTranslate\GoogleTranslate;

class TranslationService
{
    private $googleTranslate;

    // Constructor now expects a translator instance, not just a language string
    public function __construct(string $defaultLanguage)
    {
        // Initialize GoogleTranslate with the default language passed as an argument
        $this->googleTranslate = new GoogleTranslate($defaultLanguage);
    }

    public function translate(string $text, ?string $targetLanguage = null): string
    {
        // Set the target language if provided
        if ($targetLanguage !== null) {
            $this->googleTranslate->setTarget($targetLanguage);
        }
        return $this->googleTranslate->translate($text);
    }
}

