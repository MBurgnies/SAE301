<?php
session_start();

$justif_id = isset($_GET['id']) ? (int)$_GET['id'] : null;

$justificatif = [
        'id' => $justif_id,
        'etudiant_nom' => 'Lewandowski',
        'etudiant_prenom' => 'Enzo',
        'etudiant_email' => 'Enzo.Lewan@uphf.fr',
        'date_demande' => '2025-09-25',
        'statut' => 'En attente',
        'date_absence' => '2025-09-20',
        'motif_etudiant' => 'Rendez-vous médical urgent.',
        'commentaire_etudiant' => 'J\'ai joint une copie de mon certificat médical. Désolé pour l\'absence.',
];



?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulter Justificatif #<?php echo htmlspecialchars($justif_id); ?></title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="modal.css">
    <link rel="stylesheet" href="view-justificatif.css">
</head>
<body class="modal-view-body">

<div class="view-justificatif-main">
    <div class="modal-header">
        Détails du Justificatif #<?php echo htmlspecialchars($justif_id); ?>
        <a href="dashboard.php" class="close-modal-btn">&times;</a>
    </div>

    <div class="justificatif-details-container">

        <div class="details-section">
            <h3 class="section-title">Informations Générales</h3>
            <p>Nom : <span><?php echo htmlspecialchars($justificatif['etudiant_nom']); ?></span></p>
            <p>Prénom : <span><?php echo htmlspecialchars($justificatif['etudiant_prenom']); ?></span></p>
            <p>Adresse email : <span><?php echo htmlspecialchars($justificatif['etudiant_email']); ?></span></p>
            <p>Statut : <span class="statut-révision-demandée"><?php echo htmlspecialchars($justificatif['statut']); ?></span></p>
            <p>Date d'absence : <span><?php echo htmlspecialchars($justificatif['date_absence']); ?></span></p>
            <p>Date de la demande : <span><?php echo htmlspecialchars($justificatif['date_demande']); ?></span></p>
            <p>Motif déclaré : <span><?php echo htmlspecialchars($justificatif['motif_etudiant']); ?></span></p>

            <h3 class="section-title student-comment">Commentaire Étudiant</h3>
            <div class="comment-section student-comment">
                <textarea readonly><?php echo htmlspecialchars($justificatif['commentaire_etudiant']); ?></textarea>
            </div>
        </div>

        <div class="info-section">
            <h3 class="section-title">Fichier Joint</h3>

            <div class="file-actions">
                <a  class="file-btn download-btn" download>Télécharger</a>
                <button class="file-btn preview-btn" onclick="alert('Prévisualisation du document: Non implémenté dans la démo.');">Prévisualiser</button>
            </div>
        </div>
    </div>

    <div class="justificatif-actions-footer">
        <div class="justificatif-actions" style="justify-content: center;">

            <a href="valider-justificatif.php?id=<?php echo htmlspecialchars($justif_id); ?>"
               class="action-btn validate-btn">
                Valider
            </a>

            <a href="refuse-justificatif.php?id=<?php echo htmlspecialchars($justif_id); ?>"
               class="action-btn refuse-btn">
                Refuser
            </a>

            <button class="action-btn revision-btn" onclick="alert('Fonctionnalité Demander une révision non implémentée.');">
                Demander une révision
            </button>
        </div>
    </div>
</div>

</body>
</html>