<?php
session_start();
$justif_id = isset($_GET['id']) ? (int)$_GET['id'] : null;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Déverrouiller Justificatif</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="modal.css">
</head>
<body class="modal-body">
<div class="modal-simulated-content">

    <a href="dashboard.php" class="close-modal-x">&times;</a>

    <h2>Déverrouiller le justificatif</h2>

    <p>Êtes-vous sûr de vouloir déverrouiller l'absence ? Celle-ci sera de nouveau dans la liste des absences à justifier.</p>

    <div class="action-modal-btns">
        <a href="handle-action.php?action=unlock&id=<?php echo htmlspecialchars($justif_id); ?>" class="confirm-btn action-seul">Confirmer Déverrouillage</a>
    </div>
</div>
</body>
</html>