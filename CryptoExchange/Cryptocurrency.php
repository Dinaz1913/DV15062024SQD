<?php

namespace Reelz222z\CryptoExchange;

class Cryptocurrency
{
    private int $id;
    private string $name;
    private string $symbol;
    private Quote $quote;

    public function __construct(int $id, string $name, string $symbol, Quote $quote)
    {
        $this->id = $id;
        $this->name = $name;
        $this->symbol = $symbol;
        $this->quote = $quote;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSymbol(): string
    {
        return $this->symbol;
    }

    public function getQuote(): Quote
    {
        return $this->quote;
    }
}
