<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OB TRAINING - Plateforme de Formation</title>
    <link rel="icon" href="./cours_img/logoOB.png" type="image/png">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container navbar-container">
            <a href="acceuil.php" class="navbar-brand">OB<span>TRAINING</span></a>
            
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a href="acceuil.php" class="nav-link ">Accueil</a>
                </li>
                <li class="nav-item">
                    <a href="cours.php" class="nav-link">Cours</a>
                </li>
                
                <?php if (isset($_SESSION['utilisateur_id'])): ?>
                    <li class="nav-item">
                        <a href="mon_compte.php" class="nav-link">Mon compte</a>
                    </li>
                    <li class="nav-item">
                        <a href="deconnexion.php" class="nav-link btn ">Déconnexion</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a href="login.php" class="nav-link btn">Connexion</a>
                    </li>
                    <li class="nav-item">
                        <a href="create_account.php" class="nav-link btn-outline">Inscription</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>
</body>
 </html>