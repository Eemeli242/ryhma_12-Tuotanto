<?php
require 'config.php';
include 'header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login_register.php");
    exit;
}

$message = '';

// Hae käyttäjän tiedot
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $currentPassword = $_POST['current_password'] ?? '';
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $profileImage = $user['profile_image'] ?? null;

    // Tarkista salasana
    if (!password_verify($currentPassword, $user['password'])) {
        $message = "Nykyinen salasana on väärin. Profiilia ei päivitetty.";
    } else {
        // Käsittele profiilikuvan upload
        if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === 0) {
            $targetDir = "uploads/";
            if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

            $fileTmp  = $_FILES["profile_image"]["tmp_name"];
            $fileName = basename($_FILES["profile_image"]["name"]);
            $fileExt  = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            $allowed  = ['jpg','jpeg','png'];

            $check = getimagesize($fileTmp);
            if($check === false) {
                $message = "Tiedosto ei ole kuva.";
            } elseif(!in_array($fileExt, $allowed)) {
                $message = "Vain JPG ja PNG kuvat sallittu.";
            } else {
                $targetFile = $targetDir . time() . "_" . $fileName;
                if (move_uploaded_file($fileTmp, $targetFile)) {
                    $profileImage = $targetFile;
                } else {
                    $message = "Profiilikuvan lataus epäonnistui.";
                }
            }
        }

        // Päivitä tiedot tietokantaan vain jos ei virheitä
        if (!$message) {
            $stmt = $pdo->prepare("
                UPDATE users SET username = ?, email = ?, phone = ?, profile_image = ? WHERE id = ?
            ");
            $stmt->execute([$username, $email, $phone, $profileImage, $_SESSION['user_id']]);
            
            // Päivitys onnistui → ohjataan dashboardille
            header("Location: dashboard.php");
            exit;
        }
    }
}
?>

<!doctype html>
<html lang="fi">
<head>
<meta charset="utf-8">
<title>Muokkaa profiilia</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<main class="add_cabin">
  <h1>Muokkaa profiilia</h1>

  <!-- Näytetään virheviesti jos olemassa -->
  <?php if($message): ?>
    <p style="color:#d33; font-weight:500; text-align:center; margin-bottom:15px;">
        <?=htmlspecialchars($message)?>
    </p>
  <?php endif; ?>

  <form method="post" enctype="multipart/form-data">
      <label>Nykyinen salasana
        <input type="password" name="current_password" required>
      </label>
      <label>Käyttäjänimi
        <input type="text" name="username" value="<?=htmlspecialchars($user['username'])?>" required>
      </label>
      <label>Sähköposti
        <input type="email" name="email" value="<?=htmlspecialchars($user['email'])?>" required>
      </label>
      <label>Puhelin
        <input type="tel" name="phone" value="<?=htmlspecialchars($user['phone'])?>" required>
      </label>
      <label>Profiilikuva
        <?php if (!empty($user['profile_image'])): ?>
            <img src="<?=htmlspecialchars($user['profile_image'])?>" alt="Profiili" style="width:120px;height:120px;border-radius:50%;object-fit:cover;margin-right:8px;">
        <?php endif; ?>
        <input type="file" name="profile_image" accept="image/*">
      </label>
      <button type="submit">Tallenna muutokset</button>
  </form>
  <a href="dashboard.php" class="btn" style="margin-top:15px;">Takaisin dashboardille</a>
</main>
</body>
</html>
<?php include 'footer.php'; ?>
