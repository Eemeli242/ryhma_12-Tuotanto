<?php
/**Ei tarvitse vaihtaa misään tilanteessa. Ei kosketa. */
$dbHost = '127.0.0.1';
$dbName = 'lomamokit';
$dbUser = 'root';
$dbPass = '';
$dsn = "mysql:host=$dbHost;dbname=$dbName;charset=utf8mb4";

try {
    $pdo = new PDO($dsn, $dbUser, $dbPass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    die('Tietokantayhteys epäonnistui: ' . $e->getMessage());
}
