<?php

namespace Reelz222z\CryptoExchange;

class Quote
{
    private float $price;
    private float $volume24h;
    private float $marketCap;
    private float $marketCapDominance;
    private float $fullyDilutedMarketCap;
    private string $lastUpdated;

    public function __construct(
        float $price,
        float $volume24h,
        float $marketCap,
        float $marketCapDominance,
        float $fullyDilutedMarketCap,
        string $lastUpdated
    ) {
        $this->price = $price;
        $this->volume24h = $volume24h;
        $this->marketCap = $marketCap;
        $this->marketCapDominance = $marketCapDominance;
        $this->fullyDilutedMarketCap = $fullyDilutedMarketCap;
        $this->lastUpdated = $lastUpdated;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getVolume24h(): float
    {
        return $this->volume24h;
    }

    public function getMarketCap(): float
    {
        return $this->marketCap;
    }

    public function getMarketCapDominance(): float
    {
        return $this->marketCapDominance;
    }

    public function getFullyDilutedMarketCap(): float
    {
        return $this->fullyDilutedMarketCap;
    }

    public function getLastUpdated(): string
    {
        return $this->lastUpdated;
    }
}
