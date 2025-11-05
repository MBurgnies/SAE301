<?php
session_start();

// vérifier si l'utilisateur s'est connecté
if (!isset($_SESSION['idUtilisateur']) || $_SESSION['role'] != 'Responsable Pedagogique') {
    header('Location: ../Page_de_connexion/Page_De_Connexion.php');
    exit();
}

$notificationMessage = '';
$notificationType = '';

// vérifier si un paramètre 'traitement' est présent dans l'URL (pour les messages de validations, refus et demande de révisions
if (isset($_GET['traitement'])) {
    switch ($_GET['traitement']) {
        case 'succes':
            $notificationMessage = 'Le justificatif a été validé avec succès.';
            $notificationType = 'succes';
            break;
        case 'refuse':
            $notificationMessage = 'Le justificatif a été refusé.';
            $notificationType = 'refuse';
            break;
        case 'revision':
            $notificationMessage = 'La demande de révision a été envoyée.';
            $notificationType = 'revision';
            break;
    }
}


require 'Tableau_Justificatif_Responsable.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de bord Responsable</title>
    <link rel="stylesheet" href="Style_Page_Accueil_Responsable.css">
</head>

<body>

<?php
// afficher la div "toast" seulement si un message existe
if (!empty($notificationMessage)) {
    echo '<div id="toast" class="toast-notification ' . htmlspecialchars($notificationType) . '">';
    echo htmlspecialchars($notificationMessage);
    echo '</div>';
}
?>
<div class="header-main">
    <div class="logo-section">
        <img src="logo_uphf.png" alt="Logo Université Polytechnique">
    </div>

    <div class="header-right">
        <p>ESPACE NUMÉRIQUE DE TRAVAIL</p>
        <a href="Page_Statistique_Accueil.php" class="bouton-statistique">Statistique</a>
        <a href="../Page_de_connexion/Page_De_Connexion.php" class="bouton-deconnexion">Déconnexion</a>
    </div>
</div>

<div class="container">
    <div class="dashboard-content-wrapper">

        <h1 class="titre-tableau-de-bord">Tableau De Bord</h1>

        <div class="content-tables-container">

            <section class="section-justificatifs">
                <h2>Justificatifs en attente</h2>

                <div class="table-container">
                    <table class="tableau">
                        <thead>
                        <tr>
                            <th>Du</th>
                            <th>À</th>
                            <th>Au</th>
                            <th>À</th>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Groupe</th>
                            <th>Consulter</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if (empty($lesjustificatifs)) : ?>
                            <tr class="empty-table-message">
                                <td colspan="8">Aucun justificatif en attente.</td>
                            </tr>
                        <?php else : ?>
                            <?php foreach ($lesjustificatifs as $justif) : ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($justif['datededebut']); ?></td>
                                    <td><?php echo htmlspecialchars($justif['heuredebut']); ?></td>
                                    <td><?php echo htmlspecialchars($justif['datedefin']); ?></td>
                                    <td><?php echo htmlspecialchars($justif['heurefin']); ?></td>
                                    <td><?php echo htmlspecialchars($justif['nom']); ?></td>
                                    <td><?php echo htmlspecialchars($justif['prénom']); ?></td>
                                    <td><?php echo htmlspecialchars($justif['groupe']); ?></td>
                                    <td>
                                        <a href="Page_Consultation_Justificatif_En_Attente.php?id=<?php echo htmlspecialchars($justif['idjustificatif']); ?>" class="action-button">Consulter</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>

            <section class="section-historique">
                <h2>Historique</h2>

                <div class="table-container">
                    <table class="tableau historique">
                        <thead>
                        <tr>
                            <th>Du</th>
                            <th>À</th>
                            <th>Au</th>
                            <th>À</th>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Groupe</th>
                            <th>Consulter</th>
                            <th>Déverrouiller</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if (empty($lesjustificatifsHisto)) : ?>
                            <tr class="empty-table-message">
                                <td colspan="9">Aucun justificatif dans l'historique.</td>
                            </tr>
                        <?php else : ?>
                            <?php foreach ($lesjustificatifsHisto as $justif) : ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($justif['datededebut']); ?></td>
                                    <td><?php echo htmlspecialchars($justif['heuredebut']); ?></td>
                                    <td><?php echo htmlspecialchars($justif['datedefin']); ?></td>
                                    <td><?php echo htmlspecialchars($justif['heurefin']); ?></td>
                                    <td><?php echo htmlspecialchars($justif['nom']); ?></td>
                                    <td><?php echo htmlspecialchars($justif['prénom']); ?></td>
                                    <td><?php echo htmlspecialchars($justif['groupe']); ?></td>
                                    <td>
                                        <a href="Page_Consultation_Justificatif_Historique.php?id=<?php echo htmlspecialchars($justif['idjustificatif']); ?>" class="action-button">Consulter</a>
                                    </td>
                                    <td>
                                        <a href="Page_Confirmation_Deverouillage.php?id=<?php echo htmlspecialchars($justif['idjustificatif']); ?>" class="action-button">Déverrouiller</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>

        </div>
    </div>
</div>
<footer class="main-footer"></footer>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // on cherche la notification "toast"
        const toast = document.getElementById('toast');

        if (toast) {
            // on attend 4 secondes
            setTimeout(function() {

                // on ajoute la classe "fade-out" pour lancer l'animation de disparition
                toast.classList.add('fade-out');

                // on supprime l'élément du DOM après la fin de l'animation
                setTimeout(function() {
                    if (toast.parentNode) {
                        toast.parentNode.removeChild(toast);
                    }
                }, 1000);

            }, 4000);
        }
    });
</script>

</body>
</html>