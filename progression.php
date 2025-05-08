<?php
session_start();
require "pdoconnexion.php";
$pdo = connexion();

$courseId = isset($_GET['id']) ? $_GET['id'] : null;

// Initialize course progress if not exists
if (!isset($_SESSION['course_progress'])) {
    $_SESSION['course_progress'] = [];
}

if (!isset($_SESSION['course_progress'][$courseId])) {
    $_SESSION['course_progress'][$courseId] = [
        'lessons' => [
            'lesson1' => false,
            'lesson2' => false,
            'lesson3' => false,
        ],
        'quiz_completed' => false,
        'quiz_score' => 0
    ];
}

// Handle lesson completion
if (isset($_GET['complete_lesson'])) {
    $lesson = $_GET['complete_lesson'];
    if (isset($_SESSION['course_progress'][$courseId]['lessons'][$lesson])) {
        $_SESSION['course_progress'][$courseId]['lessons'][$lesson] = true;
    }
}

// Handle quiz submission
if (isset($_POST['submit_quiz'])) {
    $score = 0;
    if (isset($_POST['q1']) && $_POST['q1'] === 'correct') $score++;
    if (isset($_POST['q2']) && $_POST['q2'] === 'correct') $score++;
    if (isset($_POST['q3']) && $_POST['q3'] === 'correct') $score++;
    
    $_SESSION['course_progress'][$courseId]['quiz_completed'] = true;
    $_SESSION['course_progress'][$courseId]['quiz_score'] = $score;
}

$progress = $_SESSION['course_progress'][$courseId];
$completedLessons = array_filter($progress['lessons'], fn($v) => $v === true);
$progressPercentage = (count($completedLessons) / count($progress['lessons'])) * 100;

