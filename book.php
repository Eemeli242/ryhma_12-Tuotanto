<?php
session_start(); // <- tärkeää, jotta $_SESSION toimii
require 'config.php';


if (!isset($_SESSION['user_id'])) {
    header('Location: login_register.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$cabin_id = (int)($_POST['cabin_id'] ?? 0);
$name = trim($_POST['customer_name'] ?? '');
$email = trim($_POST['customer_email'] ?? '');
$start = $_POST['start_date'] ?? '';
$end = $_POST['end_date'] ?? '';
$guests = max(1, (int)($_POST['guests'] ?? 1));

if (!$cabin_id || !$start || !$end) die('Puuttuvat tiedot.');

$sd = DateTime::createFromFormat('Y-m-d', $start);
$ed = DateTime::createFromFormat('Y-m-d', $end);
if (!$sd || !$ed || $ed <= $sd) die('Virheelliset päivämäärät.');

// Hae mökin hinta ja omistaja
$stmt = $pdo->prepare("SELECT price_per_night, owner_id FROM cabins WHERE id = ?");
$stmt->execute([$cabin_id]);
$cabin = $stmt->fetch();
if (!$cabin) die('Mökkiä ei löytynyt.');

// Lasketaan kokonaishinta
$days = $ed->diff($sd)->days;
$price = (float)$cabin['price_per_night'] * $days;

// Hae käyttäjän saldo
$stmt = $pdo->prepare("SELECT balance FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();
$user_balance = (float)$user['balance'];

// Tarkista saldo
if ($user_balance < $price) die('Ei tarpeeksi saldoa varaukseen.');

// Tarkista päällekkäiset varaukset
$stmt = $pdo->prepare('SELECT COUNT(*) FROM bookings WHERE cabin_id = ? AND start_date < ? AND end_date > ?');
$stmt->execute([$cabin_id, $end, $start]);
$count = $stmt->fetchColumn();

if (!empty($_POST['check_only'])) {
    echo $count > 0 ? 'VARATTU' : 'OK';
    exit;
}

if ($count > 0) die('Valitettavasti mökki on varattu kyseiselle ajalle.');

// Vähennä käyttäjän saldo
$stmt = $pdo->prepare("UPDATE users SET balance = balance - ? WHERE id = ?");
$stmt->execute([$price, $_SESSION['user_id']]);

// Lisää mökin omistajan saldo
$stmt = $pdo->prepare("UPDATE users SET balance = balance + ? WHERE id = ?");
$stmt->execute([$price, $cabin['owner_id']]);

// Lisää varaus tietokantaan
$stmt = $pdo->prepare('INSERT INTO bookings (cabin_id, customer_name, customer_email, start_date, end_date, guests, paid, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
$stmt->execute([$cabin_id, $name, $email, $start, $end, $guests, $price, 'paid']);

header('Location: booking_confirm.php?id=' . $pdo->lastInsertId());
exit;
?>
