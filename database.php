<?php
$pdo = new PDO('mysql:host=localhost','root','');
$stm = "CREATE DATABASE IF NOT EXISTS formation";
$pdo->exec($stm)

?>