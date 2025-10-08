<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login_register.php');
    exit;
}

// Hae käyttäjän tiedot
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

$message = '';
$messageColor = 'green';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $amount = floatval($_POST['amount'] ?? 0);
    if ($amount > 0) {
        $stmt = $pdo->prepare("UPDATE users SET balance = balance + ? WHERE id = ?");
        $stmt->execute([$amount, $_SESSION['user_id']]);
        $user['balance'] += $amount;
        $message = "Saldo päivitetty onnistuneesti!";
        $messageColor = 'green';
    } else {
        $message = "Anna positiivinen summa.";
        $messageColor = 'red';
    }
}
?>
<!doctype html>
<html lang="fi">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Lisää saldoa</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<main class="add_cabin">
    <h1>Lisää saldoa</h1>
    <?php if ($message): ?>
        <p style="color:<?=htmlspecialchars($messageColor)?>;"><?=htmlspecialchars($message)?></p>
    <?php endif; ?>
    
    <p><strong>Nykyinen saldo:</strong> €<?=number_format($user['balance'], 2)?></p>

    <form method="post">
        <label>Lisättävä summa (€):
            <input type="number" name="amount" step="0.01" min="0" required>
        </label>
        <button type="submit">Lisää saldoa</button>
    </form>

    <p><a href="dashboard.php" class="back-link">Takaisin dashboardille</a></p>
</main>
</body>
</html>
