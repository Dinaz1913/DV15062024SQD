<?php

namespace Reelz222z\CryptoExchange;

interface ApiClientInterface
{
    public function fetchTopCryptocurrencies(): array;
    public function getCryptocurrencyBySymbol(string $symbol): ?Cryptocurrency;
    public function getCryptocurrencyBySymbolSecond(string $symbol, User $user): ?Cryptocurrency;
}
