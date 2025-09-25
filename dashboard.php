<?php
if (session_status() == PHP_SESSION_NONE) {
}

require 'config.php';
include 'header.php';
// Tarkista kirjautuminen
if (!isset($_SESSION['user_id'])) {
    header('Location: login_register.php');
    exit;
}

// Hae käyttäjän tiedot
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

// Hae käyttäjän mökit
$stmt = $pdo->prepare("
    SELECT c.*, COUNT(b.id) AS booking_count
    FROM cabins c
    LEFT JOIN bookings b ON c.id = b.cabin_id
    WHERE c.owner_id = ?
    GROUP BY c.id
");
$stmt->execute([$_SESSION['user_id']]);
$cabins = $stmt->fetchAll();
?>
<!doctype html>
<html lang="fi">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Dashboard</title>
<link rel="stylesheet" href="style.css">
<style>
</style>
</head>
<body>
<main class="container dashboard-grid">
    <!-- Profiili -->
    <section class="dashboard-section">
        <h2>Profiili</h2>
        <img src="<?=htmlspecialchars($user['profile_image'] ?? 'default-avatar.png')?>" alt="Profiili" class="profile-pic" style="width:100px;height:100px;">
        <p><strong>Käyttäjänimi:</strong> <?=htmlspecialchars($user['username'])?></p>
        <p><strong>Email:</strong> <?=htmlspecialchars($user['email'])?></p>
        <p><strong>Puhelin:</strong> <?=htmlspecialchars($user['phone'])?></p>
        <p><strong>Saldo:</strong> €<?=number_format($user['balance'] ?? 0, 2)?></p> <!-- balance lisätty -->
        <a href="edit_profile.php" class="btn">Muokkaa profiilia</a>
    </section>

    <!-- Käyttäjän mökit -->
    <section class="dashboard-section">
        <h2>Omat mökit</h2>
        <?php if ($cabins): ?>
        <table class="cabin-table">
            <thead>
                <tr>
                    <th>Nimi</th>
                    <th>Hinta/yö</th>
                    <th>Varauksia</th>
                    <th>Toiminnot</th>
                </tr>
            </thead>
            <tbody>
<?php foreach ($cabins as $cabin): ?>
<tr>
  <td><?=htmlspecialchars($cabin['name'])?></td>
  <td><?=number_format($cabin['price_per_night'], 2)?> €</td>
  <td><?=intval($cabin['booking_count'])?></td>
  <td>
    <a href="edit_cabin.php?id=<?= $cabin['id'] ?>" class="btn">Muokkaa</a>
    <a href="delete_cabin.php?id=<?= $cabin['id'] ?>" onclick="return confirm('Haluatko varmasti poistaa mökin?');" class="btn btn-danger">Poista</a>
  </td>
</tr>
<?php endforeach; ?>
            </tbody>
        </table>
        <?php else: ?>
            <p>Sinulla ei ole vielä lisättyjä mökkejä.</p>
        <?php endif; ?>
        <a href="add_cabin.php" class="btn">Lisää uusi mökki</a>
    </section>
</main>
