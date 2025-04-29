<?php

namespace App\Service;

use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class SwissTransportApiService
{
    private $client;
    private $logger;

    public function __construct(HttpClientInterface $client, LoggerInterface $logger)
    {
        $this->client = $client;
        $this->logger = $logger;
    }

    public function getStations(): array
    {
        try {
            $this->logger->info('Fetching stations from the Swiss API');
    
            $cities = ['Zurich', 'Geneva', 'Basel'];
            $allStations = [];
    
            foreach ($cities as $city) {
                $url = 'http://transport.opendata.ch/v1/locations?query=' . urlencode($city);
    
                $this->logger->info('Requesting URL: ' . $url);
    
                $response = $this->client->request('GET', $url);
    
                if ($response->getStatusCode() !== 200) {
                    $this->logger->error('Failed to fetch Swiss stations for ' . $city, [
                        'status_code' => $response->getStatusCode(),
                        'url' => $url,
                    ]);
                    continue;
                }
    
                $data = $response->toArray();
                $this->logger->info('Decoded data for ' . $city . ': ' . print_r($data, true));
    
                $allStations = array_merge($allStations, $data['stations'] ?? []);
            }
    
            return array_slice($allStations, 0, 100);
    
        } catch (\Exception $e) {
            $this->logger->error('Error fetching stations from Swiss API', [
                'exception' => $e->getMessage(),
            ]);
    
            return []; // Just return an empty array on failure
        }
    }
    
    
    
}
