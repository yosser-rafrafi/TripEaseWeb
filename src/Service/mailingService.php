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
        // Créer le contenu du QR code avec emojis et détails
        $content = "🔒 Confirmation de réservation\n\n";
        $content .= "👤 Nom : " . $reservationhotel->getUser()->getNom() . "\n";
        $content .= "📧 Email : " . $reservationhotel->getUser()->getEmail() . "\n";
        $content .= "🏨 Hôtel : " . $reservationhotel->getHotel()->getNom() . "\n";
        $content .= "🛏️ Chambre : " . $reservationhotel->getChambre()->getTypeChambre() . "\n";
        $content .= "💰 Prix : " . $reservationhotel->getChambre()->getPrix_par_nuit() . " TND\n";
        $content .= "📅 Du : " . $reservationhotel->getDateDebut()->format('Y-m-d') . "\n";
        $content .= "📅 Au : " . $reservationhotel->getDateFin()->format('Y-m-d') . "\n";
    
        // Génère le QR code avec le nouveau contenu
        $qrCodePaths = $this->qrCodeService->generateQrCode($content);
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
