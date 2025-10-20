<?php
session_start();

// Données des étudiants (simulées)
$etudiants = [
        ['id' => 1, 'nom' => 'Enzo LeGrand'],
        ['id' => 2, 'nom' => 'Arthus Baillon'],
        ['id' => 3, 'nom' => 'Léon Marchand'],
        ['id' => 4, 'nom' => 'Zinedine Zidane'],
        ['id' => 5, 'nom' => 'Enzo Lewandowski --Bry'],
];

$rattrapages = [
        ['date' => '06/05/2025', 'heure' => '9h30 à 11h', 'etudiant' => 'Enzo LeGrand', 'enseignant' => 'BELLOUM RAFIK'],
        ['date' => '06/05/2025', 'heure' => '9h30 à 11h', 'etudiant' => 'Arthus Baillon', 'enseignant' => 'BELLOUM RAFIK'],
        ['date' => '06/05/2025', 'heure' => '9h30 à 11h', 'etudiant' => 'Léon Marchand', 'enseignant' => 'BELLOUM RAFIK'],
        ['date' => '06/05/2025', 'heure' => '9h30 à 11h', 'etudiant' => 'Zinedine Zidane', 'enseignant' => 'BELLOUM RAFIK'],
];


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['etudiant_id'])) {
    $etudiant_id = (int)$_POST['etudiant_id'];
    header("Location: stats_etudiant.php?id=" . $etudiant_id);
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistiques - Responsable Pédagogique</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="statistiques.css">
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
        <a href="index.php" class="nav-btn">Déconnexion</a>
    </nav>
    <div class="yellow-line"></div>
</header>

<main class="stats-main">
    <div class="stats-container">
        <section class="rattrapages-section">
            <h1>Liste des rattrapages</h1>
            <div class="table-wrapper">
                <table>
                    <thead>
                    <tr>
                        <th>Date et heure du contrôle</th>
                        <th>Étudiant</th>
                        <th>Enseignant</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($rattrapages as $rattrapage) : ?>
                        <tr>
                            <td>Le <?php echo htmlspecialchars($rattrapage['date']); ?> de <?php echo htmlspecialchars($rattrapage['heure']); ?></td>
                            <td><?php echo htmlspecialchars($rattrapage['etudiant']); ?></td>
                            <td><?php echo htmlspecialchars($rattrapage['enseignant']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <section class="stats-box-container">
            <div class="stats-box">
                <h3>Nombre de rattrapages par ressource</h3>
                <div class="select-wrapper">
                    <select name="ressource" id="ressource-select">
                        <option value="">Choisir une ressource</option>
                        <option value="R4.01">R4.01 - Développement Web</option>
                        <option value="R4.02">R4.02 - Base de données</option>
                        <option value="R4.03">R4.03 - Réseaux</option>
                    </select>
                </div>
                <p>Il y a <span id="rattrapages-count">4</span> rattrapage(s) à faire passer.</p>
            </div>

            <div class="stats-box">
                <h3>Consulter les statistiques d'un étudiant</h3>
                <form action="statistiques.php" method="post" class="etudiant-select-form">
                    <div class="select-wrapper">
                        <select name="etudiant_id" id="etudiant-select" required>
                            <option value="">Sélectionner un étudiant</option>
                            <?php foreach ($etudiants as $etudiant) : ?>
                                <option value="<?php echo htmlspecialchars($etudiant['id']); ?>">
                                    <?php echo htmlspecialchars($etudiant['nom']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="stats-btn">Voir les statistiques</button>
                </form>
            </div>
        </section>
    </div>
</main>

<script>

    document.getElementById('ressource-select').addEventListener('change', function() {
        const countElement = document.getElementById('rattrapages-count');
        if (this.value) {
            countElement.textContent = Math.floor(Math.random() * 5) + 1; // Simulation
        } else {
            countElement.textContent = '4';
        }
    });
</script>
</body>
</html>