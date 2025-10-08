<?php
require_once __DIR__ . '/../includes/header.php';

// (viimeisimmät 12 kuukautta)
$stmt = $pdo->query("SELECT DATE_FORMAT(created_at, '%Y-%m') AS ym, COUNT(*) AS cnt FROM bookings GROUP BY ym ORDER BY ym DESC LIMIT 12");
$rows = $stmt->fetchAll();
?>
<h1>Raportit</h1>
<h3>Varaukset per kuukausi (viimeiset 12)</h3>
<table class="table">
<thead><tr><th>Kuukausi</th><th>Määrä</th></tr></thead>
<tbody>
<?php foreach($rows as $r): ?>
<tr><td><?php echo htmlspecialchars($r['ym']); ?></td><td><?php echo htmlspecialchars($r['cnt']); ?></td></tr>
<?php endforeach; ?>
</tbody>
</table>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
