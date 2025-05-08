<?php 
require "pdoconnexion.php";
$pdo = connexion();


// COURS 
$stm = "CREATE TABLE cours(
   id INT(6)  AUTO_INCREMENT PRIMARY KEY,
   description VARCHAR(60) ,
   date_creation DATE ,
   image VARCHAR(255) 
)";
$pdo->exec($stm);

// UTILISATEURS 
$stm = "CREATE TABLE utilisateurs(
    id INT(6)  AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(60) UNIQUE,
    password VARCHAR(255) NOT NULL,
    img VARCHAR(255),
    tel INT(10) ,
    etablissement varchar(60),
    date_inscription DATE
 )";
 $pdo->exec($stm);

 // COURS_DEBUT 
$stm = "CREATE TABLE cours_debut(
    id INT(6)  AUTO_INCREMENT PRIMARY KEY,
    id_cour INT(6) NOT NULL,
    FOREIGN KEY (id_cour) REFERENCES cours(id),
    id_utilisateur INT(6) NOT NULL,
    FOREIGN KEY(id_utilisateur) REFERENCES utilisateurs(id),
    date_debut DATE,
    niveau_progrression INT 
 )";
 $pdo->exec($stm);

 

?>