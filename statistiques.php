<?php
// Simulation de données statiques pour la liste des rattrapages
$rattrapages = [
        ['date' => '06/05/2025', 'heure' => '9h30 à 11h', 'etudiant' => 'Enzo LeGrand', 'enseignant' => 'BELLOUM RAFIK'],
        ['date' => '06/05/2025', 'heure' => '9h30 à 11h', 'etudiant' => 'Arthus Baillon', 'enseignant' => 'BELLOUM RAFIK'],
        ['date' => '06/05/2025', 'heure' => '9h30 à 11h', 'etudiant' => 'Léon Marchand', 'enseignant' => 'BELLOUM RAFIK'],
        ['date' => '06/05/2025', 'heure' => '9h30 à 11h', 'etudiant' => 'Zinedine Zidane', 'enseignant' => 'BELLOUM RAFIK'],
];

// Liste des étudiants pour la sélection
$etudiants = [
        ['id' => 'enzo_legrand', 'nom' => 'Enzo LeGrand'],
        ['id' => 'arthus_baillon', 'nom' => 'Arthus Baillon'],
        ['id' => 'leon_marchand', 'nom' => 'Léon Marchand'],
        ['id' => 'zinedine_zidane', 'nom' => 'Zinedine Zidane'],
];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistiques - Responsable Pédagogique</title>
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
                    </select>
                </div>
                <p>Il y a <span id="rattrapages-count">X</span> rattrapage(s) à faire passer.</p>
            </div>
            <div class="stats-btn-container">
                <button id="consult-stats-btn" class="stats-btn">Consulter les statistiques d'un étudiant</button>
            </div>
        </section>
    </div>
</main>

<div id="selectEtudiantModal" class="modal">
    <div class="modal-content small-modal">
        <span class="close-btn">&times;</span>
        <div class="select-etudiant-form">
            <select id="etudiant-select">
                <option value="">Sélectionner un étudiant</option>
                <?php foreach ($etudiants as $etudiant) : ?>
                    <option value="<?php echo htmlspecialchars($etudiant['id']); ?>"><?php echo htmlspecialchars($etudiant['nom']); ?></option>
                <?php endforeach; ?>
            </select>
            <button class="confirm-btn" id="confirmEtudiantBtn">Confirmer</button>
        </div>
    </div>
</div>

<script src="script.js"></script>
</body>
</html>