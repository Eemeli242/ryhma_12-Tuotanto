<?php
require 'config.php';
include 'header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: kirjaudu_rekisteroidy.php");
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

        // Päivitä tiedot tietokantaan
        if (!$message) {
            $stmt = $pdo->prepare("
                UPDATE users SET username = ?, email = ?, phone = ?, profile_image = ? WHERE id = ?
            ");
            $stmt->execute([$username, $email, $phone, $profileImage, $_SESSION['user_id']]);
            $message = "Profiili päivitetty!";
            // Päivitä $user muuttuja näkymää varten
            $user['username'] = $username;
            $user['email'] = $email;
            $user['phone'] = $phone;
            $user['profile_image'] = $profileImage;
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
<main class="admin-container">
  <h1>Muokkaa profiilia</h1>
  <p><?=htmlspecialchars($message)?></p>
  <p><strong>Saldo:</strong> €<?=number_format($user['balance'] ?? 0, 2)?></p>

  <form method="post" enctype="multipart/form-data" class="admin-form">
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
            <img src="<?=htmlspecialchars($user['profile_image'])?>" alt="Profiili" style="width:80px;height:80px;border-radius:50%;display:block;margin-bottom:10px;">
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
