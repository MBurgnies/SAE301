<?php
session_start();


$etudiant_id = isset($_GET['id']) ? (int)$_GET['id'] : null;


$etudiants = [
        1 => ['id' => 1, 'nom' => 'Enzo LeGrand'],
        2 => ['id' => 2, 'nom' => 'Arthus Baillon'],
        3 => ['id' => 3, 'nom' => 'Léon Marchand'],
        4 => ['id' => 4, 'nom' => 'Zinedine Zidane'],
        5 => ['id' => 5, 'nom' => 'Enzo Lewandowski --Bry'],
];


$etudiant_nom = 'Étudiant non trouvé';
if ($etudiant_id && isset($etudiants[$etudiant_id])) {
    $etudiant_nom = $etudiants[$etudiant_id]['nom'];
}


$stats_etudiants = [
        1 => ['absences' => 3, 'moyenne' => 12.5],
        2 => ['absences' => 1, 'moyenne' => 14.2],
        3 => ['absences' => 5, 'moyenne' => 10.8],
        4 => ['absences' => 2, 'moyenne' => 13.7],
        5 => ['absences' => 4, 'moyenne' => 11.9],
];

$stats = isset($stats_etudiants[$etudiant_id]) ? $stats_etudiants[$etudiant_id] : ['absences' => 0, 'moyenne' => 0];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistiques étudiant - Responsable Pédagogique</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="statistiques.css">
</head>
<body>
<header class="main-header">
    <div class="logo-container">
        <img src="Images/logo.png" alt="Université Polytechnique Hauts-de-France Logo">
        <div class="logo-text-block">
            <span>Université</span>
            <span>Polytechnique</span>
            <span>HAUTS-DE-FRANCE</span>
        </div>
        <div class="logo-right-block">
            <span>ESPACE</span>
            <span>NUMÉRIQUE DE</span>
            <span>TRAVAIL</span>
        </div>
    </div>
    <nav class="top-nav">
        <a href="dashboard.php" class="nav-btn">Tableau de bord</a>
        <a href="statistiques.php" class="nav-btn">Statistiques</a>
        <a href="index.php" class="nav-btn">Déconnexion</a>
    </nav>
    <div class="yellow-line"></div>
</header>

<main class="stats-etudiant-main">
    <h1>Statistiques étudiant : <?php echo htmlspecialchars($etudiant_nom); ?></h1>

    <!-- Résumé des statistiques -->
    <div class="stats-resume">
        <div class="stats-resume-grid">
            <div class="resume-item">
                <h3>Nombre d'absences</h3>
                <p class="resume-value"><?php echo htmlspecialchars($stats['absences']); ?></p>
            </div>
            <div class="resume-item">
                <h3>Moyenne générale</h3>
                <p class="resume-value"><?php echo htmlspecialchars($stats['moyenne']); ?>/20</p>
            </div>
        </div>
    </div>

    <div class="stats-graphs-container">
        <div class="graph-box">
            <div class="graph-header">
                <h3>Tendances d'absences par ressources</h3>
                <div class="radio-group">
                    <label><input type="radio" name="tendance" checked> Tendances par semestre</label>
                    <label><input type="radio" name="tendance"> Tendances par année</label>
                </div>
            </div>
        </div>

        <div class="graph-box">
            <div class="graph-header">
                <h3>Répartition des absences par CM, TD, TP</h3>
                <div class="radio-group">
                    <label><input type="radio" name="repartition1"> Répartition par semestre</label>
                    <label><input type="radio" name="repartition1" checked> Répartition par année</label>
                </div>
            </div>
        </div>

        <div class="graph-box">
            <div class="graph-header">
                <h3>Répartition des absences par ressource</h3>
                <div class="radio-group">
                    <label><input type="radio" name="repartition2"> Répartition par semestre</label>
                    <label><input type="radio" name="repartition2" checked> Répartition par année</label>
                </div>
            </div>
        </div>
    </div>

    <div class="back-button-container">
        <a href="statistiques.php" class="stats-btn">← Retour aux statistiques</a>
    </div>
</main>
</body>
</html>