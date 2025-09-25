<?php
session_start();

// Poista käyttäjän sessio, jos olemassa
if (isset($_SESSION['user_id'])) {
    unset($_SESSION['user_id']);
}

// Tyhjennetään kaikki sessiotiedot
session_unset();
session_destroy();

// Uudelleenohjaus: jos haluat aina viedä kirjautumissivulle
header("Location: index.php");
exit;
