<?php

namespace Reelz222z\CryptoExchange;

class Transaction
{
    private string $username;
    private string $date;
    private string $type;
    private string $symbol;
    private float $amount;
    private float $price;
    private float $total;

    public function __construct(
        string $username,
        string $date,
        string $type,
        string $symbol,
        float $amount,
        float $price,
        float $total
    ) {
        $this->username = $username;
        $this->date = $date;
        $this->type = $type;
        $this->symbol = $symbol;
        $this->amount = $amount;
        $this->price = $price;
        $this->total = $total;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getDate(): string
    {
        return $this->date;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getSymbol(): string
    {
        return $this->symbol;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getTotal(): float
    {
        return $this->total;
    }

    public function toArray(): array
    {
        return [
            'username' => $this->username,
            'date' => $this->date,
            'type' => $this->type,
            'symbol' => $this->symbol,
            'amount' => $this->amount,
            'price' => $this->price,
            'total' => $this->total
        ];
    }
}
