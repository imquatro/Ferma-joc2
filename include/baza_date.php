<?php
date_default_timezone_set('Europe/Bucharest');  // sau timezone-ul tău

// Configurare conexiune baza de date
$host = 'localhost';
$db   = 'ferma_joc';
$user = 'root';      // schimbă cu user-ul tău DB
$pass = '';          // schimbă cu parola ta DB
$charset = 'utf8';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // arată erorile
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
     $pdo = new PDO($dsn, $user, $pass, $options);
     
     // Setăm timezone pentru sesiunea curentă MySQL
     $pdo->exec("SET time_zone = '+03:00'");
} catch (\PDOException $e) {
     throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
