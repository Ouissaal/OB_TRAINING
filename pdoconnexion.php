<?php

function connexion(){
    try{
        $pdo = new PDO("mysql:host=localhost; dbname=formation", "root","");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;

    }catch(PDOEXCEPTION $e){
        die("Erreur de connexion".$e->getMessage());

    }
}

?>