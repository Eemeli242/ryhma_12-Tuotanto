<?php
require '../includes/header.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $location = $_POST['location'] ?? '';
    $price = floatval($_POST['price'] ?? 0);
    $max = intval($_POST['max_guests'] ?? 4);
    $desc = $_POST['description'] ?? '';

    // Tarkista, että tiedosto on ladattu
    if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        $message = "Valitse kuva mökille.";
    } else {
        $fileTmp = $_FILES["image"]["tmp_name"];
        $fileName = basename($_FILES["image"]["name"]);
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png'];

        $check = getimagesize($fileTmp);
        if ($check === false) {
            $message = "Tiedosto ei ole kuva.";
        } elseif (!in_array($fileExt, $allowed)) {
            $message = "Vain JPG, ja PNG kuvat sallittu.";
        } else {
// Polku palvelimelle
$targetDir = $_SERVER['DOCUMENT_ROOT'] . "/uploads/"; // htdocs/uploads/
$uniqueName = time() . "_" . basename($_FILES["image"]["name"]);
$targetFile = $targetDir . $uniqueName;

if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
    // Tietokantaan tallennetaan URL suhteessa htdocs:iin
    $stmt = $pdo->prepare('INSERT INTO cabins (owner_id, name, description, price_per_night, max_guests, location, availability, image) VALUES (0, :name, :desc, :price, :max, :loc, 1, :image)');
    $stmt->execute([
        'name' => $name,
        'desc' => $desc,
        'price' => $price,
        'max' => $max,
        'loc' => $location,
        'image' => "/uploads/" . $uniqueName // URL selaimelle
    ]);


                header('Location: list.php');
                exit;
            } else {
                $message = "Kuvan lataus epäonnistui.";
            }
        }
    }
}
?>

<h1>Lisää mökki</h1>

<?php if ($message): ?>
    <p class="text-danger"><?=htmlspecialchars($message)?></p>
<?php endif; ?>

<form method="post" enctype="multipart/form-data">
  <div class="mb-3">
    <label class="form-label">Nimi</label>
    <input name="name" class="form-control" value="<?=htmlspecialchars($_POST['name'] ?? '')?>" required>
  </div>

  <div class="mb-3">
    <label class="form-label">Sijainti</label>
    <input name="location" class="form-control" value="<?=htmlspecialchars($_POST['location'] ?? '')?>">
  </div>

  <div class="mb-3">
    <label class="form-label">Hinta per yö</label>
    <input name="price" type="number" step="0.01" class="form-control" value="<?=htmlspecialchars($_POST['price'] ?? '')?>">
  </div>

  <div class="mb-3">
    <label class="form-label">Max vieraita</label>
    <input name="max_guests" type="number" class="form-control" value="<?=htmlspecialchars($_POST['max_guests'] ?? 4)?>">
  </div>

  <div class="mb-3">
    <label class="form-label">Kuvaus</label>
    <textarea name="description" class="form-control"><?=htmlspecialchars($_POST['description'] ?? '')?></textarea>
  </div>

  <div class="mb-3">
    <label class="form-label">Kuva</label>
    <input type="file" name="image" accept="image/*" class="form-control" required>
  </div>

  <button class="btn btn-primary">Tallenna</button>
  <a class="btn btn-secondary" href="list.php">Peruuta</a>
</form>

