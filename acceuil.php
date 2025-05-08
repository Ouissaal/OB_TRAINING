<?php 
session_start();
require 'pdoconnexion.php';
$pdo = connexion();


if (!isset($_SESSION['course_progress'])) {
    $_SESSION['course_progress'] = [];
}


function calculateProgressPercentage($courseId) {
    if (!isset($_SESSION['course_progress'][$courseId])) { // Vérifie si on a des données de progression pour ce cours
        return 0;
    }


    $lessons = $_SESSION['course_progress'][$courseId]['lessons'];// Récupère les leçons de ce cours depuis la session

    $total = 0;      // Total des leçons
    $completed = 0;  // Leçons terminées

    // Parcourt chaque leçon
    foreach ($lessons as $lessonDone) {
        $total++; 
        if ($lessonDone === true) {
            $completed++; // Si la leçon est faite, on ajoute 1 au compteur terminé
        }
    }

    // Évite la division par zéro si aucun cours n'est défini
    if ($total == 0) {
        return 0;
    }

    
    $percentage = ($completed / $total) * 100;
    return $percentage;
}




$stmt = $pdo->query('SELECT * FROM cours');
$cours = $stmt->fetchAll(); //all 
?>

<body>

<?php include "header.php"; ?>

   
    <section class="hero-section">
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title">
                    Développez vos <span class="text-gradient">compétences</span> avec OB formations de qualité
                </h1>
                <p class="hero-subtitle">
                    OB FORMATION vous propose des cours interactifs pour progresser à votre rythme avec un suivi personnalisé
                </p>
                <a href="cours.php" class="btn border-dark">
                    <i class="fas fa-play"></i> Découvrir nos cours
                </a>
            </div>
        </div>
    </section>

    <section class="section-sm">
        <div class="container">
            <div class="text-center mb-5">
                <h2>Nos <span class="text-gradient">formations</span></h2>
                <p class="subtitle">Explorez notre catalogue de cours et commencez votre parcours d'apprentissage</p>
            </div>
            
            <div class="row g-4">
                <?php foreach ($cours as $key => $cour): ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="card">
                            <div class="card-image-wrapper">
                                <img src="<?= htmlspecialchars($cour['image'] ?? 'images/default.png') ?>" class="card-img-top" alt="Image du cours">
                            </div>
                            
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($cour['description']) ?></h5>
                                
                                <div class="d-flex align-items-center mb-3 text-gray">
                                    <div class="me-3">
                                        <i class="far fa-calendar-alt me-1"></i>
                                        <?= htmlspecialchars($cour['date_creation']) ?>
                                    </div>
                                    <div>
                                        <i class="far fa-clock me-1"></i>
                                        <span>6 heures</span>
                                    </div>
                                </div>
                                
                                <div class="progress-wrapper">
                                    <div class="progress-label">
                                        <?php 
                                            $progressPercentage = calculateProgressPercentage($cour['id']);
                                            $progressRounded = round($progressPercentage);
                                        ?>
                                        <span>Progression</span>
                                        <span><?= $progressRounded ?>%</span>
                                    </div>
                                    <div class="progress-bar-container">
                                        <div class="progress-bar-fill" style="--progress-width: <?= $progressPercentage ?>%"></div>
                                    </div>
                                </div>
                                
                                <a href="progression.php?id=<?= $cour['id'] ?>" class="btn text-muted bg-secondary w-100">
                                    <i class="fas fa-play-circle me-2"></i> <span class="text-gradient">Commencer le cours</span>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    

    <section class="section bg-light">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <h2>Pourquoi choisir <span class="text-gradient">OB TRAINING</span> ?</h2>
                    <p class="subtitle">Notre plateforme de formation vous offre une expérience d'apprentissage unique</p>
                    
                    <div class="mt-4">
                            
                            <div>
                                <h4 class="feature-title">Cours interactifs</h4>
                                <p class="feature-description">Nos cours sont conçus pour être engageants et interactifs, facilitant votre apprentissage.</p>
                            </div>
                            
                            <div>
                                <h4 class="feature-title">Suivi de progression</h4>
                                <p class="feature-description">Suivez votre progression et visualisez vos accomplissements en temps réel.</p>
                            </div>
                       
                    
                            <div>
                                <h4 class="feature-title">Certifications</h4>
                                <p class="feature-description">Obtenez des certifications reconnues pour valoriser vos compétences acquises.</p>
                            </div>
                        </div>
                    
                </div>
                
                <div class="col-lg-6">
                    <div class="feature-stats">
                        <img src="./cours_img/logoOB.png" alt="Formation" class="img-fluid feature-image">
                    </div>
                </div>
            </div>
        </div>
    </section>
    

<?php  include "footer.php";?>
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>