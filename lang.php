<?php
session_start();

// Kontrollo nëse është bërë zgjedhja e gjuhës dhe përfshi skedarin përkatës
if (isset($_COOKIE['language'])) {
    $language = $_COOKIE['language'];
} else {
    $language = 'en'; // Gjuha e default
}

$lang_file = "lang_" . $language . ".php";
if (file_exists($lang_file)) {
    include $lang_file;
} else {
    include "lang_en.php"; // Përdor gjuhën e default nëse skedari i përkthimit nuk ekziston
}
?>
