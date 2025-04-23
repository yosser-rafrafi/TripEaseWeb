<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class AviationStackService
{
    private $client;
    private $apiKey = '399c910c5e1bd4438c80108adeb4257b'; 

    public function __construct(HttpClientInterface $client)
    {
        //instance de http client 
        $this->client = $client;
    }

    public function getFlightData(string $flightNumber): ?array
    {
        $url = 'http://api.aviationstack.com/v1/flights';
        $response = $this->client->request('GET', $url, [
            'query' => [
                'access_key' => $this->apiKey,
                'flight_iata' => $flightNumber,
            ],
        ]);

        //from json to array
        $data = $response->toArray();

        if (!isset($data['data']) || empty($data['data'])) {
            return null;
        }

        return $data['data'][0];
    }
}
