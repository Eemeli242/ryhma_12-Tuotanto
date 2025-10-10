<?php
require 'config.php';
include 'header.php';
$id = (int)($_GET['id'] ?? 0);
if (!$id) {
    header('Location: index.php');
    exit;
}

$stmt = $pdo->prepare('SELECT b.*, c.name as cabin_name FROM bookings b JOIN cabins c ON b.cabin_id = c.id WHERE b.id = ?');
$stmt->execute([$id]);
$booking = $stmt->fetch();
?>
<!doctype html>
<html lang="fi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Varaus onnistui</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<main class="container">
  <div class="success-card">
    <h1>Varaus vastaanotettu!</h1>
    <p>Varaus #<?=htmlspecialchars($booking['id'])?> mökkiin <?=htmlspecialchars($booking['cabin_name'])?>.</p>
    <p>Saapuu: <?=htmlspecialchars($booking['start_date'])?> — Lähtee: <?=htmlspecialchars($booking['end_date'])?></p>
    <p>Vahvistus lähetetty sähköpostiin: <?=htmlspecialchars($booking['customer_email'])?></p>
    <a href="index.php">Takaisin mökkilistaukseen</a>
  </div>
</main>
</html>
<?php include 'footer.php'; ?>