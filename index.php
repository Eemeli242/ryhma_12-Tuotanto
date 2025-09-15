<?php
require 'config.php';
include 'header.php';


// Hae kaikki sijainnit suodattimelle
$locations = $pdo->query('SELECT DISTINCT location FROM cabins ORDER BY location')->fetchAll(PDO::FETCH_COLUMN);
?>

<!doctype html>
<html lang="fi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Lomamökit</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<main class="container">
  <section class="search">
    <!-- Suodatinlomake -->
    <form class="filters" method="get" action="all_cabins.php">
      <label>Sijainti
        <select name="location">
          <option value="">Kaikki</option>
          <?php foreach ($locations as $loc): ?>
            <option value="<?=htmlspecialchars($loc)?>"><?=htmlspecialchars($loc)?></option>
          <?php endforeach; ?>
        </select>
      </label>

      <label>Vieraiden määrä
        <input type="number" name="guests" min="1" placeholder="Kaikki">
      </label>

      <label>Min hinta (€)
        <input type="number" name="min_price" min="0" step="1" placeholder="Ei rajaa">
      </label>

      <label>Maksimi hinta (€)
        <input type="number" name="max_price" min="0" step="1" placeholder="Ei rajaa">
      </label>

      <button type="submit">Näytä kaikki mökit</button>
    </form>
  </section>

<!-- Neljä info-artikkelia kuvineen -->
<section class="info-grid">
  <div class="info-card">
    <img src="images/etuikoni-1.png" alt="Laatutarkastus" style="width:80px;height:80px;margin-bottom:10px;">
    <h3>Yli 4400 laatutarkastettua kohdetta</h3>
    <p>Laaja valikoima mökkejä ympäri Suomen, tarkastettu laatu ja varustelu.</p>
  </div>
  <div class="info-card">
    <img src="images/etuikoni-2.png" alt="Peruutusturva" style="width:80px;height:80px;margin-bottom:10px;">
    <h3>Peruutusturva sisältyy hintaan</h3>
    <p>Voit varata mökin turvallisesti ja muuttaa suunnitelmia tarvittaessa.</p>
  </div>
  <div class="info-card">
    <img src="images/etuikoni-3.png" alt="Kuluttajansuoja" style="width:80px;height:80px;margin-bottom:10px;">
    <h3>Kaikissa varauksissa kuluttajansuoja</h3>
    <p>Turvallisuus ja luottamus etusijalla kaikissa mökkivarausprosesseissa.</p>
  </div>
  <div class="info-card">
    <img src="images/etuikoni-4.png" alt="Suomenkielinen asiakaspalvelu" style="width:80px;height:80px;margin-bottom:10px;">
    <h3>Suomenkielinen asiakaspalvelu</h3>
    <p>Autamme sinua tarvittaessa nopeasti ja helposti omalla kielelläsi.</p>
  </div>
</section>

<!-- Ajankohtaista -->
<section class="highlights">
  <h2>Ajankohtaista</h2>
  <div class="highlights-grid" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(250px,1fr));gap:20px;margin:30px 0;">
    
    <!-- Äkkilähdöt -->
    <div class="highlight-card" style="background:#fff;padding:20px;border-radius:12px;box-shadow:0 4px 12px rgba(0,0,0,0.08);text-align:center;transition:transform 0.2s;">
      <img src="images/123.jpeg" alt="Äkkilähdöt" style="width:80px;height:80px;margin-bottom:10px;">
      <h3>Äkkilähdöt</h3>
      <p>Spontaanin matkustajan unelma - katso edulliset Äkkilähtö-mökit täältä!</p>
      <a href="all_cabins.php" class="btn">Katso mökit</a>
    </div>

    <!-- Laita mökkisi tienaamaan -->
    <div class="highlight-card" style="background:#fff;padding:20px;border-radius:12px;box-shadow:0 4px 12px rgba(0,0,0,0.08);text-align:center;transition:transform 0.2s;">
      <img src="images/124.jpeg" alt="Vuokraa mökki" style="width:80px;height:80px;margin-bottom:10px;">
      <h3>Laita mökkisi tienaamaan</h3>
      <p>Onko sinulla oma mökki lähes käyttämättömänä? Tai vuokraatko jo mökkiäsi? Lomarenkaan kautta vuokraustoiminta tarjoaa sinulle tuloja pienemmällä työllä.</p>
      <a href="add_cabin.php" class="btn">Ilmoita mökki</a>
    </div>

    <!-- Usein kysytyt kysymykset -->
    <div class="highlight-card" style="background:#fff;padding:20px;border-radius:12px;box-shadow:0 4px 12px rgba(0,0,0,0.08);text-align:center;transition:transform 0.2s;">
      <img src="images/125.jpeg" alt="UKK" style="width:80px;height:80px;margin-bottom:10px;">
      <h3>Usein kysytyt kysymykset</h3>
      <p>Katso täältä usein kysytyt kysymykset koskien mökin vuokraamista.</p>
      <a href="all_cabins.php" class="btn">Lue lisää</a>
</section>

<!-- Pieni info-osio kuvineen -->
<section class="small-info">
  <h2>Miksi valita meidät?</h2>
<ul> <li>Luotettava kotimainen toimija</li> <li>Helppo ja turvallinen varausprosessi</li> <li>Kaikki hinnat sisältävät tarvittavat lisäpalvelut</li> <li>Suomalainen asiakaspalvelu valmiina auttamaan</li> </ul>
</section>


</main>

<?php include 'footer.php'; ?>
</body>
</html>
