<?php
require "pdoconnexion.php";
$pdo = connexion();

if (isset($_POST['inscrire'])) {
    if (!empty($_POST['email']) && !empty($_FILES['image']['name']) && !empty($_POST['tel']) && !empty($_POST['etablissement']) && !empty($_POST['password'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];
        // Traitement de l'image
        $imageName = time() . "_" . basename($_FILES['image']['name']);
        $imageTmp = $_FILES['image']['tmp_name'];
        $uploadDir = "images/";


        move_uploaded_file($imageTmp, $uploadDir . $imageName);

        $tel = $_POST['tel'];
        $etablissement = $_POST['etablissement'];
        $date_inscription = date('Y-m-d'); 

       
        $sql = "INSERT INTO utilisateurs(email,password, img, tel, etablissement, date_inscription) VALUES (?, ?,?, ?, ?, ?)";
        $stm = $pdo->prepare($sql);
        $stm->execute([$email,$password, $imageName, $tel, $etablissement, $date_inscription]);

        header("Location: acceuil.php");
        exit();
    }
}
?>



<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - OB TRAINING</title>
    <link rel="icon" href="./cours_img/logoOB.png" type="image/png">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
<?php include "header.php"; ?>

    <div class="card mx-auto mt-5 border shadow" style="width:500px; height:700px;">
        <h3 class=" inscription_title p-2 text-center">INSCRIPTION :</h3>
        <form action="inscription.php" method="post" class="p-3" enctype="multipart/form-data">
            <label class="form-label mt-3">Email</label>
            <input type="email" name="email" class="form-control" required>
            <label class="form-label mt-3">Crée votre mot de passe</label>
            <input type="password" name="password" class="form-control" required>

            <label class="form-label mt-3">Image :</label>
            <input type="file" name="image" class="form-control" accept="image/*" required>

            <label class="form-label mt-3">Téléphone</label>
            <input type="tel" name="tel" class="form-control" required>

            <label class="form-label mt-3">Établissement</label>
            <input type="text" name="etablissement" class="form-control" required><br>

            <div class="text-center">
                <input type="submit" name="inscrire" value="S'inscrire" class="btn btn-outline-primary w-50">
                <p class="text-secondary mt-2">Déjà inscrit ? <a href="login.php">Login</a></p>
            </div>
        </form>
    </div>

<?php include "footer.php"; ?>
<script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
