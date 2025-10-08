<?php
require 'config.php';
include 'header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$message = '';
$locations = [
    'Helsinki','Espoo','Tampere','Vantaa','Oulu','Turku',
    'Jyväskylä','Lahti','Kuopio','Pori','Lappeenranta','Vaasa',
    'Seinäjoki','Rovaniemi','Kotka','Joensuu','Hämeenlinna','Kouvola',
    'Salo','Mikkeli','Hyvinkää','Nokia','Kajaani','Savonlinna',
    'Riihimäki','Kerava','Kemi','Kokkola','Loimaa','Raisio'
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = floatval($_POST['price_per_night'] ?? 0);
    $maxGuests = intval($_POST['max_guests'] ?? 0);
    $location = $_POST['location'] ?? '';

    // Tarkista pakolliset kentät
    if (!$name || !$price || !$maxGuests || !$location || !isset($_FILES['image'])) {
        $message = "Täytä kaikki pakolliset kentät ja valitse kuva.";
    } else {
        $fileTmp = $_FILES["image"]["tmp_name"];
        $fileName = basename($_FILES["image"]["name"]);
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png'];

        $check = getimagesize($fileTmp);
        if ($check === false) {
            $message = "Tiedosto ei ole kuva.";
        } elseif (!in_array($fileExt, $allowed)) {
            $message = "Vain JPG, ja PNG kuvat sallittu.";
        } else {
            $targetDir = "uploads/";
            if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
            $targetFile = $targetDir . time() . "_" . $fileName;

            if (move_uploaded_file($fileTmp, $targetFile)) {
                // Tallenna tietokantaan
                $stmt = $pdo->prepare("
                    INSERT INTO cabins 
                    (name, description, price_per_night, max_guests, image, location, owner_id)
                    VALUES (?, ?, ?, ?, ?, ?, ?)
                ");
                $stmt->execute([$name, $description, $price, $maxGuests, $targetFile, $location, $_SESSION['user_id']]);

                header("Location: cabins.php?id=" . $pdo->lastInsertId());
                exit;
            } else {
                $message = "Kuvan lataus epäonnistui.";
            }
        }
    }
}
?>

<!doctype html>
<html lang="fi">
<head>
  <meta charset="utf-8">
  <title>Lisää mökki</title>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
</head>
<body>
<main class="add-cabin">
  <h1>Lisää uusi mökki</h1>

  <?php if ($message): ?>
    <p class="form-error"><?=htmlspecialchars($message)?></p>
  <?php endif; ?>

  <form method="post" enctype="multipart/form-data" class="cabin-form">
    <div class="form-group">
      <label for="name">Mökin nimi</label>
      <input id="name" name="name" value="<?=htmlspecialchars($_POST['name'] ?? '')?>" required>
    </div>

    <div class="form-group">
      <label for="description">Kuvaus</label>
      <textarea id="description" name="description"><?=htmlspecialchars($_POST['description'] ?? '')?></textarea>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label for="price_per_night">Hinta / yö</label>
        <input id="price_per_night" type="number" step="0.01" name="price_per_night" value="<?=htmlspecialchars($_POST['price_per_night'] ?? '')?>" required>
      </div>

      <div class="form-group">
        <label for="max_guests">Maksimi vieraita</label>
        <input id="max_guests" type="number" name="max_guests" value="<?=htmlspecialchars($_POST['max_guests'] ?? '')?>" required>
      </div>
    </div>

    <div class="form-group">
      <label for="image">Kuva</label>
      <input id="image" type="file" name="image" accept="image/*" required>
    </div>

    <div class="form-group">
      <label for="location">Sijainti</label>
      <select id="location" name="location" required>
        <option value="">Valitse sijainti</option>
        <?php foreach ($locations as $loc): ?>
          <option value="<?=htmlspecialchars($loc)?>" <?=isset($_POST['location']) && $_POST['location']==$loc ? 'selected' : ''?>>
            <?=htmlspecialchars($loc)?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <button type="submit" class="btn">Lisää mökki</button>
  </form>
</main>
<?php include 'footer.php'; ?>
</body>
</html>

