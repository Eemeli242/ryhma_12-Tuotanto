<?php
require_once __DIR__ . '/../includes/header.php';
$id = intval($_GET['id'] ?? 0);

$stmt = $pdo->prepare('SELECT * FROM cabins WHERE id = :id');
$stmt->execute(['id'=>$id]);
$c = $stmt->fetch();

if (!$c) { 
    echo '<div class="alert alert-danger">Mökkiä ei löydy.</div>'; 
    require_once __DIR__ . '/../includes/footer.php'; 
    exit; 
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $location = $_POST['location'] ?? '';
    $price = floatval($_POST['price'] ?? 0);
    $max = intval($_POST['max_guests'] ?? 4);
    $desc = $_POST['description'] ?? '';
    $avail = isset($_POST['availability']) ? 1 : 0;

    // Kuva
    $imagePath = $c['image']; // oletus nykyinen kuva
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $fileTmp = $_FILES["image"]["tmp_name"];
        $fileName = basename($_FILES["image"]["name"]);
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png','gif'];

        $check = getimagesize($fileTmp);
        if ($check === false) {
            $message = "Tiedosto ei ole kuva.";
        } elseif (!in_array($fileExt, $allowed)) {
            $message = "Vain JPG, PNG ja GIF kuvat sallittu.";
        } else {
            $targetDir = $_SERVER['DOCUMENT_ROOT'] . "/uploads/";
            $uniqueName = time() . "_" . $fileName;
            $targetFile = $targetDir . $uniqueName;

            if (move_uploaded_file($fileTmp, $targetFile)) {
                $imagePath = "/uploads/" . $uniqueName;
            } else {
                $message = "Kuvan lataus epäonnistui.";
            }
        }
    }

    if (!$message) {
        $stmt = $pdo->prepare('UPDATE cabins SET name=:name, description=:desc, price_per_night=:price, max_guests=:max, location=:loc, availability=:avail, image=:image WHERE id=:id');
        $stmt->execute([
            'name'=>$name,
            'desc'=>$desc,
            'price'=>$price,
            'max'=>$max,
            'loc'=>$location,
            'avail'=>$avail,
            'image'=>$imagePath,
            'id'=>$id
        ]);
        header('Location: list.php');
        exit;
    }
}
?>

<h1>Muokkaa mökkiä</h1>

<?php if ($message): ?>
    <p class="text-danger"><?=htmlspecialchars($message)?></p>
<?php endif; ?>

<form method="post" enctype="multipart/form-data">
  <div class="mb-3">
    <label class="form-label">Nimi</label>
    <input name="name" class="form-control" required value="<?=htmlspecialchars($c['name'])?>">
  </div>

  <div class="mb-3">
    <label class="form-label">Sijainti</label>
    <input name="location" class="form-control" value="<?=htmlspecialchars($c['location'])?>">
  </div>

  <div class="mb-3">
    <label class="form-label">Hinta per yö</label>
    <input name="price" type="number" step="0.01" class="form-control" value="<?=htmlspecialchars($c['price_per_night'])?>">
  </div>

  <div class="mb-3">
    <label class="form-label">Max vieraita</label>
    <input name="max_guests" type="number" class="form-control" value="<?=htmlspecialchars($c['max_guests'])?>">
  </div>

  <div class="mb-3">
    <label class="form-label">Kuvaus</label>
    <textarea name="description" class="form-control"><?=htmlspecialchars($c['description'])?></textarea>
  </div>

  <div class="mb-3">
    <label class="form-label">Nykyinen kuva</label><br>
    <?php if ($c['image']): ?>
        <img src="<?=htmlspecialchars($c['image'])?>" alt="Mökin kuva" style="max-width:200px;margin-bottom:10px;">
    <?php else: ?>
        <p>Ei kuvaa</p>
    <?php endif; ?>
  </div>

  <div class="mb-3">
    <label class="form-label">Vaihda kuva</label>
    <input type="file" name="image" accept="image/*" class="form-control">
  </div>

  <div class="form-check mb-3">
    <input class="form-check-input" type="checkbox" name="availability" id="avail" <?= $c['availability'] ? 'checked' : '' ?>>
    <label class="form-check-label" for="avail">Näkyvissä</label>
  </div>

  <button class="btn btn-primary">Tallenna</button>
  <a class="btn btn-secondary" href="list.php">Peruuta</a>
</form>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
