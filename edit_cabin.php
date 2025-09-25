<?php
require 'config.php';
include 'header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: kirjaudu_rekisteroidy.php");
    exit;
}

$id = (int)($_GET['id'] ?? 0);
if (!$id) die('Mökkiä ei löytynyt.');

// Hae mökki ja tarkista omistajuus
$stmt = $pdo->prepare("SELECT * FROM cabins WHERE id = ? AND owner_id = ?");
$stmt->execute([$id, $_SESSION['user_id']]);
$cabin = $stmt->fetch();
if (!$cabin) die('Et voi muokata tätä mökkiä.');

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = $_POST['price_per_night'];
    $maxGuests = $_POST['max_guests'];
    $location = trim($_POST['location']);
    $image = $cabin['image'];

    // Käsittele kuvan upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $targetDir = "uploads/";
        if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

        $fileTmp  = $_FILES["image"]["tmp_name"];
        $fileName = basename($_FILES["image"]["name"]);
        $fileExt  = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowed  = ['jpg','jpeg','png','gif'];

        $check = getimagesize($fileTmp);
        if($check !== false && in_array($fileExt, $allowed)) {
            $targetFile = $targetDir . time() . "_" . $fileName;
            if (move_uploaded_file($fileTmp, $targetFile)) {
                $image = $targetFile;
            } else {
                $message = "Kuvan lataus epäonnistui.";
            }
        } else {
            $message = "Vain JPGja PNG kuvat sallittu.";
        }
    }

    if (!$message) {
        $stmt = $pdo->prepare("
            UPDATE cabins
            SET name = ?, description = ?, price_per_night = ?, max_guests = ?, image = ?, location = ?
            WHERE id = ? AND owner_id = ?
        ");
        $stmt->execute([$name, $description, $price, $maxGuests, $image, $location, $id, $_SESSION['user_id']]);
        $message = "Mökki päivitetty!";
        // Päivitetään $cabin esikatselua varten
        $cabin = array_merge($cabin, [
            'name'=>$name,
            'description'=>$description,
            'price_per_night'=>$price,
            'max_guests'=>$maxGuests,
            'image'=>$image,
            'location'=>$location
        ]);
    }
}

// Lista Suomen kaupungeista
$locations = [
    'Helsinki','Espoo','Tampere','Vantaa','Oulu','Turku',
    'Jyväskylä','Lahti','Kuopio','Pori','Lappeenranta','Vaasa',
    'Seinäjoki','Rovaniemi','Kotka','Joensuu','Hämeenlinna','Kouvola',
    'Salo','Mikkeli','Hyvinkää','Nokia','Kajaani','Savonlinna',
    'Riihimäki','Kerava','Kemi','Kokkola','Loimaa','Raisio'
];

?>
<!doctype html>
<html lang="fi">
<head>
<meta charset="utf-8">
<title>Muokkaa mökkiä</title>
<link rel="stylesheet" href="style.css">
<style>
.edit-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 40px;
}
.cabin-preview img {
    width: 100%;
    max-width: 400px;
    height: auto;
    margin-bottom: 15px;
    border-radius: 10px;
}
.cabin-preview .cabin-info p {
    margin: 8px 0;
}
.admin-form input, .admin-form textarea, .admin-form select {
    width: 100%;
}
</style>
</head>
<body>
<main class="add_cabin">
  <h1>Muokkaa mökkiä</h1>
  <p><?=htmlspecialchars($message)?></p>

  <div class="edit-grid">
    <!-- Muokkauslomake -->
    <form method="post" enctype="multipart/form-data" class="admin-form">
        <label>Nimi
          <input type="text" name="name" value="<?=htmlspecialchars($cabin['name'])?>" required>
        </label>
        <label>Kuvaus
          <textarea name="description"><?=htmlspecialchars($cabin['description'])?></textarea>
        </label>
        <label>Hinta / yö
          <input type="number" step="0.01" name="price_per_night" value="<?=htmlspecialchars($cabin['price_per_night'])?>" required>
        </label>
        <label>Maksimi vieraita
          <input type="number" name="max_guests" value="<?=htmlspecialchars($cabin['max_guests'])?>" required>
        </label>
        <label>Kuva
          <?php if (!empty($cabin['image'])): ?>
              <img src="<?=htmlspecialchars($cabin['image'])?>" alt="Mökki" style="width:150px;height:auto;margin-bottom:10px;">
          <?php endif; ?>
          <input type="file" name="image" accept="image/*">
        </label>
        <label>Sijainti
          <select name="location" required>
              <option value="">Valitse sijainti</option>
              <?php foreach ($locations as $loc): ?>
                  <option value="<?=htmlspecialchars($loc)?>" <?=($loc == $cabin['location']) ? 'selected' : ''?>>
                      <?=htmlspecialchars($loc)?>
                  </option>
              <?php endforeach; ?>
          </select>
        </label>
        <button type="submit">Tallenna muutokset</button>
    </form>

    <!-- Esikatselu kuten cabins.php -->
    <section class="cabin-preview">
        <h2>Esikatselu</h2>
        <?php if (!empty($cabin['image'])): ?>
          <img src="<?=htmlspecialchars($cabin['image'])?>" alt="<?=htmlspecialchars($cabin['name'])?>">
        <?php endif; ?>
        <div class="cabin-info">
          <p><strong>Nimi:</strong> <?=htmlspecialchars($cabin['name'])?></p>
          <p><strong>Kuvaus:</strong> <?=nl2br(htmlspecialchars($cabin['description']))?></p>
          <p><strong>Hinta / yö:</strong> €<?=number_format($cabin['price_per_night'],2)?></p>
          <p><strong>Maksimi vieraita:</strong> <?=htmlspecialchars($cabin['max_guests'])?></p>
          <p><strong>Sijainti:</strong> <?=htmlspecialchars($cabin['location'] ?? 'Ei määritelty')?></p>
        </div>
    </section>
  </div>

  <a href="dashboard.php" class="btn" style="margin-top:15px;">Takaisin dashboardille</a>
</main>
</body>
</html>
<?php include 'footer.php'; ?>
