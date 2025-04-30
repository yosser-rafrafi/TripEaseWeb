<?php

namespace App\Service;

use App\Entity\Reservationhotel;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class mailingService
{
    private MailerInterface $mailer;
    private QrCodeService $qrCodeService;

    public function __construct(MailerInterface $mailer, QrCodeService $qrCodeService)
    {
        $this->mailer = $mailer;
        $this->qrCodeService = $qrCodeService;
    }

   
    public function sendEmailWithQrCode(Reservationhotel $reservationhotel): void
    {
        // Génère les détails de la réservation à inclure dans le QR code
        $details = sprintf(
            "Réservation #%d\nHôtel : %s\nChambre : %s\nDate début : %s\nDate fin : %s",
            $reservationhotel->getId_reservation(),
            $reservationhotel->getHotel()->getNom(),
            $reservationhotel->getChambre()->getTypeChambre(),
            $reservationhotel->getDateDebut()->format('Y-m-d'),
            $reservationhotel->getDateFin()->format('Y-m-d')
        );
    
        // Génère le QR code avec le service QrCodeService
        $qrCodePaths = $this->qrCodeService->generateQrCode($details);
        $qrCodeFileAbsolute = $qrCodePaths['absolute'];
    
        // Construire l'e-mail
        $email = (new Email())
            ->from('hanafkiri81@gmail.com')
            ->to($reservationhotel->getUser()->getEmail())
            ->subject('Votre QR Code de Réservation')
            ->html('<p>Voici votre réservation avec un QR code en pièce jointe.</p>')
            ->attachFromPath($qrCodePaths['absolute'], 'qr-code.png', 'image/png');
    
        // Envoie l'e-mail
        $this->mailer->send($email);
    
        // Supprime le fichier temporaire après envoi
        unlink($qrCodeFileAbsolute);
    }
}
