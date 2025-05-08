<?php
require "pdoconnexion.php";
$pdo = connexion();
session_start();


if (!isset($_SESSION['utilisateur_id'])) {
    header("Location: login.php");
    exit();
}

$id_utilisateur = $_SESSION['utilisateur_id'];

// Requête pour récupérer les données de l'utilisateur connecté
$sql = "SELECT * FROM utilisateurs WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id_utilisateur]);
$utilisateur = $stmt->fetch();

if (!$utilisateur) {
    echo "<p>Utilisateur introuvable.</p>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
  <title>Mon compte</title>
</head>
<body>
<?php include "header.php";?>


  <div class="container py-5">
    <div class="card shadow-lg rounded-4 p-4">
      <div class="row align-items-center">
        <div class="col-md-4 text-center">
          <img src="<?= htmlspecialchars($utilisateur['img']??'images/default.png') ?>" alt="Photo de profil" class="img-fluid shadow" style="width:300px;">
        </div>
        <div class="col-md-8">
          <h2 class="mb-3">Mon Compte</h2>
          <ul class="list-group list-group-flush">
            <li class="list-group-item"><strong>Email :</strong> <?= htmlspecialchars($utilisateur['email']) ?></li>
            <li class="list-group-item"><strong>Téléphone :</strong> <?= htmlspecialchars($utilisateur['tel']) ?></li>
            <li class="list-group-item"><strong>Établissement :</strong> <?= htmlspecialchars($utilisateur['etablissement']) ?></li>
            <li class="list-group-item"><strong>Date d'inscription :</strong> <?= htmlspecialchars($utilisateur['date_inscription']) ?></li>
          </ul>
          <div class="mt-4">
            <a href="modifier.php" class="btn text-light bg-secondary">Modifier</a>
            <a href="deconnexion.php" class="btn btn-outline-danger">Se déconnecter</a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php include "footer.php";?>
</body>
</html>
