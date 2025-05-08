<?php
require "pdoconnexion.php";
session_start();
$pdo = connexion();

// Initialize course badges if not exists
if (!isset($_SESSION['course_badges'])) {
    $_SESSION['course_badges'] = [];
}

// Initialize course progress if not exists
if (!isset($_SESSION['course_progress'])) {
    $_SESSION['course_progress'] = [];
}

// Calculate progress percentage for each course
function calculateProgressPercentage($courseId) {
    if (!isset($_SESSION['course_progress'][$courseId])) {
        return 0;
    }
    
    $progress = $_SESSION['course_progress'][$courseId];
    $completedLessons = array_filter($progress['lessons'] ?? [], fn($v) => $v === true);
    return count($completedLessons) / count($progress['lessons'] ?? [1,2,3]) * 100;
}

// Handle badge addition
if (isset($_GET['add_badge'])) {
    $courseId = $_GET['add_badge'];
    if (!isset($_SESSION['course_badges'][$courseId])) {
        $_SESSION['course_badges'][$courseId] = 1;
    } else {
        $_SESSION['course_badges'][$courseId]++;
    }
}

$sql = "SELECT * FROM cours";
$stm = $pdo->query($sql);
$cours = $stm->fetchAll();
?>


<body>
 <?php include "header.php";?>


    <div class="hero-section">
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title">
                    Explorez Nos <span class="text-gradient">Formations</span> Pour Développer Vos Compétences
                </h1>
                <p class="hero-subtitle">
                    Découvrez notre catalogue de cours interactifs et progressez à votre rythme avec un suivi personnalisé
                </p>
            </div>
        </div>
    </div>
    
    <div class="container courses-container">
        <div class="row g-4 p-4">
            <?php foreach ($cours as $cour): ?>
                <div class="col-lg-4 col-md-6">
                    <div class="card ">
                        
                        <div class="card-image-wrapper">
                            <img src="<?= htmlspecialchars($cour['image']) ?>" class="card-img-top" alt="Image du cours">
                            <?php if (isset($_SESSION['course_badges'][$cour['id']])): ?>
                                <span class="card-badge">
                                    <i class="fas fa-star"></i> <?= $_SESSION['course_badges'][$cour['id']] ?>
                                </span>
                            <?php endif; ?>
                        </div>
                        
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($cour['description']) ?></h5>
                            
                            <div class="d-flex align-items-center mb-3 course-info">
                                <div class="me-3">
                                    <i class="far fa-clock me-1"></i>
                                    <span>3 heures</span>
                                </div>
                                <div>
                                    <i class="far fa-file-alt me-1"></i>
                                    <span>3 leçons</span>
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
                            
                            <div class="d-flex gap-2 mt-3">
                                <a href="progression.php?id=<?= $cour['id'] ?>" class="btn text-dark bg-secondary flex-grow-1 rounded">
                                    <i class="fas fa-play me-2"></i> Commencer
                                </a>
                                <a href="?add_badge=<?= $cour['id'] ?>" class="btn-icon btn-secondary">
                                    <i class="fas fa-star"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    
 <?php include "footer.php";?>
    
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

