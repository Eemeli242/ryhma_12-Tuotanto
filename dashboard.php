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
.header-flex {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 0;
}
.profile-dropdown {
    position: relative;
    display: inline-block;
}
.profile-dropdown-content {
    display: none;
    position: absolute;
    right: 0;
    background-color: #fff;
    min-width: 180px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    border-radius: 8px;
    overflow: hidden;
    z-index: 1;
}
.profile-dropdown-content a {
    display: block;
    padding: 10px 16px;
    text-decoration: none;
    color: #333;
    transition: background-color 0.2s;
}
.profile-dropdown-content a:hover { background-color: #f2f2f2; }
.profile-dropdown:hover .profile-dropdown-content { display: block; }

.profile-pic {
    width: 40px; height: 40px; border-radius: 50%; object-fit: cover; margin-right: 8px;
}

.dashboard-grid {
    display: grid; grid-template-columns: 1fr 2fr; gap: 20px;
}
.dashboard-section {
    background: #fff; padding: 20px; border-radius: 12px; box-shadow: 0 6px 20px rgba(0,0,0,0.08);
}
.cabin-table {
    width: 100%; border-collapse: collapse; margin-top:10px;
}
.cabin-table th, .cabin-table td {
    border: 1px solid #ccc; padding: 10px; text-align: left;
}
.cabin-table th { background-color: #f2f2f2; }
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
        <a href="uusi_mokki.php" class="btn">Lisää uusi mökki</a>
    </section>
</main>
