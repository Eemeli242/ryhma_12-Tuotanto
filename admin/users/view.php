<?php
require_once __DIR__ . '/../includes/header.php';
$id = intval($_GET['id'] ?? 0);
$stmt = $pdo->prepare('SELECT * FROM users WHERE id = :id');
$stmt->execute(['id'=>$id]);
$u = $stmt->fetch();
if (!$u) { echo '<div class="alert alert-danger">Käyttäjää ei löydy.</div>'; require_once __DIR__ . '/../includes/footer.php'; exit; }
?>
<h1>Käyttäjä: <?php echo htmlspecialchars($u['username']); ?></h1>
<ul class="list-group">
  <li class="list-group-item"><strong>Sähköposti:</strong> <?php echo htmlspecialchars($u['email']); ?></li>
  <li class="list-group-item"><strong>Puhelin:</strong> <?php echo htmlspecialchars($u['phone']); ?></li>
  <li class="list-group-item"><strong>Rooli:</strong> <?php echo htmlspecialchars($u['role']); ?></li>
  <li class="list-group-item"><strong>Saldo:</strong> €<?php echo number_format((float)$u['balance'],2); ?></li>
</ul>
<p class="mt-3"><a class="btn btn-secondary" href="list.php">Takaisin</a></p>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
