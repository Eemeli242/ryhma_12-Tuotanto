<?php
require 'config.php';
include 'header.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$id = (int)($_GET['id'] ?? 0);
if (!$id) {
    header('Location: index.php');
    exit;
}

// Hae mökki
$stmt = $pdo->prepare('SELECT * FROM cabins WHERE id = ?');
$stmt->execute([$id]);
$cabin = $stmt->fetch();
if (!$cabin) die('Mökkiä ei löytynyt.');

// Hae arvostelut
$stmt = $pdo->prepare("
    SELECT r.*, u.username 
    FROM reviews r 
    JOIN users u ON r.user_id = u.id 
    WHERE r.cabin_id = ? 
    ORDER BY r.created_at DESC
");
$stmt->execute([$cabin['id']]);
$reviews = $stmt->fetchAll();

// Laske keskiarvo
$stmt = $pdo->prepare("SELECT AVG(rating) AS avg_rating, COUNT(*) AS review_count FROM reviews WHERE cabin_id = ?");
$stmt->execute([$cabin['id']]);
$stats = $stmt->fetch();
$avg_rating = $stats['avg_rating'] ?? 0;
$review_count = $stats['review_count'] ?? 0;
?>

<!doctype html>
<html lang="fi">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title><?=htmlspecialchars($cabin['name'])?></title>
<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
.back-btn {
    display: table;
    margin-bottom: 20px; 
}
.cabin-card img {
    display: block;
    margin-top: 10px;
}

</style>
</head>

<body>
    
<main class="container">
<a href="all_cabins.php" class="btn back-btn">Takaisin</a>
<div class="cabin-card">
    <?php if (!empty($cabin['image'])): ?>
        <img src="<?=htmlspecialchars($cabin['image'])?>" alt="<?=htmlspecialchars($cabin['name'])?>">
    <?php endif; ?>
</div>

    <div class="cabin-info">
        <h1><?=htmlspecialchars($cabin['name'])?></h1>

        <p><strong>Arvostelut:</strong>
        <span class="stars">
            <?php $rounded = round($avg_rating); for($i=1;$i<=5;$i++){ echo ($i<=$rounded)?'★':'<span>★</span>'; } ?>
        </span> (<?=intval($review_count)?> arvostelua)
        </p>

        <p><?=nl2br(htmlspecialchars($cabin['description']))?></p>
        <p class="price">
            <strong class="price-label">Hinta / yö:</strong> 
            <span class="price-value">€<?=number_format($cabin['price_per_night'],2)?></span>
        </p>
        <p><strong>Maksimi vieraita:</strong> <?=intval($cabin['max_guests'])?></p>
        <p><strong>Sijainti:</strong> <?=htmlspecialchars($cabin['location'] ?? 'Ei määritelty')?></p>
    </div>

    <section class="booking">
        <h2>Varaa mökki</h2>
        <form id="bookingForm" action="book.php" method="post">
            <input type="hidden" name="cabin_id" value="<?=htmlspecialchars($cabin['id'])?>">
            <label>Etunimi & Sukunimi<br><input name="customer_name" required></label>
            <label>Sähköposti<br><input type="email" name="customer_email" required></label>

            <div class="date-range-wrapper">
                <label>Alkaen<br><input type="text" id="start_date" name="start_date" placeholder="Valitse aloituspäivä" required></label>
                <label>Päättyen<br><input type="text" id="end_date" name="end_date" placeholder="Valitse päättymispäivä" required></label>
            </div>

            <label>Vieraiden määrä<br>
                <input type="number" name="guests" min="1" max="<?=intval($cabin['max_guests'])?>" value="1" required>
            </label>
            <button type="submit">Varaa</button>
        </form>
    </section>

    <section class="reviews">
        <h2>Asiakkaiden arvostelut</h2>
        <?php if ($reviews): ?>
            <?php foreach ($reviews as $r): ?>
                <div class="review">
                    <div>
                        <span class="review-user"><?=htmlspecialchars(substr($r['username'],0,6))?></span>
                        <span class="review-date">(<?=htmlspecialchars($r['created_at'])?>)</span>
                    </div>
                    <div class="stars">
                        <?php for($i=1;$i<=5;$i++): ?>
                            <?= $i <= $r['rating'] ? '★' : '<span>★</span>' ?>
                        <?php endfor; ?>
                    </div>
                    <p><?=nl2br(htmlspecialchars($r['comment']))?></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Ei arvosteluja vielä.</p>
        <?php endif; ?>
    </section>
</div>
</main>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
const startPicker = flatpickr("#start_date", {
    minDate: "today",
    dateFormat: "Y-m-d",
    onChange: function(selectedDates) {
        endPicker.set('minDate', selectedDates[0] || "today");
    }
});
const endPicker = flatpickr("#end_date", {
    minDate: "today",
    dateFormat: "Y-m-d"
});
</script>

<?php include 'footer.php'; ?>
