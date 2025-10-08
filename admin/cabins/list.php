<?php
require_once __DIR__ . '/../includes/header.php';

// Fetch cabins
$stmt = $pdo->query('SELECT * FROM cabins ORDER BY id DESC');
$cabins = $stmt->fetchAll();
?>
<h1>Mökit</h1>
<p><a class="btn btn-success" href="add.php">Lisää uusi mökki</a></p>
<div class="table-responsive">
<table class="table table-striped">
<thead><tr><th>ID</th><th>Nimi</th><th>Sijainti</th><th>Hinta/vrk</th><th>Saatavuus</th><th>Toiminnot</th></tr></thead>
<tbody>
<?php foreach($cabins as $c): ?>
<tr>
  <td><?php echo htmlspecialchars($c['id']); ?></td>
  <td><?php echo htmlspecialchars($c['name']); ?></td>
  <td><?php echo htmlspecialchars($c['location']); ?></td>
  <td>€<?php echo number_format((float)$c['price_per_night'],2); ?></td>
  <td><?php echo $c['availability'] ? 'Näkyvissä' : 'Piilotettu'; ?></td>
  <td>
    <a class="btn btn-sm btn-primary" href="edit.php?id=<?php echo $c['id']; ?>">Muokkaa</a>
    <a class="btn btn-sm btn-danger" href="delete.php?id=<?php echo $c['id']; ?>" onclick="return confirm('Poistetaanko mökki?')">Poista</a>
  </td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
