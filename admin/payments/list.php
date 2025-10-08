<?php
require_once __DIR__ . '/../includes/header.php';

$stmt = $pdo->query('SELECT b.id, b.paid, b.status, b.created_at, c.name AS cabin_name, b.customer_name FROM bookings b JOIN cabins c ON b.cabin_id=c.id ORDER BY b.created_at DESC');
$rows = $stmt->fetchAll();
?>
<h1>Maksut / varaukset</h1>
<div class="table-responsive">
<table class="table table-striped">
<thead><tr><th>ID</th><th>Mökki</th><th>Asiakas</th><th>Summa</th><th>Tila</th><th>Päivä</th></tr></thead>
<tbody>
<?php foreach($rows as $r): ?>
<tr>
  <td><?php echo htmlspecialchars($r['id']); ?></td>
  <td><?php echo htmlspecialchars($r['cabin_name']); ?></td>
  <td><?php echo htmlspecialchars($r['customer_name']); ?></td>
  <td>€<?php echo number_format((float)$r['paid'],2); ?></td>
  <td><?php echo htmlspecialchars($r['status']); ?></td>
  <td><?php echo htmlspecialchars($r['created_at']); ?></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
