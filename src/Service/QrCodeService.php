<?php

namespace App\Service;

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Symfony\Component\Filesystem\Filesystem;

class QrCodeService
{
  /*  public function generateQrCode(string $data): string
    {
        $qrCode = new QrCode($data);

        $writer = new PngWriter();
        $result = $writer->write($qrCode);

        $tempFile = tempnam(sys_get_temp_dir(), 'qr_') . '.png';
        file_put_contents($tempFile, $result->getString());

        return $tempFile;
    }*/
    

    
    public function generateQrCode(string $data): array
{
    $qrCode = new QrCode($data);
    $writer = new PngWriter();
    $result = $writer->write($qrCode);

    // Chemin absolu vers le dossier public/uploads/qrcodes/
    $uploadDir = __DIR__ . '/../../public/uploads/qrcodes/';
    $filesystem = new Filesystem();

    if (!$filesystem->exists($uploadDir)) {
        $filesystem->mkdir($uploadDir, 0700);
    }

    //Génère un nom de fichier unique du type
    $fileName = uniqid('qr_', true) . '.png';
    $filePathAbsolute = realpath($uploadDir) . DIRECTORY_SEPARATOR . $fileName;

    // Enregistre l'image
    file_put_contents($filePathAbsolute, $result->getString());

    // Retourne les deux chemins
    return [
        'absolute' => $filePathAbsolute,              // pour attachFromPath dans Email
        'relative' => '/uploads/qrcodes/' . $fileName, // pour afficher dans un lien Web (HTML/Twig)
    ];
}

}
