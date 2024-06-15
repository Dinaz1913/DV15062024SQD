<?php

require 'vendor/autoload.php';
require 'database.php';

use GuzzleHttp\Client;
use Reelz222z\CryptoExchange\User;
use Reelz222z\CryptoExchange\CoinMarketCapApiClient;
use Reelz222z\CryptoExchange\TransactionHistory;

$client = new Client();
$apiUrl = 'https://sandbox-api.coinmarketcap.com/v1/cryptocurrency/listings/latest';
$apiKey = 'YOUR_API_KEY';

$users = User::loadUsers();

// Get the username from the command line
$username = readline("Enter your username: ");

// Find the user
$user = null;
foreach ($users as $u) {
    if ($u->getName() === $username) {
        $user = $u;
        break;
    }
}

if ($user === null) {
    echo "User not found.\n";
    exit;
}

echo "User found: " . $user->getName() . " with wallet balance: " . $user->getWallet()->getBalance() . " USD\n";

// Fetch top cryptocurrencies
$cryptoData = new CoinMarketCapApiClient($client, $apiUrl, $apiKey);
$topCryptos = $cryptoData->fetchTopCryptocurrencies();
echo "Top cryptocurrencies fetched successfully.\n";

$transactionHistoryBuy = new TransactionHistory();
$transactionHistorySell = new TransactionHistory();

function displayMenu(): void
{
    echo "Choose an option:\n";
    echo "1. List top cryptocurrencies\n";
    echo "2. Search cryptocurrency by symbol\n";
    echo "3. Buy cryptocurrency\n";
    echo "4. Sell cryptocurrency\n";
    echo "5. Display wallet state\n";
    echo "6. Display transaction history\n";
    echo "7. Exit\n";
}

while (true) {
    displayMenu();
    $choice = (int) readline("Enter your choice: ");

    switch ($choice) {
        case 1:
            echo "Available Cryptocurrencies:\n";
            foreach ($topCryptos as $crypto) {
                echo "Name: " . $crypto->getName() . " - Symbol: " . $crypto->getSymbol() . "\n";
                echo "Market Cap Dominance: " . $crypto->getQuote()->getMarketCapDominance() . "\n";
                echo "Price: $" . $crypto->getQuote()->getPrice() . "\n";
            }
            break;

        case 2:
            $symbol = readline("Enter the cryptocurrency symbol: ");
            $crypto = $cryptoData->getCryptocurrencyBySymbol($symbol);
            if ($crypto === null) {
                echo "Cryptocurrency not found.\n";
                break;
            }

            echo "Name: " . $crypto->getName() . "\n";
            echo "Symbol: " . $crypto->getSymbol() . "\n";
            echo "Market Cap: $" . $crypto->getQuote()->getMarketCap() . "\n";
            echo "Price: $" . $crypto->getQuote()->getPrice() . "\n";
            echo "Market Cap Dominance: " . $crypto->getQuote()->getMarketCapDominance() . "\n";
            break;

        case 3:
            $symbol = readline("Enter the cryptocurrency symbol to buy: ");
            $crypto = $cryptoData->getCryptocurrencyBySymbol($symbol);
            if ($crypto === null) {
                echo "Cryptocurrency not found.\n";
                break;
            }

            echo "Name: " . $crypto->getName() . "\n";
            echo "Symbol: " . $crypto->getSymbol() . "\n";
            echo "Price: $" . $crypto->getQuote()->getPrice() . "\n";
            $choice = readline("Do you want to purchase this value? (yes/no): ");
            if (strtolower($choice) === 'yes') {
                $amount = (float) readline("Enter the amount to buy: ");
                $user->buyCryptocurrency($crypto, $amount);
                $transactionHistoryBuy->addTransaction(
                    $username,
                    date('Y-m-d H:i:s'),
                    'buy',
                    $crypto->getSymbol(),
                    $amount,
                    $crypto->getQuote()->getPrice(),
                    $crypto->getQuote()->getPrice() * $amount
                );
                echo "Bought $amount of " . $crypto->getName() . "\n";
                User::saveUser($user);
            }
            break;

        case 4:
            $symbol = readline("Enter the cryptocurrency symbol to sell: ");
            $crypto = $cryptoData->getCryptocurrencyBySymbolSecond($symbol, $user);
            if ($crypto === null) {
                echo "Cryptocurrency not found in your portfolio.\n";
                break;
            }

            $amount = (float) readline("Enter the amount to sell: ");
            try {
                $user->sellCryptocurrency($crypto, $amount);
                $transactionHistorySell->addTransaction(
                    $username,
                    date('Y-m-d H:i:s'),
                    'sell',
                    $crypto->getSymbol(),
                    $amount,
                    $crypto->getQuote()->getPrice(),
                    $crypto->getQuote()->getPrice() * $amount
                );
                echo "Sold $amount of " . $crypto->getSymbol() . "\n";
                User::saveUser($user);
            } catch (\Exception $exception) {
                echo $exception->getMessage() . "\n";
            }
            break;

        case 5:
            echo "Current Wallet State:\n";
            echo "Balance: " . $user->getWallet()->getBalance() . " USD\n";
            echo "Portfolio: \n";
            foreach ($user->getPortfolio() as $symbol => $items) {
                $totalAmount = 0;
                foreach ($items as $item) {
                    $totalAmount += $item['amount'];
                }
                echo "$symbol: $totalAmount\n";
            }
            break;

        case 6:
            echo "Transaction History:\n";
            echo "Buy Transactions:\n";
            foreach ($transactionHistoryBuy->getTransactions() as $transaction) {
                echo $transaction['date'] . ": "
                    . $transaction['type'] . " "
                    . $transaction['amount']
                    . " of " . $transaction['symbol']
                    . " at $" . $transaction['price']
                    . " each. Total: $" . $transaction['total'] . "\n";
            }

            echo "Sell Transactions:\n";
            foreach ($transactionHistorySell->getTransactions() as $transaction) {
                echo $transaction['date'] . ": "
                    . $transaction['type'] . " "
                    . $transaction['amount']
                    . " of " . $transaction['symbol']
                    . " at $" . $transaction['price']
                    . " each. Total: $" . $transaction['total'] . "\n";
            }
            break;

        case 7:
            exit;

        default:
            echo "Invalid choice. Please try again.\n";
            break;
    }
}
