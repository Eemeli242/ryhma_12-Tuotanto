<?php
require '../includes/header.php';

$where = '1=1';
$params = [];
if (!empty($_GET['status'])) {
    $where .= ' AND b.status = :status';
    $params['status'] = $_GET['status'];
}

$sql = 'SELECT b.*, c.name AS cabin_name FROM bookings b JOIN cabins c ON b.cabin_id = c.id WHERE ' . $where . ' ORDER BY b.created_at DESC';
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$rows = $stmt->fetchAll();
?>
<h1>Varaukset</h1>
<form class="mb-3">
  <label>Tila</label>
  <select name="status" class="form-select" onchange="this.form.submit()">
    <option value="">Kaikki</option>
    <option value="pending" <?php echo (isset($_GET['status']) && $_GET['status']=='pending') ? 'selected' : ''; ?>>Pending</option>
    <option value="paid" <?php echo (isset($_GET['status']) && $_GET['status']=='paid') ? 'selected' : ''; ?>>Paid</option>
    <option value="cancelled" <?php echo (isset($_GET['status']) && $_GET['status']=='cancelled') ? 'selected' : ''; ?>>Cancelled</option>
  </select>
</form>
<div class="table-responsive">
<table class="table table-striped">
<thead><tr><th>#</th><th>Mökki</th><th>Asiakas</th><th>Ajankohta</th><th>Summa</th><th>Tila</th><th>Toiminnot</th></tr></thead>
<tbody>
<?php foreach($rows as $r): ?>
<tr>
  <td><?php echo htmlspecialchars($r['id']); ?></td>
  <td><?php echo htmlspecialchars($r['cabin_name']); ?></td>
  <td><?php echo htmlspecialchars($r['customer_name']); ?><br><?php echo htmlspecialchars($r['customer_email']); ?></td>
  <td><?php echo htmlspecialchars($r['start_date']); ?> → <?php echo htmlspecialchars($r['end_date']); ?></td>
  <td>€<?php echo number_format((float)$r['paid'],2); ?></td>
  <td><?php echo htmlspecialchars($r['status']); ?></td>
  <td><a class="btn btn-sm btn-primary" href="view.php?id=<?php echo $r['id']; ?>">Näytä</a></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</div>
