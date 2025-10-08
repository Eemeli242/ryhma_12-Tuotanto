<?php
require 'config.php';
session_start();

// Tarkista, että käyttäjä on kirjautunut
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Hae mökin ID
$id = (int)($_GET['id'] ?? 0);
if (!$id) {
    die("Mökkiä ei löytynyt.");
}

// Hae mökki ja tarkista omistajuus (haetaan vain image-kenttä)
$stmt = $pdo->prepare("SELECT image FROM cabins WHERE id = ? AND owner_id = ?");
$stmt->execute([$id, $_SESSION['user_id']]);
$cabin = $stmt->fetch();

if (!$cabin) {
    die("Et voi poistaa tätä mökkiä.");
}

// Poista kuva tiedostojärjestelmästä (jos olemassa)
if (!empty($cabin['image']) && file_exists($cabin['image'])) {
    unlink($cabin['image']);
}

// Poista mökki tietokannasta
$stmt = $pdo->prepare("DELETE FROM cabins WHERE id = ? AND owner_id = ?");
$stmt->execute([$id, $_SESSION['user_id']]);

// Ohjataan takaisin dashboardille viestillä
header("Location: dashboard.php?message=" . urlencode("Mökki poistettu!"));
exit;
