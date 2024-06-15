<?php

namespace Reelz222z\CryptoExchange;

class Wallet
{
    private float $balance;

    public function __construct(float $initialBalance)
    {
        $this->balance = $initialBalance;
    }

    public function getBalance(): float
    {
        return $this->balance;
    }

    public function deduct(float $amount): void
    {
        if ($amount > $this->balance) {
            throw new \Exception('Insufficient funds');
        }

        $this->balance -= $amount;
    }

    public function add(float $amount): void
    {
        $this->balance += $amount;
    }
}
