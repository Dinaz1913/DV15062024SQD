<?php

namespace Reelz222z\CryptoExchange;

use GuzzleHttp\Client;

class CoinMarketCapApiClient implements ApiClientInterface
{
    private Client $client;
    private string $apiUrl;
    private string $apiKey;

    public function __construct(Client $client, string $apiUrl, string $apiKey)
    {
        $this->client = $client;
        $this->apiUrl = $apiUrl;
        $this->apiKey = $apiKey;
    }

    public function fetchTopCryptocurrencies(): array
    {
        $response = $this->client->request('GET', $this->apiUrl, [
            'headers' => [
                'X-CMC_PRO_API_KEY' => $this->apiKey,
                'Accept' => 'application/json'
            ]
        ]);

        $data = json_decode($response->getBody(), true);

        $cryptocurrencies = [];
        foreach ($data['data'] as $cryptoData) {
            $quote = new Quote(
                $cryptoData['quote']['USD']['price'],
                $cryptoData['quote']['USD']['volume_24h'],
                $cryptoData['quote']['USD']['market_cap'],
                $cryptoData['quote']['USD']['market_cap_dominance'],
                $cryptoData['quote']['USD']['fully_diluted_market_cap'],
                $cryptoData['quote']['USD']['last_updated'] ?? 'N/A'
            );

            $cryptocurrencies[] = new Cryptocurrency(
                $cryptoData['id'],
                $cryptoData['name'],
                $cryptoData['symbol'],
                $quote
            );
        }

        return $cryptocurrencies;
    }

    public function getCryptocurrencyBySymbol(string $symbol): ?Cryptocurrency
    {
        foreach ($this->fetchTopCryptocurrencies() as $crypto) {
            if (trim(strtolower($crypto->getSymbol())) === trim(strtolower($symbol))) {
                return $crypto;
            }
        }
        return null;
    }

    public function getCryptocurrencyBySymbolSecond(string $symbol, User $user): ?Cryptocurrency
    {
        foreach ($user->getPortfolio() as $cryptoData) {
            if ($cryptoData['symbol'] === $symbol) {
                $quote = new Quote(
                    $cryptoData['price'],
                    0,
                    0,
                    0,
                    0,
                    $cryptoData['last_updated'] ?? 'N/A'
                );

                return new Cryptocurrency(
                    0,
                    $cryptoData['name'] ?? 'Unknown',
                    $cryptoData['symbol'],
                    $quote
                );
            }
        }
        return null;
    }
}
