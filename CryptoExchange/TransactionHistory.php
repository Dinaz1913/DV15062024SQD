<?php

namespace Reelz222z\CryptoExchange;

use PDO;

class TransactionHistory
{
    private array $transactions;

    public function __construct()
    {
        $this->loadTransactions();
    }

    private function loadTransactions(): void
    {
        $pdo = Database::getInstance()->getConnection();
        $stmt = $pdo->query("SELECT * FROM transactions");
        $this->transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTransactions(): array
    {
        return $this->transactions;
    }

    public function addTransaction(
        string $username,
        string $date,
        string $type,
        string $symbol,
        float $amount,
        float $price,
        float $total
    ): void {
        $pdo = Database::getInstance()->getConnection();
        $sql = "
            INSERT INTO transactions (
                username,
                date,
                type,
                symbol,
                amount,
                price,
                total
            ) VALUES (
                :username,
                :date,
                :type,
                :symbol,
                :amount,
                :price,
                :total
            )
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':username' => $username,
            ':date' => $date,
            ':type' => $type,
            ':symbol' => $symbol,
            ':amount' => $amount,
            ':price' => $price,
            ':total' => $total
        ]);
        $this->loadTransactions();
    }
}
