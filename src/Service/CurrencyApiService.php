<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class CurrencyApiService
{
    private $client;
    private $appId;
    private $exchangeRates;

    public function __construct(HttpClientInterface $client, string $appId)
    {
        $this->client = $client;
        $this->appId = $appId;
    }

    public function fetchExchangeRates(): void
    {
        $url = "https://openexchangerates.org/api/latest.json?app_id=" . $this->appId;
        $response = $this->client->request('GET', $url);

        $data = $response->toArray();
        $this->exchangeRates = $data['rates'];
    }

    public function getRate(string $currency): ?float
    {
        if ($this->exchangeRates === null) {
            $this->fetchExchangeRates();
        }

        return $this->exchangeRates[$currency] ?? null;
    }

    public function convert(float $amount, string $fromCurrency, string $toCurrency): ?float
    {
        $this->fetchExchangeRates();
        
        if (!isset($this->exchangeRates[$fromCurrency]) || !isset($this->exchangeRates[$toCurrency])) {
            return null;
        }

        $usdAmount = $amount / $this->exchangeRates[$fromCurrency];
        return $usdAmount * $this->exchangeRates[$toCurrency];
    }
}
