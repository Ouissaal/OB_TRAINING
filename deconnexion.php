<?php
session_start();

// Détruire toutes les variables de session
$_SESSION = [];
session_destroy();

setcookie('email_utilisateur', '', time() - 3600);
setcookie('psw_utilisateur', '', time() - 3600);

header("Location: login.php");
exit();
?>
