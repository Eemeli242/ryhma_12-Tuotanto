<?php
require 'config.php';
session_start();

// Tarkista kirjautuminen
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Hae varaus
$booking_id = (int)($_GET['booking_id'] ?? 0);
if (!$booking_id) die('Väärä varaus.');

$stmt = $pdo->prepare("
    SELECT b.*, c.name AS cabin_name 
    FROM bookings b 
    JOIN cabins c ON b.cabin_id = c.id 
    WHERE b.id = ? AND b.customer_id = ?
");
$stmt->execute([$booking_id, $_SESSION['user_id']]);
$booking = $stmt->fetch();
if (!$booking) die('Varausta ei löytynyt.');

// Tarkista onko arvostelu jo olemassa
$stmt = $pdo->prepare("SELECT COUNT(*) FROM reviews WHERE booking_id = ?");
$stmt->execute([$booking_id]);
if ($stmt->fetchColumn() > 0) {
    die('Olet jo arvostellut tämän varauksen.');
}

$error = '';
$rating = '';
$comment = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rating = (int)($_POST['rating'] ?? 0);
    $comment = trim($_POST['comment'] ?? '');

    if ($rating < 1 || $rating > 5) {
        $error = "Arvioinnin tulee olla 1–5 tähteä.";
    } else {
        $stmt = $pdo->prepare("
            INSERT INTO reviews (booking_id, cabin_id, user_id, rating, comment) 
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([$booking_id, $booking['cabin_id'], $_SESSION['user_id'], $rating, $comment]);
        header("Location: dashboard.php?message=" . urlencode("Arvostelu lähetetty!"));
        exit;
    }
}
?>

<!doctype html>
<html lang="fi">
<head>
<meta charset="utf-8">
<title>Jätä arvostelu - <?=htmlspecialchars($booking['cabin_name'])?></title>
<link rel="stylesheet" href="style.css">
<style>
body { font-family: Arial, sans-serif; }
main { max-width: 500px; margin: 40px auto; padding: 20px; }
form { display: flex; flex-direction: column; gap: 15px; }
input, textarea, select { padding: 10px; border: 1px solid #ccc; border-radius: 6px; width: 100%; }
button { padding: 10px; background: #116d38; color: #fff; border: none; border-radius: 6px; cursor: pointer; }
.error { color: red; }
</style>
</head>
<body>
<main>
    <h1>Jätä arvostelu: <?=htmlspecialchars($booking['cabin_name'])?></h1>

    <?php if ($error): ?>
        <p class="error"><?=htmlspecialchars($error)?></p>
    <?php endif; ?>

    <form method="post">
        <label>Arvio (1–5)
            <select name="rating" required>
                <option value="">Valitse</option>
                <?php for ($i=1; $i<=5; $i++): ?>
                    <option value="<?=$i?>" <?=($rating == $i) ? 'selected' : ''?>><?=$i?> tähti<?=$i>1?'ä':''?></option>
                <?php endfor; ?>
            </select>
        </label>

        <label>Kommentti
            <textarea name="comment" rows="4" placeholder="Kirjoita arvostelu..."><?=htmlspecialchars($comment)?></textarea>
        </label>

        <button type="submit">Lähetä arvostelu</button>
    </form>
</main>
</body>
</html>
