<?php
session_start();
require "pdoconnexion.php";
$pdo = connexion();

if (!isset($_SESSION['utilisateur_id'])) {
    header("Location: login.php");
    exit();
}

$id = $_SESSION['utilisateur_id'];

// Récupération des infos actuelles
$stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE id = ?");
$stmt->execute([$id]);
$utilisateur = $stmt->fetch(); 

if (isset($_POST['modifier'])) {
    $email = $_POST['email'];
    $tel = $_POST['tel'];
    $etablissement = $_POST['etablissement'];

    $stmt = $pdo->prepare("UPDATE utilisateurs SET email = ?, tel = ?, etablissement = ? WHERE id = ?");
    $stmt->execute([$email, $tel, $etablissement, $id]);
    header("Location: mon_compte.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier mon compte</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
</head>
<body>
<?php include "header.php";?>


<div class="container py-5">
    <h2>Modifier mes informations</h2>
    <form method="post" class="card p-4 shadow-lg">
        <div class="mb-3">
            <label class="form-label">Email :</label>
            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($utilisateur['email']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Téléphone :</label>
            <input type="text" name="tel" class="form-control" value="<?= htmlspecialchars($utilisateur['tel']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Établissement :</label>
            <input type="text" name="etablissement" class="form-control" value="<?= htmlspecialchars($utilisateur['etablissement']) ?>">
        </div>
        <button type="submit" name="modifier" class="btn enregistrer">Enregistrer</button>
        <a href="mon_compte.php" class="btn btn-secondary">Annuler</a>
    </form>
</div>

<?php include "footer.php";?>
</body>
</html>
