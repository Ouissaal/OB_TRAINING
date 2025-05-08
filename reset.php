<?php
session_start();

// Initialiser la progression si elle n'existe pas
if (!isset($_SESSION['progress'])) {
    $_SESSION['progress'] = [
        'a1' => false,
        'a2' => false,
        'a3' => false,
    ];
}

// Si un bouton est cliqué
if (isset($_GET['valider'])) {
    $id = $_GET['valider'];
    if (isset($_SESSION['progress'][$id])) {
        $_SESSION['progress'][$id] = true;
    }
}

// Vérifie si tout est terminé
$finis = array_filter($_SESSION['progress'], fn($v) => $v === true);
$completed = count($finis) === 3;
?>
