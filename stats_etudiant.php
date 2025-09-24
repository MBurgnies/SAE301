<?php
// Simulation de la récupération des données de l'étudiant via l'ID passé en URL
$etudiant_id = isset($_GET['id']) ? $_GET['id'] : 'inconnu';
$etudiant_nom = 'Enzo Lewandowski --Bry'; // Exemple pour la démo
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistiques étudiant - Responsable Pédagogique</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<header class="main-header">
    <div class="logo-container">
        <img src="images/logo.png" alt="Université Polytechnique Hauts-de-France Logo">
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
        <a href="statistiques.php" class="nav-btn">Statistique</a>
        <a href="index.php" class="nav-btn">Déconnexion</a>
    </nav>
    <div class="yellow-line"></div>
</header>

<main class="stats-etudiant-main">
    <h1>Statistiques étudiant : <?php echo htmlspecialchars($etudiant_nom); ?></h1>

    <div class="stats-graphs-container">
        <div class="graph-box">
            <div class="graph-header">
                <h3>Tendances d'absences par ressources</h3>
                <div class="radio-group">
                    <label><input type="radio" name="tendance" checked> Tendances par semestre</label>
                    <label><input type="radio" name="tendance"> Tendances par année</label>
                </div>
            </div>
            <img src="images/graphique-ligne.png" alt="Graphique des tendances d'absences" class="graph-image">
        </div>

        <div class="graph-box">
            <div class="graph-header">
                <h3>Répartition des absences par CM, TD, TP</h3>
                <div class="radio-group">
                    <label><input type="radio" name="repartition1"> Répartition par semestre</label>
                    <label><input type="radio" name="repartition1" checked> Répartition par année</label>
                </div>
            </div>
            <img src="images/graphique-camembert1.png" alt="Répartition des absences par type de cours" class="graph-image">
        </div>

        <div class="graph-box">
            <div class="graph-header">
                <h3>Répartition des absences par ressource</h3>
                <div class="radio-group">
                    <label><input type="radio" name="repartition2"> Répartition par semestre</label>
                    <label><input type="radio" name="repartition2" checked> Répartition par année</label>
                </div>
            </div>
            <img src="images/graphique-camembert2.png" alt="Répartition des absences par ressource" class="graph-image">
        </div>
    </div>
</main>
</body>
</html>