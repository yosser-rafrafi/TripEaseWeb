<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class WashingtonDatabaseService
{
    private $client;
    private $apiKey;

    // Inject the HttpClient and ParameterBagInterface to get the API key securely
    public function __construct(HttpClientInterface $client, ParameterBagInterface $params)
    {
        $this->client = $client;
        // Fetch API key from environment variables (via .env file)
        $this->apiKey = $params->get('wmata_api_key'); // 
    }

    // Fetch stations from WMATA API
    public function getStations()
    {
        // WMATA API endpoint to get stations
        $url = 'https://api.wmata.com/Rail.svc/json/jStations';

        // Make the request to the WMATA API with the API key in the headers
        $response = $this->client->request('GET', $url, [
            'headers' => [
                'Cache-Control' => 'no-cache',
                'api_key' => $this->apiKey // Pass the API key from the environment
            ]
        ]);

        // Check if the request is successful
        if ($response->getStatusCode() !== 200) {
            // Handle the error (optional)
            return new JsonResponse(['error' => 'Failed to fetch station data'], 500);
        }

        // Parse the response to an array
        $data = $response->toArray();

        // Return the data in a JSON response
        return new JsonResponse($data);
    }
}