// Get current course information
$sql = "SELECT * FROM cours WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$courseId]);
$courseInfo = $stmt->fetch(PDO::FETCH_ASSOC);
?>


    <div class="container py-5">
        <div class="row">
            <div class="col-lg-10 col-md-12 mx-auto">
                <div class="course-header">
                    <h1 class="course-title">
                        <?= $courseInfo ? htmlspecialchars($courseInfo['description']) : 'Progression du Cours' ?>
                    </h1>
                    <p>Suivez votre progression et complétez toutes les leçons pour accéder au quiz final</p>
                    <div class="progress-wave"></div>
                </div>
                
                <div class="progress-section">
                    <div class="progress-circle-container">
                        <div class="progress-circle">
                            <svg class="progress-svg" viewBox="0 0 120 120">
                                <defs>
                                    <linearGradient id="progressGradient" x1="0%" y1="0%" x2="100%" y2="0%">
                                        <stop offset="0%" stop-color="rgb(127, 95, 255)" />
                                        <stop offset="100%" stop-color="rgb(255, 95, 149)" />
                                    </linearGradient>
                                </defs>
                                <circle class="progress-circle-bg" cx="60" cy="60" r="54" />
                                <circle class="progress-circle-fill" cx="60" cy="60" r="54" />
                            </svg>
                            <div class="progress-text"><?= round($progressPercentage) ?>%</div>
                        </div>
                    </div>
                    
                    <h2 class="text-center mb-4">Votre Parcours d'Apprentissage</h2>
                    
                    <div class="lessons-list">
                        <?php foreach ($progress['lessons'] as $lesson => $completed): ?>
                            <div class="card lesson-card">
                                <div class="card-body d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <span class="lesson-icon">
                                            <?php
                                            switch($lesson) {
                                                case 'lesson1': echo '<i class="fas fa-book-open"></i>';
                                                break;
                                                case 'lesson2': echo '<i class="fas fa-code"></i>';
                                                break;
                                                case 'lesson3': echo '<i class="fas fa-laptop-code"></i>';
                                                break;
                                            }
                                            ?>
                                        </span>
                                        <div>
                                            <h5 class="card-title mb-0">
                                                <?php
                                                switch($lesson) {
                                                    case 'lesson1': echo 'Introduction aux Concepts';
                                                    break;
                                                    case 'lesson2': echo 'Techniques Fondamentales';
                                                    break;
                                                    case 'lesson3': echo 'Applications Pratiques';
                                                    break;
                                                }
                                                ?>
                                            </h5>
                                            <?php if ($completed): ?>
                                                <small class="text-muted">Complété avec succès</small>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <?php if ($completed): ?>
                                        <span class="lesson-status-completed">
                                            <i class="fas fa-check-circle"></i> Complété
                                        </span>
                                    <?php else: ?>
                                        <a href="?id=<?= $courseId ?>&complete_lesson=<?= $lesson ?>" class="btn-lesson">
                                            <i class="fas fa-play me-2"></i> Commencer
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <?php if (count($completedLessons) === count($progress['lessons'])): ?>
                    <div class="quiz-section">
                        <h3 class="mb-4">Quiz Final: Testez Vos Connaissances</h3>
                        <?php if (!$progress['quiz_completed']): ?>
                            <form method="POST">
                                <div class="quiz-question mb-4">
                                    <h5>Question 1</h5>
                                    <p>Quelle est la réponse correcte à la première question ?</p>
                                    <div class="quiz-options">
                                        <div class="form-check">
                                            <input class="form-check-input d-none" type="radio" name="q1" id="q1a" value="correct" required>
                                            <label class="form-check-label" for="q1a">
                                                <i class="far fa-circle me-2"></i> Réponse correcte
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input d-none" type="radio" name="q1" id="q1b" value="wrong">
                                            <label class="form-check-label" for="q1b">
                                                <i class="far fa-circle me-2"></i> Réponse incorrecte
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="quiz-question mb-4">
                                    <h5>Question 2</h5>
                                    <p>Quelle est la réponse correcte à la deuxième question ?</p>
                                    <div class="quiz-options">
                                        <div class="form-check">
                                            <input class="form-check-input d-none" type="radio" name="q2" id="q2a" value="correct" required>
                                            <label class="form-check-label" for="q2a">
                                                <i class="far fa-circle me-2"></i> Réponse correcte
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input d-none" type="radio" name="q2" id="q2b" value="wrong">
                                            <label class="form-check-label" for="q2b">
                                                <i class="far fa-circle me-2"></i> Réponse incorrecte
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="quiz-question mb-4">
                                    <h5>Question 3</h5>
                                    <p>Quelle est la réponse correcte à la troisième question ?</p>
                                    <div class="quiz-options">
                                        <div class="form-check">
                                            <input class="form-check-input d-none" type="radio" name="q3" id="q3a" value="correct" required>
                                            <label class="form-check-label" for="q3a">
                                                <i class="far fa-circle me-2"></i> Réponse correcte
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input d-none" type="radio" name="q3" id="q3b" value="wrong">
                                            <label class="form-check-label" for="q3b">
                                                <i class="far fa-circle me-2"></i> Réponse incorrecte
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-center mt-5">
                                    <button type="submit" name="submit_quiz" class="btn-submit-quiz">
                                        <i class="fas fa-paper-plane me-2"></i> Soumettre le Quiz
                                    </button>
                                </div>
                            </form>
                        <?php else: ?>
                            <div class="text-center p-4">
                                <div class="mb-3">
                                    <i class="fas fa-trophy trophy-icon"></i>
                                </div>
                                <h4 class="mb-3">Quiz Terminé avec Succès!</h4>
                                <div class="quiz-score p-3 mb-4">
                                    <h5 class="mb-0">Votre score: <span class="text-success"><?= $progress['quiz_score'] ?>/3</span></h5>
                                </div>
                                
                                <?php if ($progress['quiz_score'] >= 2): ?>
                                    <div class="mt-3">
                                        <div class="alert alert-success border-0 shadow-sm">
                                            <h5><i class="fas fa-medal me-2 text-warning"></i> Félicitations!</h5>
                                            <p class="mb-0">Vous avez réussi le quiz et maîtrisé le contenu du cours.</p>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="mt-3">
                                        <p>Vous pouvez faire mieux! Essayez à nouveau.</p>
                                        <a href="?id=<?= $courseId ?>" class="btn btn-warning">
                                            <i class="fas fa-redo me-2"></i> Réessayer le Quiz
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <div class="text-center mt-4">
                    <a href="cours.php" class="btn-back">
                        <i class="fas fa-arrow-left me-2"></i> Retour aux Cours
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
    <script>
        // Make radio buttons work with custom labels
        document.querySelectorAll('.quiz-options label').forEach(label => {
            label.addEventListener('click', function() {
                const input = document.getElementById(this.getAttribute('for'));
                input.checked = true;
                
                // Update icon
                const icon = this.querySelector('i');
                if (icon) {
                    // Reset all icons in the same question group
                    this.closest('.quiz-options').querySelectorAll('i').forEach(i => {
                        i.className = 'far fa-circle me-2';
                    });
                    // Set checked icon
                    icon.className = 'fas fa-check-circle me-2';
                }
            });
        });

        // Set progress circle
        document.addEventListener('DOMContentLoaded', function() {
            const progressCircle = document.querySelector('.progress-circle-fill');
            if (progressCircle) {
                const progressPercentage = <?= $progressPercentage ?>;
                const dashArray = 339.292;
                const dashOffset = dashArray - (dashArray * progressPercentage) / 100;
                progressCircle.style.strokeDashoffset = dashOffset;
                
                // Change color based on progress percentage
                if (progressPercentage === 0) {
                    // No progress - show light gray
                    progressCircle.style.stroke = '#E0E0E0';
                } else if (progressPercentage < 33) {
                    // Less than 33% - show a gradient that's more blue/purple
                    progressCircle.style.stroke = 'rgb(127, 95, 255)';
                }
                // Otherwise, use the default gradient from CSS
            }
        });
    </script>
</body>
</html>
