<?php
require 'config.php';
include 'header.php'; // tuo nav ja linkitys tyyleihin

if (!isset($_SESSION['user_id'])) {
    header("Location: login_register.php");
    exit;
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $targetDir = "uploads/";
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    if (isset($_FILES["image"]) && $_FILES["image"]["error"] === 0) {
        $fileTmp  = $_FILES["image"]["tmp_name"];
        $fileName = basename($_FILES["image"]["name"]);
        $fileExt  = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowed  = ['jpg','jpeg','png','gif'];

        // Tarkista tiedostotyyppi
        $check = getimagesize($fileTmp);
        if($check === false) {
            $message = "Tiedosto ei ole kuva.";
        } elseif(!in_array($fileExt, $allowed)) {
            $message = "Vain JPG, PNG ja GIF kuvat sallittu.";
        } else {
            $targetFile = $targetDir . time() . "_" . $fileName;
            if (move_uploaded_file($fileTmp, $targetFile)) {
                // Tallenna tietokantaan ladatun kuvan polku
                $stmt = $pdo->prepare("
                    INSERT INTO cabins (name, description, price_per_night, max_guests, image, location, owner_id)
                    VALUES (?, ?, ?, ?, ?, ?, ?)
                ");
                $stmt->execute([
                    $_POST['name'],
                    $_POST['description'],
                    $_POST['price_per_night'],
                    $_POST['max_guests'],
                    $targetFile,
                    $_POST['location'],
                    $_SESSION['user_id']
                ]);
                // Hae viimeksi lisätyn mökin ID
                $cabinId = $pdo->lastInsertId();
                // Ohjaa suoraan mökin sivulle
                header("Location: cabins.php?id=" . $cabinId);
                exit;
            } else {
                $message = "Kuvan lataus epäonnistui.";
            }
        }
    } else {
        $message = "Valitse kuva ladattavaksi.";
    }
}


// Lista Suomen yleisimmistä kaupungeista
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
<title>Lisää mökki</title>
<link rel="stylesheet" href="style.css">

</head>
<body>
<main class="add_cabin">
  <h1>Lisää uusi mökki</h1>
  <p><?=htmlspecialchars($message)?></p>
  <form method="post" enctype="multipart/form-data">
      <label>Nimi
        <input name="name" required>
      </label>
      <label>Kuvaus
        <textarea name="description"></textarea>
      </label>
      <label>Hinta / yö
        <input type="number" step="0.01" name="price_per_night" required>
      </label>
      <label>Maksimi vieraita
        <input type="number" name="max_guests" required>
      </label>
      <label>Kuva
        <input type="file" name="image" accept="image/*" required>
      </label>
      <label>Sijainti
        <select name="location" required>
            <option value="">Valitse sijainti</option>
            <?php foreach ($locations as $loc): ?>
                <option value="<?=htmlspecialchars($loc)?>"><?=htmlspecialchars($loc)?></option>
            <?php endforeach; ?>
        </select>
      </label>
      <button type="submit">Lisää mökki</button>
  </form>
</main>
</body>
</html>
<?php include 'footer.php'; ?>