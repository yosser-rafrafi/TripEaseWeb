<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Psr\Log\LoggerInterface;  // Importation de l'interface Logger
use App\Repository\VoyageRepository;
use Doctrine\ORM\EntityManagerInterface;

class VoiceAssistantController extends AbstractController
{
    private $client;
    private $witToken;
    private $logger;  // Déclaration de la variable pour le logger

    // Injection de HttpClientInterface et LoggerInterface
    public function __construct(HttpClientInterface $client, LoggerInterface $logger)
    {
        $this->client = $client;
        $this->witToken = 'EXNXSKHTGO2IL7T7KTEQ7W6C3YFDAJWO';  // Ton token d'accès Wit.ai
        $this->logger = $logger;  // Assignation du logger injecté
    }

    #[Route('/api/voice-command', name: 'api_voice_command', methods: ['POST'])]
    public function handleVoiceCommand(Request $request, VoyageRepository $voyageRepository, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $command = $data['command'];

        // Envoi de la commande à Wit.ai
        $response = $this->client->request('GET', 'https://api.wit.ai/message', [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->witToken,
            ],
            'query' => [
                'q' => $command,
            ],
        ]);

        $content = $response->toArray();

        // Vérification de l'intention
        $intent = $content['intents'][0]['name'] ?? null;
        $entities = $content['entities'] ?? [];

        // Ajout de logs pour déboguer
        // Log l'intention et les entités pour vérifier leur extraction
        $this->logger->info('Intent: ' . ($intent ?? 'Aucune intention détectée'));
        $this->logger->info('Entities: ' . json_encode($entities));
        $this->logger->info('Réponse complète Wit.ai : ' . json_encode($content));

        // Traitement en fonction de l'intention
        switch ($intent) {
            case 'app_voyage_new':
                // Rediriger vers le formulaire de création de voyage
                return $this->json([
                    'action' => 'app_voyage_new',
                    'message' => 'Vous allez être redirigé vers le formulaire pour ajouter un nouveau voyage.',
                    'redirect_url' => $this->generateUrl('app_voyage_new'),
                ]);
            case 'app_voyage_statistics':
                return $this->json([
                    'action' => 'app_voyage_statistics',
                    'message' => 'Vous allez être redirigé vers la page des statistiques',
                    'redirect_url' => $this->generateUrl('stats_frais'),
                ]);
            case 'app_statut_index':
                return $this->json([
                    'action' => 'app_statut_index',
                    'message' => 'Vous allez être redirigé vers la page du forum',
                    'redirect_url' => $this->generateUrl('app_statut_index'),
                ]);
            case 'manager_avances':
                return $this->json([
                    'action' => 'manager_avances',
                    'message' => 'Vous allez être redirigé vers la page des avances',
                    'redirect_url' => $this->generateUrl('manager_avances'),
                ]);
            case 'app_hotel_index':
                return $this->json([
                    'action' => 'app_hotel_index',
                    'message' => 'Vous allez être redirigé vers la page des Établissements Hôteliers',
                    'redirect_url' => $this->generateUrl('app_hotel_index'),
                ]);
            case 'app_transport_index':
                return $this->json([
                    'action' => 'app_transport_index',
                    'message' => 'Vous allez être redirigé vers la page des moyens de transports',
                    'redirect_url' => $this->generateUrl('app_transport_index'),
                ]);

            /*case 'app_voyage_edit':
                // Logique pour modifier un voyage existant
                if (isset($entities['trip_title'][0]['value'])) {
                    $title = $entities['trip_title'][0]['value'];
                    $voyage = $voyageRepository->findOneBy(['title' => $title]);

                    if ($voyage) {
                        return $this->json([
                            'action' => 'app_voyage_edit',
                            'id' => $voyage->getId(),
                            'message' => "Voyage '{$title}' trouvé, prêt à être édité.",
                        ]);
                    } else {
                        return $this->json(['response' => "Voyage intitulé '{$title}' introuvable."]);
                    }
                } else {
                    return $this->json(['response' => "Le titre du voyage n'a pas été trouvé dans la commande."]);
                }*/
            
            /*case 'app_voyage_delete':
                // Initialisation de la variable title
                $title = null;
            
                // 1. Tentative de récupération du titre via Wit.ai (entité "trip_title")
                if (isset($entities['trip_title'][0]['value'])) {
                    $title = $entities['trip_title'][0]['value'];
                }
            
                // 2. Si Wit.ai ne trouve pas l'entité, utilisation de la regex pour détecter le titre
                if (!$title && preg_match('/supprimer(?: le)? voyage (.+)/i', $command, $matches)) {
                    $title = trim($matches[1]);
                }
            
                // Vérification du titre
                if ($title) {
                    // Recherche du voyage dans la base de données
                    $voyage = $voyageRepository->findOneBy(['title' => $title]);
            
                    if ($voyage) {
                        // Suppression du voyage et confirmation
                        $em->remove($voyage);
                        $em->flush();
            
                        return $this->json([
                            'action' => 'app_voyage_delete',
                            'id' => $voyage->getId(),
                            'message' => "Voyage '{$title}' supprimé avec succès.",
                        ]);
                    } else {
                        return $this->json(['response' => "Voyage intitulé '{$title}' introuvable."]);
                    }
                }
            
                // Si aucun titre n'est trouvé, renvoyer une réponse d'erreur
                return $this->json(['response' => "Le titre du voyage n'a pas été trouvé dans la commande."]);
                */
            default:
                return $this->json(['response' => "Désolé, je n'ai pas compris la demande."]);
        }
    }
}