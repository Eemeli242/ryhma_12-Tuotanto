<?php
require '../includes/header.php';
$stmt = $pdo->query('SELECT id, username, email, phone, role, created_at FROM users ORDER BY id DESC');
$users = $stmt->fetchAll();
?>
<h1>Käyttäjät</h1>
<div class="table-responsive">
<table class="table table-striped">
<thead><tr><th>ID</th><th>Käyttäjä</th><th>Sähköposti</th><th>Puhelin</th><th>Rooli</th><th>Rekisteröity</th><th>Toiminnot</th></tr></thead>
<tbody>
<?php foreach($users as $u): ?>
<tr>
  <td><?php echo htmlspecialchars($u['id']); ?></td>
  <td><?php echo htmlspecialchars($u['username']); ?></td>
  <td><?php echo htmlspecialchars($u['email']); ?></td>
  <td><?php echo htmlspecialchars($u['phone']); ?></td>
  <td><?php echo htmlspecialchars($u['role']); ?></td>
  <td><?php echo htmlspecialchars($u['created_at']); ?></td>
  <td>
    <a class="btn btn-sm btn-primary" href="view.php?id=<?php echo $u['id']; ?>">Näytä</a>
    <a class="btn btn-sm btn-warning" href="edit.php?id=<?php echo $u['id']; ?>">Muokkaa</a>
    <a class="btn btn-sm btn-danger" href="deactivate.php?id=<?php echo $u['id']; ?>" onclick="return confirm('Poistetaanko käyttäjä?')">Poista</a>
  </td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</div>
