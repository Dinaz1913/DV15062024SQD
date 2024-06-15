<?php

namespace Reelz222z\CryptoExchange;

class Database
{
    private static ?Database $instance = null;
    private \PDO $pdo;

    private function __construct()
    {
        $this->pdo = new \PDO('sqlite:' . __DIR__ . '/../../../cryptoExchange.db');
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->createTables();
    }

    public static function getInstance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection(): \PDO
    {
        return $this->pdo;
    }

    private function createTables(): void
    {
        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS users (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL,
                wallet_balance REAL NOT NULL
            )
        ");

        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS portfolio (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                user_id INTEGER NOT NULL,
                symbol TEXT NOT NULL,
                amount REAL NOT NULL,
                price REAL NOT NULL,
                last_updated TEXT NOT NULL,
                FOREIGN KEY (user_id) REFERENCES users(id)
            )
        ");

        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS transactions (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                username TEXT NOT NULL,
                date TEXT NOT NULL,
                type TEXT NOT NULL,
                symbol TEXT NOT NULL,
                amount REAL NOT NULL,
                price REAL NOT NULL,
                total REAL NOT NULL
            )
        ");
    }
}
