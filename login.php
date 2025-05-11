<?php
require "pdoconnexion.php";
$pdo = connexion();

session_start();
$save_email = $_COOKIE['email_utilisateur']?? '';
$save_password = $_COOKIE['psw_utilisateur']?? '';


if(isset($_POST['connexion'])){
    if(!empty($_POST['email']) && !empty($_POST['password'])){
        $email = $_POST['email'];
        $password = $_POST['password'];

        $sql = "SELECT * FROM utilisateurs WHERE email = ? AND password = ?";
        $stm = $pdo->prepare($sql);
        $stm->execute([$email, $password]);
        $utilisateur = $stm->fetch();

        if($utilisateur){
            $_SESSION['utilisateur_id'] = $utilisateur['id'];
            if(isset($_POST['save'])){

            setcookie('email_utilisateur', $email, time()+3600);
            setcookie('psw_utilisateur', $password, time()+3600);

        }
        else{
            setcookie('email_utilisateur', $email, time()-3600);
            setcookie('psw_utilisateur', $password, time()-3600);
        }
        header("Location: acceuil.php");
        exit();
    }
else{
    echo "<p style='color:red;'><b>Email ou Mot de passe incorrect !</b></p>";
}
   
} 
    
}


?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - OB TRAINING</title>
    <link rel="icon" href="./cours_img/logoOB.png" type="image/png">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
<?php include "header.php"; ?>

    <div class="conatiner card mx-auto mt-5 bordred shadow"style="width:400px;height:500px;">
        <h3 class="text-light  p-2 text-center connexion_title" >CONNEXION :</h3>
        <form action="login.php" method="post" class="p-3">
            <label for="" class="form-label mt-3">Email</label>
            <input type="email" name="email" id="" class="form-control" value="<?= htmlspecialchars($save_email) ?>">
        
            <label for="" class="form-label mt-3">Pasword </label>
            <input type="password" name="password" id="" class="form-control" value="<?= htmlspecialchars($save_password) ?>">
            
            <label for="" class="form-label mt-3"><input type="checkbox" name="save" id="">Se souvenir de moi </label>
           
        <div class="text-center">
            <input type="submit" value="Connexion " name="connexion" class="btn btn-outline-primary w-50 ">
            <p class="text-secondary mt-2">Pas de compte? <a href="inscription.php"> inscrire</a></p>
            </div>



        </form>
    </div>

<?php include "footer.php"; ?>
<script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>