<?php
namespace App\Controller;

use App\Entity\Reservationtransport;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[Route('/paiement')]
class PaymentController extends AbstractController
{
    #[Route('/create-checkout-session/{id}', name: 'paiement_checkout')]
    public function createCheckoutSession(Reservationtransport $reservation): Response
    {
        \Stripe\Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);

        // Get price from the related Transport entity
        $montantEnCentimes = intval($reservation->getTransport()->getTransportPrix() * 100);

        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => 'Paiement réservation transport #' . $reservation->getId(),
                    ],
                    'unit_amount' => $montantEnCentimes,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => $this->generateUrl('app_reservationtransport_payment_success', [
            'id' => $reservation->getId()
              ], UrlGeneratorInterface::ABSOLUTE_URL),

            'cancel_url' => $this->generateUrl('app_reservationtransport_payment_cancel', [
             'id' => $reservation->getId()
               ], UrlGeneratorInterface::ABSOLUTE_URL),
]);

        return $this->redirect($session->url);
    }
    
}
