<?php
session_start();
require 'config.php';

$message = '';
$view = $_GET['view'] ?? 'login';

// LOGIN
if ($view === 'login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username=?");
    $stmt->execute([$_POST['username']]);
    $user = $stmt->fetch();

    if ($user && password_verify($_POST['password'], $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        header("Location: add_cabin.php");
        exit;
    } else {
        $message = "Virheellinen käyttäjätunnus tai salasana";
    }
}

// REGISTER
if ($view === 'register' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $password_raw = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $profile_image = null;

    if ($username && $email && $phone && $password_raw && $confirm_password) {
        if ($password_raw !== $confirm_password) {
            $message = "Salasanat eivät täsmää.";
        } else {
            // Profiilikuva
            if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === 0) {
                $fileTmp = $_FILES['profile_image']['tmp_name'];
                $fileName = basename($_FILES['profile_image']['name']);
                $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                $allowed = ['jpg','jpeg','png','gif'];

                $check = getimagesize($fileTmp);
                if ($check === false) {
                    $message = "Valittu tiedosto ei ole kuva.";
                } elseif (!in_array($fileExt, $allowed)) {
                    $message = "Sallitut kuvatyypit: JPG, PNG, GIF.";
                } else {
                    $targetDir = "uploads/";
                    if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
                    $targetFile = $targetDir . time() . "_" . $fileName;
                    if (move_uploaded_file($fileTmp, $targetFile)) {
                        $profile_image = $targetFile;
                    } else {
                        $message = "Kuvan tallennus epäonnistui.";
                    }
                }
            }

            if (!$message) {
                $password = password_hash($password_raw, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO users (username, email, phone, password, profile_image) VALUES (?, ?, ?, ?, ?)");
                try {
                    $stmt->execute([$username, $email, $phone, $password, $profile_image]);

                    // Automaattinen kirjautuminen
                    $user_id = $pdo->lastInsertId();
                    $_SESSION['user_id'] = $user_id;

                    // Ohjataan suoraan add_cabin.php VAIHDA TÄMÄ!!!!!!!!!!!!!!!!!
                  
                    header("Location: index.php");
                    exit;

                } catch (PDOException $e) {
                    $message = "Virhe: käyttäjänimi, sähköposti tai puhelin on jo käytössä.";
                }
            }
        }
    } else {
        $message = "Täytä kaikki kentät.";
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
