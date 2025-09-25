<?php
require 'config.php';
include 'header.php';


$location  = $_GET['location'] ?? '';
$guests    = (int)($_GET['guests'] ?? 0);
$minPrice  = (float)($_GET['min_price'] ?? 0);
$maxPrice  = (float)($_GET['max_price'] ?? 0);
$startDate = $_GET['start_date'] ?? '';
$endDate   = $_GET['end_date'] ?? '';

$locations = $pdo->query('SELECT DISTINCT location FROM cabins ORDER BY location')
                 ->fetchAll(PDO::FETCH_COLUMN);

$sql = "SELECT * FROM cabins WHERE 1=1";
$params = [];

if ($location) { $sql .= " AND location = ?"; $params[] = $location; }
if ($guests > 0) { $sql .= " AND max_guests >= ?"; $params[] = $guests; }
if ($minPrice > 0) { $sql .= " AND price_per_night >= ?"; $params[] = $minPrice; }
if ($maxPrice > 0) { $sql .= " AND price_per_night <= ?"; $params[] = $maxPrice; }

if ($startDate && $endDate) {
    $sql .= " AND id NOT IN (SELECT cabin_id FROM bookings WHERE NOT (end_date < ? OR start_date > ?))";
    $params[] = $startDate; $params[] = $endDate;
} elseif ($startDate) {
    $sql .= " AND id NOT IN (SELECT cabin_id FROM bookings WHERE ? BETWEEN start_date AND end_date)";
    $params[] = $startDate;
} elseif ($endDate) {
    $sql .= " AND id NOT IN (SELECT cabin_id FROM bookings WHERE ? BETWEEN start_date AND end_date)";
    $params[] = $endDate;
}

$stmt   = $pdo->prepare($sql);
$stmt->execute($params);
$cabins = $stmt->fetchAll();

/* Kaikki varaukset kalenteriin */
$bookings = $pdo->query("SELECT start_date, end_date FROM bookings")
                ->fetchAll(PDO::FETCH_ASSOC);
$disabledDates = [];
foreach ($bookings as $b) {
}

