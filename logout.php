<?php
session_start();

// Poista käyttäjän jos sessio olemas
if (isset($_SESSION['user_id'])) {
    unset($_SESSION['user_id']);
}

// Tyhjennetään kaikki sessiot
session_unset();
session_destroy();


header("Location: index.php");
exit;
