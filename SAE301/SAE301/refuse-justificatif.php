<?php
session_start();

// Initialiser la liste des motifs de refus si elle n'existe pas
if (!isset($_SESSION['refus_motifs'])) {
    $_SESSION['refus_motifs'] = [
            'Absence non justifiée',
            'Justificatif non valide',
            'Motif non recevable',
            'Faux document'
    ];
}

$justif_id = isset($_GET['id']) ? (int)$_GET['id'] : null;
$error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';
$new_motif_mode = isset($_GET['new_motif']) ? true : false;

unset($_SESSION['error_message']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Refuser Justificatif</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="modal.css">
</head>
<body class="modal-body">
<div class="modal-simulated-content refuse-validate-modal">

    <a href="dashboard.php" class="retour-btn retour-top-right">Retour</a>

    <?php if ($new_motif_mode): ?>
        <h2>Créer un motif de refus</h2>
        <form action="handle-action.php" method="post" class="reason-form">
            <input type="hidden" name="action" value="new_refus_motif">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($justif_id); ?>">
            <div class="form-group">
                <label for="new_motif">Nom du nouveau motif :</label>
                <input type="text" id="new_motif" name="new_motif" required>
                <?php if ($error_message) : ?>
                    <span class="error-message"><?php echo htmlspecialchars($error_message); ?></span>
                <?php endif; ?>
            </div>
            <div class="action-modal-btns">
                <a href="refuse-justificatif.php?id=<?php echo htmlspecialchars($justif_id); ?>" class="retour-btn">Annuler</a>
                <button type="submit" class="confirm-btn create-motif-btn">Ajouter le motif</button>
            </div>
        </form>
    <?php else: ?>
        <h2>Voulez-vous refuser le justificatif ?</h2>

        <form action="handle-action.php" method="post" class="reason-form">
            <input type="hidden" name="action" value="reject">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($justif_id); ?>">

            <div class="refuse-grid">
                <div class="grid-item motif-selection">
                    <div class="form-group">
                        <label for="rejectMotive">Sélectionnez un motif :</label>
                        <select id="rejectMotive" name="motif" required>
                            <option value="">Sélectionnez un motif</option>
                            <?php foreach ($_SESSION['refus_motifs'] as $motif) : ?>
                                <option value="<?php echo htmlspecialchars($motif); ?>"><?php echo htmlspecialchars($motif); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?php if ($error_message) : ?>
                            <span class="error-message"><?php echo htmlspecialchars($error_message); ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="action-footer-grid">
                        <a href="refuse-justificatif.php?id=<?php echo htmlspecialchars($justif_id); ?>&new_motif=1"
                           class="create-motif-btn">Créer un motif</a>

                        <button type="submit" class="confirm-btn refuse-btn-action">Valider</button>
                    </div>
                </div>

                <div class="grid-item comment-area">
                    <div class="form-group">
                        <label for="comment">Commentaire :</label>
                        <textarea id="comment" name="comment" rows="8" placeholder="Écrivez un commentaire... (facultatif)"></textarea>
                    </div>
                </div>
            </div>
        </form>
    <?php endif; ?>
</div>
</body>
</html>