<?php
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

// Hae arvostelujen keskiarvot jokaiselle mökille
$reviewStmt = $pdo->prepare("SELECT AVG(rating) AS avg_rating, COUNT(*) AS review_count FROM reviews WHERE cabin_id = ?");
foreach ($cabins as &$cabin) {
    $reviewStmt->execute([$cabin['id']]);
    $stats = $reviewStmt->fetch();
    $cabin['avg_rating'] = $stats['avg_rating'] ?? 0;
    $cabin['review_count'] = $stats['review_count'] ?? 0;
}
unset($cabin);

// Hae viimeisimmät varaukset (omien mökkien varaukset)
$stmt = $pdo->prepare("
    SELECT b.*, c.name AS cabin_name
    FROM bookings b
    JOIN cabins c ON b.cabin_id = c.id
    WHERE c.owner_id = ?
    ORDER BY b.created_at DESC
    LIMIT 5
");
$stmt->execute([$_SESSION['user_id']]);
$bookings = $stmt->fetchAll();

// Hae suosituin mökki (eniten varauksia)
$top_cabin = null;
if ($cabins) {
    usort($cabins, fn($a,$b) => $b['booking_count'] - $a['booking_count']);
    $top_cabin = $cabins[0];
}

// Hae käyttäjän omat varaukset
$stmt = $pdo->prepare("
    SELECT b.*, c.name AS cabin_name
    FROM bookings b
    JOIN cabins c ON b.cabin_id = c.id
    WHERE b.customer_id = ?
    ORDER BY b.start_date DESC
");
$stmt->execute([$_SESSION['user_id']]);
$my_bookings = $stmt->fetchAll();
?>

<!doctype html>
<html lang="fi">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Käyttäjädashboard</title>
<link rel="stylesheet" href="style.css">
<style>
.btn { display:inline-block; padding:5px 10px; background:#116d38; color:#fff; text-decoration:none; border-radius:5px; }
.btn-danger { background:#d9534f; }
</style>
</head>
<body>
<main class="container dashboard-grid">

<!-- Profiili -->
<section class="dashboard-section">
    <h2>Profiili</h2>
    <div class="profile-section">
        <img src="<?=htmlspecialchars($user['profile_image'] ?? 'default-avatar.png')?>" alt="Profiili" class="profile-pic">
        <div>
            <p><strong>Käyttäjänimi:</strong> <?=htmlspecialchars($user['username'])?></p>
            <p><strong>Email:</strong> <?=htmlspecialchars($user['email'])?></p>
            <p><strong>Puhelin:</strong> <?=htmlspecialchars($user['phone'])?></p>
            <p><strong>Saldo:</strong> €<?=number_format($user['balance'] ?? 0, 2)?></p>
        </div>
    </div>
    <a href="edit_profile.php" class="btn">Muokkaa profiilia</a>
    <a href="/admin/index.php" class="btn">Admin paneeli</a>
</section>

<!-- Omat mökit -->
<section class="dashboard-section">
    <h2>Omat mökit</h2>

    <?php if ($top_cabin): ?>
    <div class="top-cabin-card">
        <h3>Suosituin mökki: <?=htmlspecialchars($top_cabin['name'])?></h3>
        <p>Varauksia: <?=intval($top_cabin['booking_count'])?></p>
        <p>Hinta/yö: €<?=number_format($top_cabin['price_per_night'],2)?></p>
    </div>
    <?php endif; ?>

    <?php if ($cabins): ?>
    <table class="cabin-table">
        <thead>
            <tr>
                <th>Nimi</th>
                <th>Hinta/yö</th>
                <th>Varauksia</th>
                <th>Arvostelu</th>
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
    <?php if($cabin['review_count'] > 0): ?>
        <?=number_format($cabin['avg_rating'],1)?> / 5 (<?=intval($cabin['review_count'])?> arv.)
    <?php else: ?>
        Ei arvosteluja
    <?php endif; ?>
  </td>
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

<!-- Viimeisimmät varaukset -->
<section class="dashboard-section">
    <h2>Viimeisimmät varaukset</h2>
    <?php if ($bookings): ?>
    <table class="booking-table">
        <thead>
            <tr>
                <th>Mökki</th>
                <th>Asiakas</th>
                <th>Email</th>
                <th>Päivämäärät</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($bookings as $booking): ?>
            <tr>
                <td><?=htmlspecialchars($booking['cabin_name'])?></td>
                <td><?=htmlspecialchars($booking['customer_name'])?></td>
                <td><?=htmlspecialchars($booking['customer_email'])?></td>
                <td><?=htmlspecialchars($booking['start_date'])?> - <?=htmlspecialchars($booking['end_date'])?></td>
                <td><?=htmlspecialchars($booking['status'])?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php else: ?>
        <p>Ei uusia varauksia.</p>
    <?php endif; ?>
</section>

<!-- Omien varattujen mökkien lista -->
<section class="dashboard-section">
    <h2>Omat varatut mökit</h2>
    <?php if ($my_bookings): ?>
    <table class="booking-table">
        <thead>
            <tr>
                <th>Mökki</th>
                <th>Päivämäärät</th>
                <th>Vieraat</th>
                <th>Status</th>
                <th>Arvostele</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($my_bookings as $booking): ?>
            <tr>
                <td><?=htmlspecialchars($booking['cabin_name'])?></td>
                <td><?=htmlspecialchars($booking['start_date'])?> - <?=htmlspecialchars($booking['end_date'])?></td>
                <td><?=intval($booking['guests'])?></td>
                <td><?=htmlspecialchars($booking['status'])?></td>
                <td>
                    <a href="add_review.php?booking_id=<?= $booking['id'] ?>" class="btn">Jätä arvostelu</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php else: ?>
        <p>Sinulla ei ole vielä varattuja mökkejä.</p>
    <?php endif; ?>
</section>

</main>
</body>
</html>
