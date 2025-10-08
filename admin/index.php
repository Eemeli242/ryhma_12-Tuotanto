<?php
require_once __DIR__ . '/includes/header.php';

// Quick stats
$totalCabins = $pdo->query('SELECT COUNT(*) FROM cabins')->fetchColumn();
$totalBookings = $pdo->query('SELECT COUNT(*) FROM bookings')->fetchColumn();
$totalUsers = $pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
$income = $pdo->query('SELECT IFNULL(SUM(paid),0) FROM bookings')->fetchColumn();
?>
<h1>Dashboard</h1>
<div class="row">
  <div class="col-md-3">
    <div class="card mb-3"><div class="card-body">
      <h5 class="card-title">Mökit</h5>
      <p class="card-text"><?php echo (int)$totalCabins; ?></p>
    </div></div>
  </div>
  <div class="col-md-3">
    <div class="card mb-3"><div class="card-body">
      <h5 class="card-title">Varaukset</h5>
      <p class="card-text"><?php echo (int)$totalBookings; ?></p>
    </div></div>
  </div>
  <div class="col-md-3">
    <div class="card mb-3"><div class="card-body">
      <h5 class="card-title">Käyttäjät</h5>
      <p class="card-text"><?php echo (int)$totalUsers; ?></p>
    </div></div>
  </div>
  <div class="col-md-3">
    <div class="card mb-3"><div class="card-body">
      <h5 class="card-title">Tulot</h5>
      <p class="card-text">€ <?php echo number_format((float)$income,2); ?></p>
    </div></div>
  </div>
</div>

<hr>
<h3>Viimeisimmät varaukset</h3>
<div class="table-responsive">
<table class="table table-striped">
  <thead><tr><th>#</th><th>Mökki</th><th>Asiakas</th><th>Ajankohta</th><th>Summa</th><th>Tila</th></tr></thead>
  <tbody>
  <?php
  $stmt = $pdo->query('SELECT b.*, c.name AS cabin_name FROM bookings b JOIN cabins c ON b.cabin_id = c.id ORDER BY created_at DESC LIMIT 10');
  while ($row = $stmt->fetch()) {
      echo '<tr>';
      echo '<td>'.htmlspecialchars($row['id']).'</td>';
      echo '<td>'.htmlspecialchars($row['cabin_name']).'</td>';
      echo '<td>'.htmlspecialchars($row['customer_name']).'</td>';
      echo '<td>'.htmlspecialchars($row['start_date']).' → '.htmlspecialchars($row['end_date']).'</td>';
      echo '<td>€'.number_format((float)$row['paid'],2).'</td>';
      echo '<td>'.htmlspecialchars($row['status']).'</td>';
      echo '</tr>';
  }
  ?>
  </tbody>
</table>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
