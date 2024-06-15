<?php
try {
    $pdo = new PDO('sqlite:crypto_exchange.db');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "CREATE TABLE IF NOT EXISTS users (
                id INTEGER PRIMARY KEY,
                name TEXT NOT NULL,
                email TEXT NOT NULL UNIQUE
            )";
    $pdo->exec($sql);

    $sql = "CREATE TABLE IF NOT EXISTS portfolio (
                id INTEGER PRIMARY KEY,
                user_id INTEGER,
                asset TEXT NOT NULL,
                amount REAL NOT NULL,
                FOREIGN KEY(user_id) REFERENCES users(id)
            )";
    $pdo->exec($sql);

    $sql = "CREATE TABLE IF NOT EXISTS transactions (
                id INTEGER PRIMARY KEY,
                user_id INTEGER,
                asset TEXT NOT NULL,
                amount REAL NOT NULL,
                transaction_type TEXT NOT NULL,
                date TEXT NOT NULL,
                FOREIGN KEY(user_id) REFERENCES users(id)
            )";
    $pdo->exec($sql);

    echo "Database initialized and tables created.";
} catch (PDOException $e) {
    echo $e->getMessage();
}

