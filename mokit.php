<?php
require 'config.php';
include 'header.php';

$id = (int)($_GET['id'] ?? 0);
if (!$id) {
    header('Location: index.php');
    exit;
}

$stmt = $pdo->prepare('SELECT * FROM cabins WHERE id = ?');
$stmt->execute([$id]);
$cabin = $stmt->fetch();
if (!$cabin) {
    die('Mökkiä ei löytynyt.');
}
?>
<!doctype html>
<html lang="fi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title><?=htmlspecialchars($cabin['name'])?></title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <style>
    .date-range-wrapper {
      display: flex;
      gap: 10px;
      align-items: center;
    }
    .date-range-wrapper label {
      flex: 1;
    }
  </style>
</head>
<body>
  <main class="container">
    <a href="all_cabins.php" class="back-link">&larr; Takaisin</a>

    <div class="cabin-card">
      <?php if (!empty($cabin['image'])): ?>
        <img src="<?=htmlspecialchars($cabin['image'])?>" alt="<?=htmlspecialchars($cabin['name'])?>">
      <?php endif; ?>

      <div class="cabin-info">
        <h1><?=htmlspecialchars($cabin['name'])?></h1>
        <p><?=nl2br(htmlspecialchars($cabin['description']))?></p>
        <p class="price"><strong>Hinta / yö:</strong> €<?=number_format($cabin['price_per_night'],2)?></p>
        <p><strong>Maksimi vieraita:</strong> <?=htmlspecialchars($cabin['max_guests'])?></p>
        <p><strong>Sijainti:</strong> <?=htmlspecialchars($cabin['location'] ?? 'Ei määritelty')?></p>
      </div>

      <section class="booking">
        <h2>Varaa mökki</h2>
        <form id="bookingForm" action="varaus.php" method="post">
          <input type="hidden" name="cabin_id" value="<?=htmlspecialchars($cabin['id'])?>">
          <label>Etunimi & Sukunimi<br><input name="customer_name" required></label>
          <label>Sähköposti<br><input type="email" name="customer_email" required></label>

          <div class="date-range-wrapper">
            <label>Alkaen<br><input type="text" id="start_date" name="start_date" placeholder="Valitse aloituspäivä" required></label>
            <label>Päättyen<br><input type="text" id="end_date" name="end_date" placeholder="Valitse päättymispäivä" required></label>
          </div>

          <label>Vieraiden määrä<br>
            <input type="number" name="guests" min="1" max="<?=htmlspecialchars($cabin['max_guests'])?>" value="1" required>
          </label>
          <div id="availabilityMsg" aria-live="polite"></div>
          <button type="submit">Varaa</button>
        </form>
      </section>
    </div>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
  <script>
    // Päivämäärien valinta, Y-m-d formaatti PHP:lle
    const startPicker = flatpickr("#start_date", {
        minDate: "today",
        dateFormat: "Y-m-d", // Lähetetään suoraan Y-m-d
        onChange: function(selectedDates) {
            endPicker.set('minDate', selectedDates[0] || "today");
        }
    });

    const endPicker = flatpickr("#end_date", {
        minDate: "today",
        dateFormat: "Y-m-d" // Lähetetään suoraan Y-m-d
    });
  </script>
</body>
</html>
<?php include 'footer.php'; ?>