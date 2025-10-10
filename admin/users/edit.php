<?php
require '../includes/header.php';
$id = intval($_GET['id'] ?? 0);
$stmt = $pdo->prepare('SELECT * FROM users WHERE id = :id');
$stmt->execute(['id'=>$id]);
$u = $stmt->fetch();
if (!$u) { echo '<div class="alert alert-danger">Käyttäjää ei löydy.</div>'; require_once __DIR__ . '/../includes/footer.php'; exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $role = $_POST['role'] ?? 'user';
    $balance = floatval($_POST['balance'] ?? 0);

    $stmt = $pdo->prepare('UPDATE users SET email=:email, phone=:phone, role=:role, balance=:balance WHERE id=:id');
    $stmt->execute(['email'=>$email,'phone'=>$phone,'role'=>$role,'balance'=>$balance,'id'=>$id]);
    header('Location: view.php?id='.$id);
    exit;
}
?>
<h1>Muokkaa käyttäjää</h1>
<form method="post">
  <div class="mb-3"><label class="form-label">Sähköposti</label><input name="email" class="form-control" value="<?php echo htmlspecialchars($u['email']); ?>"></div>
  <div class="mb-3"><label class="form-label">Puhelin</label><input name="phone" class="form-control" value="<?php echo htmlspecialchars($u['phone']); ?>"></div>
  <div class="mb-3"><label class="form-label">Rooli</label><select name="role" class="form-select"><option value="user" <?php echo $u['role']=='user' ? 'selected' : ''; ?>>User</option><option value="admin" <?php echo $u['role']=='admin' ? 'selected' : ''; ?>>Admin</option></select></div>
  <div class="mb-3"><label class="form-label">Saldo</label><input name="balance" type="number" step="0.01" class="form-control" value="<?php echo htmlspecialchars($u['balance']); ?>"></div>
  <button class="btn btn-primary">Tallenna</button>
  <a class="btn btn-secondary" href="view.php?id=<?php echo $u['id']; ?>">Peruuta</a>
</form>

