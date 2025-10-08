<?php
session_start();
require 'config.php';

$message = '';
$view = $_GET['view'] ?? 'login';
$maxFileSize = 2 * 1024 * 1024; // 2MB

// LOGIN
if ($view === 'login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username && $password) {
        $stmt = $pdo->prepare("SELECT id, password FROM users WHERE username=?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            header("Location: add_cabin.php");
            exit;
        }
    }
    $message = "Virheellinen käyttäjätunnus tai salasana";
}

// REGISTER
if ($view === 'register' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $password_raw = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $profile_image = null;

    if (!$username || !$email || !$phone || !$password_raw || !$confirm_password) {
        $message = "Täytä kaikki kentät.";
    } 
    // Käyttäjänimi: vain kirjaimia
    elseif (!preg_match('/^[a-zA-Z]+$/', $username)) {
        $message = "Käyttäjänimi voi sisältää vain kirjaimia (A-Z, a-z).";
    } 
    // Puhelinnumero: vain numerot
    elseif (!preg_match('/^[0-9]+$/', $phone)) {
        $message = "Puhelinnumero voi sisältää vain numeroita.";
    } 
    // Salasanan pituus
    elseif (strlen($password_raw) < 8) {
        $message = "Salasanan tulee olla vähintään 8 merkkiä pitkä.";
    } 
    // Salasanat täsmäävät
    elseif ($password_raw !== $confirm_password) {
        $message = "Salasanat eivät täsmää.";
    } 
    else {
        // Profiilikuva-käsittely säilyy ennallaan
        if (!empty($_FILES['profile_image']['tmp_name']) && $_FILES['profile_image']['error'] === 0) {
            $fileTmp = $_FILES['profile_image']['tmp_name'];
            $fileName = preg_replace("/[^a-zA-Z0-9_\.-]/", "_", basename($_FILES['profile_image']['name']));
            $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            $allowed = ['jpg','jpeg','png','gif'];

            if (!in_array($fileExt, $allowed)) {
                $message = "Sallitut kuvatyypit: JPG, PNG, GIF.";
            } elseif (filesize($fileTmp) > $maxFileSize) {
                $message = "Kuvan koko saa olla enintään 2MB.";
            } elseif (getimagesize($fileTmp) === false) {
                $message = "Valittu tiedosto ei ole kuva.";
            } else {
                $targetDir = "uploads/";
                if (!is_dir($targetDir)) mkdir($targetDir, 0755, true);
                $targetFile = $targetDir . time() . "_" . $fileName;
                if (!move_uploaded_file($fileTmp, $targetFile)) {
                    $message = "Kuvan tallennus epäonnistui.";
                } else {
                    $profile_image = $targetFile;
                }
            }
        }

        if (!$message) {
            $password = password_hash($password_raw, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (username, email, phone, password, profile_image) VALUES (?, ?, ?, ?, ?)");
            try {
                $stmt->execute([$username, $email, $phone, $password, $profile_image]);
                $_SESSION['user_id'] = $pdo->lastInsertId();
                header("Location: add_cabin.php");
                exit;
            } catch (PDOException $e) {
                $message = "Virhe: käyttäjänimi, sähköposti tai puhelin on jo käytössä.";
            }
        }
    }
}

?>
<!doctype html>
<html lang="fi">
<head>
<meta charset="utf-8">
<title><?= $view === 'login' ? 'Kirjaudu sisään' : 'Rekisteröidy' ?></title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="auth-wrapper">
  <div class="auth-card">
    <h1 class="auth-title">Lomamökit</h1>
    <h2 class="auth-subtitle"><?= $view === 'login' ? 'Kirjaudu sisään' : 'Luo uusi käyttäjä' ?></h2>

    <?php if ($message): ?>
      <p class="auth-message"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <?php if ($view === 'login'): ?>
    <form method="post" class="auth-form">
      <label>Käyttäjätunnus
        <input type="text" name="username" required>
      </label>
      <label>Salasana
        <input type="password" name="password" required>
      </label>
      <button type="submit">Kirjaudu</button>
    </form>
    <div class="auth-links">
      <a href="#">Unohditko salasanasi?</a><br>
      <a href="?view=register">Uusi käyttäjä? Rekisteröidy</a>
    </div>
    <?php endif; ?>

    <?php if ($view === 'register'): ?>
    <form method="post" enctype="multipart/form-data" class="auth-form">
      <label>Käyttäjätunnus
        <input type="text" name="username" required>
      </label>
      <label>Sähköposti
        <input type="email" name="email" required>
      </label>
      <label>Puhelinnumero
        <input type="tel" name="phone" required>
      </label>
      <label>Salasana
        <input type="password" name="password" required>
      </label>
      <label>Vahvista salasana
        <input type="password" name="confirm_password" required>
      </label>
      <label>Profiilikuva (valinnainen)
        <input type="file" name="profile_image" accept="image/*">
      </label>
      <button type="submit">Rekisteröidy</button>
    </form>
    <div class="auth-links">
      <a href="?view=login">Onko sinulla jo tili? Kirjaudu</a>
    </div>
    <?php endif; ?>

  </div>
</div>
</body>
</html>
<?php include 'footer.php'; ?>
