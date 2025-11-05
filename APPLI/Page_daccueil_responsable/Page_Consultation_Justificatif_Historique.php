<?php
session_start();

if (!isset($_SESSION['idUtilisateur']) || $_SESSION['role'] != 'Responsable Pedagogique') {
    header('Location: ../Page_de_connexion/Page_De_Connexion.php');
    exit();
}

require 'Consultation_Justificatif_Historique_PHP.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Historique Justificatif Historique</title>
    <link rel="stylesheet" href="Style_Page_Consultation_Justificatif_Historique.css">
</head>
<body>

<div class="header-main">
    <div class="logo-section">
        <img src="logo_uphf.png" alt="Logo Université Polytechnique">
    </div>
    <div class="header-right">
        <p>ESPACE NUMÉRIQUE DE TRAVAIL</p>
        <a href="index.html" class="bouton-statistique">Statistique</a>
        <a href="../Page_de_connexion/Page_De_Connexion.php" class="bouton-deconnexion">Déconnexion</a>
    </div>
</div>



<div class="container">

    <div class="bouton-retour-wrapper">
        <a href="Page_Accueil_Responsable.php" class="action-button">Retour</a>
    </div>

    <div id="consultation-bloc" class="consultation-bloc-wrapper">
        <div class="consultation-contenu">

            <div class="consultation-corps">
                <div class="details-left">
                    <p class="absence-date">Absent du : <strong><?php echo htmlspecialchars($justificatifDetailsHisto['datedebut_f'] . ' à ' . $justificatifDetailsHisto['heuredebut_f']); ?></strong></p>
                    <p class="absence-date">Au : <strong><?php echo htmlspecialchars($justificatifDetailsHisto['datefin_f'] . ' à ' . $justificatifDetailsHisto['heurefin_f']); ?></strong></p>

                    <p class="student-info-label">Nom : <strong><?php echo htmlspecialchars($justificatifDetailsHisto['nom']); ?></strong></p>
                    <p class="student-info-label">Prénom : <strong><?php echo htmlspecialchars($justificatifDetailsHisto['prénom']); ?></strong></p>
                    <p class="student-info-label">Adresse mail : <strong><?php echo htmlspecialchars($justificatifDetailsHisto['email']); ?></strong></p>

                    <p>Motif (Étudiant) : <?php echo htmlspecialchars($justificatifDetailsHisto['motifeleve'] ?? 'Non spécifié'); ?></p>

                    <p class="comment-label">Commentaire (Étudiant) :</p>
                    <div class="comment-box">
                        <p>
                            <?php echo nl2br(htmlspecialchars($justificatifDetailsHisto['commentaireeleve'] ?? 'Aucun commentaire')); ?>
                        </p>
                    </div>
                </div>

                <div class="separator-line"></div>

                <div class="details-right">
                    <h3 class="detail-title">Justificatif :</h3>
                    <div class="justificatif-buttons">
                        <?php if (!empty($justificatifDetailsHisto['fichier'])) : ?>
                        <? // récupérer le fichier que l'étudiant a déposé?>
                            <a href="../Page_daccueil_etudiante/<?php echo htmlspecialchars($justificatifDetailsHisto['fichier']); ?>" download class="action-button">Télécharger le justificatif</a>
                        <?php else : ?>
                            <p>(Aucun fichier fourni)</p>
                        <?php endif; ?>
                    </div>

                    <p>Motif (Responsable) : <?php echo htmlspecialchars($justificatifDetailsHisto['motifrespon'] ?? 'Non spécifié'); ?></p>

                    <p class="comment-label">Commentaire (Responsable) :</p>
                    <div class="comment-box">
                        <p>
                            <?php echo nl2br(htmlspecialchars($justificatifDetailsHisto['commentairerespon'] ?? 'Aucun commentaire')); ?>
                        </p>
                    </div>

                    <h3 class="finalite-label">Finalité : <span class="<?php echo getStatusClass($justificatifDetailsHisto['statut']); // Ajoute la classe CSS ?>"><?php echo htmlspecialchars(ucfirst($justificatifDetailsHisto['statut'])); // Met la première lettre en majuscule ?></span></h3>
                </div>
            </div>

        </div>
    </div>
</div>

<footer class="main-footer"></footer>

</body>
</html>