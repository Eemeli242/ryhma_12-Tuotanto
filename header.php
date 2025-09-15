<?php
session_start();
require 'config.php';

$user = null;
$user_balance = 0;
if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();
    $user_balance = $user['balance'] ?? 0;
}
?>
<header>
  <div class="container header-flex" style="display:flex;justify-content:space-between;align-items:center;padding:20px 0;">
    <h1><a href="index.php" style="color:#fff;text-decoration:none;">Lomamökit</a></h1>
    <nav style="display:flex;align-items:center;gap:15px;">
      <a href="index.php" style="color:#fff;text-decoration:none;font-weight:600;">Etusivu</a>
      <a href="kaikki_mokit.php" style="color:#fff;text-decoration:none;font-weight:600;">Kaikki mökit</a>
      <a href="<?= isset($_SESSION['user_id']) ? 'uusi_mokki.php' : 'kirjaudu_rekisteroidy.php'; ?>" class="btn" style="color:#fff;background:#007bff;padding:8px 12px;border-radius:6px;">Ilmoita oma mökkisi</a>

      <?php if ($user): ?>
      <div class="profile-dropdown" style="position:relative;">
        <a href="#" style="display:flex;align-items:center;color:#fff;text-decoration:none;">
          <img src="<?=htmlspecialchars($user['profile_image'] ?? 'default-avatar.png')?>" alt="Profiili" class="profile-pic" style="width:40px;height:40px;border-radius:50%;object-fit:cover;margin-right:8px;">
          <?=htmlspecialchars($user['username'])?>
          <span style="margin-left:8px;font-weight:600;">(Saldo: €<?=number_format($user_balance, 2)?>)</span>
        </a>
        <div class="profile-dropdown-content" style="display:none;position:absolute;right:0;background:#fff;min-width:180px;border-radius:8px;box-shadow:0 4px 12px rgba(0,0,0,0.15);overflow:hidden;z-index:1;">
          <a href="dashboard.php" style="display:block;padding:10px 16px;color:#333;text-decoration:none;">Profiili</a>
          <a href="edit_profile.php" style="display:block;padding:10px 16px;color:#333;text-decoration:none;">Muokkaa profiilia</a>
          <a href="logout.php" style="display:block;padding:10px 16px;color:#333;text-decoration:none;">Kirjaudu ulos</a>
        </div>
      </div>
      <?php else: ?>
        <a href="kirjaudu_rekisteroidy.php" style="color:#fff;text-decoration:none;font-weight:600;">Kirjaudu / Rekisteröidy</a>
      <?php endif; ?>
    </nav>
  </div>
</header>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const dropdown = document.querySelector('.profile-dropdown');
    if (!dropdown) return;
    const content = dropdown.querySelector('.profile-dropdown-content');
    dropdown.addEventListener('mouseenter', () => content.style.display = 'block');
    dropdown.addEventListener('mouseleave', () => content.style.display = 'none');
});
</script>
