<?php
require 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$id = (int)($_GET['id'] ?? 0);
if (!$id) {
    die("Mökkiä ei löytynyt.");
}

// Hae mökki ja tarkista omistajuus
$stmt = $pdo->prepare("SELECT * FROM cabins WHERE id = ? AND owner_id = ?");
$stmt->execute([$id, $_SESSION['user_id']]);
$cabin = $stmt->fetch();

if (!$cabin) {
    die("Et voi poistaa tätä mökkiä.");
}

// Poista kuva tiedostojärjestelmästä (jos olemassa)
if (!empty($cabin['image']) && file_exists($cabin['image'])) {
    @unlink($cabin['image']); // @ estää virheilmoitukset jos tiedostoa ei löydy
}

// Poista mökki tietokannasta
$stmt = $pdo->prepare("DELETE FROM cabins WHERE id = ? AND owner_id = ?");
$stmt->execute([$id, $_SESSION['user_id']]);

// Ohjataan takaisin dashboardille
header("Location: dashboard.php?message=Mökki poistettu!");
exit;
