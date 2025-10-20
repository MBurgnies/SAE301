<?php
session_start();


if (!isset($_SESSION['justifications_attente']) || !isset($_SESSION['justifications_historique'])) {
    $_SESSION['justifications_attente'] = [
            ['date' => '06/05/2025', 'etudiant' => 'Enzo LeGrand', 'nom' => 'LeGrand', 'prenom' => 'Enzo', 'email' => 'enzo.legrand@uphf.fr', 'id' => 1, 'commentaire' => 'Problème familial urgent.', 'statut' => 'En attente'],
            ['date' => '06/05/2025', 'etudiant' => 'Arthus Baillon', 'nom' => 'Baillon', 'prenom' => 'Arthus', 'email' => 'arthus.baillon@uphf.fr', 'id' => 2, 'commentaire' => 'Rendez-vous médical imprévu.', 'statut' => 'En attente'],
            ['date' => '06/05/2025', 'etudiant' => 'Léon Marchand', 'nom' => 'Marchand', 'prenom' => 'Léon', 'email' => 'leon.marchand@uphf.fr', 'id' => 3, 'commentaire' => 'Contrôle technique du véhicule.', 'statut' => 'En attente'],
            ['date' => '06/05/2025', 'etudiant' => 'Zinedine Zidane', 'nom' => 'Zidane', 'prenom' => 'Zinedine', 'email' => 'zinedine.zidane@uphf.fr', 'id' => 4, 'commentaire' => 'Grève des transports.', 'statut' => 'En attente'],
            ['date' => '06/05/2025', 'etudiant' => 'Enzo LeGrand', 'nom' => 'LeGrand', 'prenom' => 'Enzo', 'email' => 'enzo.legrand@uphf.fr', 'id' => 5, 'commentaire' => 'Absence suite à une convocation.', 'statut' => 'En attente'],
    ];
    $_SESSION['justifications_historique'] = [
            ['date' => '01/05/2025', 'etudiant' => 'Zinedine Zidane', 'nom' => 'Zidane', 'prenom' => 'Zinedine', 'email' => 'zinedine.zidane@uphf.fr', 'id' => 10, 'statut' => 'Accepter', 'commentaire' => 'Validé suite à un justificatif médical clair.', 'motif' => 'Validé (Automatique)'],
            ['date' => '28/04/2025', 'etudiant' => 'Léon Marchand', 'nom' => 'Marchand', 'prenom' => 'Léon', 'email' => 'leon.marchand@uphf.fr', 'id' => 11, 'statut' => 'Refuser', 'commentaire' => 'Non respect du délai de dépôt.', 'motif' => 'Délai dépassé'],
            ['date' => '25/04/2025', 'etudiant' => 'Arthus Baillon', 'nom' => 'Baillon', 'prenom' => 'Arthus', 'email' => 'arthus.baillon@uphf.fr', 'id' => 12, 'statut' => 'Révision demandée', 'commentaire' => 'Pièce jointe illisible, demander une nouvelle version.', 'motif' => 'Pièce illisible'],
    ];

    $_SESSION['refus_motifs'] = [
            'Absence non justifiée',
            'Justificatif non valide',
            'Motif non recevable',
            'Faux document'
    ];
}

$justifications_attente = $_SESSION['justifications_attente'];
$justifications_historique = $_SESSION['justifications_historique'];


$message = isset($_SESSION['message']) ? $_SESSION['message'] : '';
unset($_SESSION['message']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord - Responsable Pédagogique</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="dashboard.css">
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
        <a href="statistiques.php" class="nav-btn">Statistique</a>
        <a href="index.php" class="nav-btn">Déconnexion</a>
    </nav>
    <div class="yellow-line"></div>
</header>

<main class="dashboard-main">
    <h1>Tableau de bord - Gestion des justificatifs</h1>

    <?php if ($message) : ?>
        <p class="system-message"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <div class="table-grid">
        <section class="table-section attente-section">
            <h2>Justificatifs en attente</h2>
            <div class="table-wrapper">
                <table>
                    <thead>
                    <tr>
                        <th>Date</th>
                        <th>Étudiant</th>
                        <th></th> </tr>
                    </thead>
                    <tbody id="justificatifs-attente-body">
                    <?php if (empty($justifications_attente)): ?>
                        <tr><td colspan="3" style="text-align: center;">Aucun justificatif en attente.</td></tr>
                    <?php else: ?>
                        <?php foreach ($justifications_attente as $justif) : ?>
                            <tr data-id="<?php echo htmlspecialchars($justif['id']); ?>">
                                <td><?php echo htmlspecialchars($justif['date']); ?></td>
                                <td><?php echo htmlspecialchars($justif['etudiant']); ?></td>
                                <td><a href="view-justificatif.php?id=<?php echo htmlspecialchars($justif['id']); ?>&type=attente" class="consult-btn">Consulter</a></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <section class="table-section historique-section">
            <h2>Historique</h2>
            <div class="table-wrapper">
                <table>
                    <thead>
                    <tr>
                        <th>Date</th>
                        <th>Étudiant</th>
                        <th>Statut</th>
                        <th></th> </tr>
                    </thead>
                    <tbody id="justificatifs-historique-body">
                    <?php if (empty($justifications_historique)): ?>
                        <tr><td colspan="4" style="text-align: center;">Aucun justificatif dans l'historique.</td></tr>
                    <?php else: ?>
                        <?php foreach ($justifications_historique as $justif) : ?>
                            <tr data-id="<?php echo htmlspecialchars($justif['id']); ?>">
                                <td><?php echo htmlspecialchars($justif['date']); ?></td>
                                <td><?php echo htmlspecialchars($justif['etudiant']); ?></td>
                                <td class="statut-<?php echo strtolower(str_replace(' ', '-', htmlspecialchars($justif['statut']))); ?>"><?php echo htmlspecialchars($justif['statut']); ?></td>
                                <td>
                                    <a href="view-justificatif.php?id=<?php echo htmlspecialchars($justif['id']); ?>&type=historique" class="consult-btn historique">Consulter</a>
                                    <a href="unlock-justificatif.php?id=<?php echo htmlspecialchars($justif['id']); ?>" class="unlock-btn">Déverrouiller</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</main>
</body>
</html>