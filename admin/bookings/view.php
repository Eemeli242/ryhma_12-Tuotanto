<?php
require_once __DIR__ . '/../includes/header.php';
$id = intval($_GET['id'] ?? 0);
$stmt = $pdo->prepare('SELECT b.*, c.name AS cabin_name FROM bookings b JOIN cabins c ON b.cabin_id = c.id WHERE b.id = :id');
$stmt->execute(['id'=>$id]);
$b = $stmt->fetch();
if (!$b) { echo '<div class="alert alert-danger">Varausta ei löydy.</div>'; require_once __DIR__ . '/../includes/footer.php'; exit; }

// change status form
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['status'])) {
    $s = $_POST['status'];
    $stmt = $pdo->prepare('UPDATE bookings SET status = :s WHERE id = :id');
    $stmt->execute(['s'=>$s,'id'=>$id]);
    header('Location: view.php?id='.$id);
    exit;
}
?>
<h1>Varaus #<?php echo htmlspecialchars($b['id']); ?></h1>
<ul class="list-group mb-3">
  <li class="list-group-item"><strong>Mökki:</strong> <?php echo htmlspecialchars($b['cabin_name']); ?></li>
  <li class="list-group-item"><strong>Asiakas:</strong> <?php echo htmlspecialchars($b['customer_name']); ?> (<?php echo htmlspecialchars($b['customer_email']); ?>)</li>
  <li class="list-group-item"><strong>Ajankohta:</strong> <?php echo htmlspecialchars($b['start_date']); ?> → <?php echo htmlspecialchars($b['end_date']); ?></li>
  <li class="list-group-item"><strong>Vieraiden määrä:</strong> <?php echo htmlspecialchars($b['guests']); ?></li>
  <li class="list-group-item"><strong>Maksettu:</strong> €<?php echo number_format((float)$b['paid'],2); ?></li>
  <li class="list-group-item"><strong>Tila:</strong> <?php echo htmlspecialchars($b['status']); ?></li>
</ul>

<form method="post" class="mb-3">
  <label>Vaihda tila</label>
  <select name="status" class="form-select mb-2">
    <option value="pending" <?php echo $b['status']=='pending' ? 'selected' : ''; ?>>pending</option>
    <option value="paid" <?php echo $b['status']=='paid' ? 'selected' : ''; ?>>paid</option>
    <option value="cancelled" <?php echo $b['status']=='cancelled' ? 'selected' : ''; ?>>cancelled</option>
  </select>
  <button class="btn btn-primary">Päivitä</button>
  <a class="btn btn-secondary" href="list.php">Takaisin</a>
</form>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