/* Eniten varatut 4 mökkiä */
$topCabinsStmt = $pdo->query("
    SELECT c.*, COUNT(b.id) AS booking_count
    FROM cabins c
    JOIN bookings b ON c.id = b.cabin_id
    GROUP BY c.id
    ORDER BY booking_count DESC
    LIMIT 4
");

$topCabins = $topCabinsStmt->fetchAll(PDO::FETCH_ASSOC); ?>

<!doctype html>
<html lang="fi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Kaikki lomamökit</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <style>
    .btn {
      display: inline-block;
      background-color: #40572e;
      color: #fff;
      padding: 10px 16px;
      border-radius: 8px;
      text-decoration: none;
      font-weight: 600;
      transition: background-color 0.2s, transform 0.2s;
    }
    .filters { display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 20px; }
    .filters label { flex: 1 1 150px; font-weight: 500; }
    .filters input, .filters select { width: 100%; padding: 8px; border-radius: 6px; border: 1px solid #ccc; }
    .card img { max-height: 200px; object-fit: cover; }
  </style>
</head>
<body>
    <!-- Suodatinlomake -->
<main class="container">

<section class="search">
  <form class="filters" method="get" action="all_cabins.php">
    <label>Sijainti
      <select name="location">
        <option value="">Kaikki</option>
        <?php foreach ($locations as $loc): ?>
          <option value="<?=htmlspecialchars($loc)?>" <?=($loc==$location)?'selected':''?>><?=htmlspecialchars($loc)?></option>
        <?php endforeach; ?>
      </select>
    </label>
    <label>Vieraiden määrä
      <input type="number" name="guests" min="1" value="<?=($guests>0)?$guests:''?>" placeholder="Kaikki"> 
    </label>
    <label>Min hinta (€)
      <input type="number" name="min_price" min="0" step="1" value="<?=($minPrice>0)?$minPrice:''?>"placeholder="Ei rajaa"> 
    </label>
    <label>Maksimi hinta (€)
      <input type="number" name="max_price" min="0" step="1" value="<?=($maxPrice>0)?$maxPrice:''?>" placeholder="Ei rajaa"> 
    </label>
    <label>Saapuu
      <input type="text" id="start_date" name="start_date" value="<?=htmlspecialchars($startDate)?>" placeholder="Valitse aloituspäivä">
    </label>
    <label>Lähtee
      <input type="text" id="end_date" name="end_date" value="<?=htmlspecialchars($endDate)?>" placeholder="Valitse päättymispäivä">
    </label>
  </form>
</section>
    <!-- Eniten varatut lomamökit -->
<?php if ($topCabins): ?>
<section style="margin:40px 0;">
  <h2>Eniten varatut lomamökit – 4 parasta</h2>
  <div class="grid">
    <?php foreach ($topCabins as $c): ?>
      <article class="card top-card">
        <h3><?=htmlspecialchars($c['name'])?></h3>
        <?php if ($c['image']): ?>
          <img src="<?=htmlspecialchars($c['image'])?>" alt="<?=htmlspecialchars($c['name'])?>">
        <?php endif; ?>
<p class="description"><?= nl2br(htmlspecialchars($c['description'])) ?></p>

        <p><strong>Hinta / yö:</strong> €<?=number_format($c['price_per_night'],2)?></p>
        <p><strong>Maksimi vieraita:</strong> <?=htmlspecialchars($c['max_guests'])?></p>
        <p><strong>Sijainti:</strong> <?=htmlspecialchars($c['location'])?></p>
        <p><strong>Varauksia yhteensä:</strong> <?=htmlspecialchars($c['booking_count'])?></p>
        <a href="cabins.php?id=<?=urlencode($c['id'])?>" class="btn">Varaa nyt</a>
      </article>
    <?php endforeach; ?>
  </div>
</section>
<?php endif; ?>

<div class="grid">
  <?php if ($cabins): ?>
    <?php foreach ($cabins as $c): ?>
      <article class="card">
        <h2><?=htmlspecialchars($c['name'])?></h2>
        <?php if ($c['image']): ?>
          <img src="<?=htmlspecialchars($c['image'])?>" alt="<?=htmlspecialchars($c['name'])?>">
        <?php endif; ?>
        <p><?=nl2br(htmlspecialchars($c['description']))?></p>
        <p><strong>Hinta / yö:</strong> €<?=number_format($c['price_per_night'],2)?></p>
        <p><strong>Maksimi vieraita:</strong> <?=htmlspecialchars($c['max_guests'])?></p>
        <p><strong>Sijainti:</strong> <?=htmlspecialchars($c['location'])?></p>
        <a href="cabins.php?id=<?=urlencode($c['id'])?>" class="btn">Varaa nyt</a>
      </article>
    <?php endforeach; ?>
  <?php else: ?>
    <p>Ei mökkejä valituilla suodattimilla löytynyt.</p>
  <?php endif; ?>
</div>
</main>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  const disabled = <?=json_encode($disabledDates)?>;

  const startPicker = flatpickr("#start_date", {
      minDate: "today",
      dateFormat: "Y-m-d",
      disable: disabled,
      onChange: function(selectedDates) {
          endPicker.set('minDate', selectedDates[0] || "today");
      }
  });

  const endPicker = flatpickr("#end_date", {
      minDate: "today",
      dateFormat: "Y-m-d",
      disable: disabled
  });

  const filters = document.querySelectorAll('.filters select, .filters input');
  filters.forEach(el => el.addEventListener('change', () => {
    const params = new URLSearchParams();
    filters.forEach(f => { if(f.value) params.set(f.name,f.value); });
    window.location.search = params.toString();
  }));
});
</script>
</body>
</html>
</main>

<?php include 'footer.php'; ?>
</body>
</html>
