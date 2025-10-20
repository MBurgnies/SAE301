<?php
session_start();


if (!isset($_SESSION['accept_motifs'])) {
    $_SESSION['accept_motifs'] = [
        'Validé',
        'Justificatif reçu'
    ];
}

$justif_id = isset($_GET['id']) ? (int)$_GET['id'] : null;

$error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';
unset($_SESSION['error_message']);

// Simuler un mode pour créer un nouveau motif d'acceptation
$new_motif_mode = isset($_GET['new_motif']) ? true : false;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Valider Justificatif</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="modal.css">
</head>
<body class="modal-body">
<div class="modal-simulated-content refuse-validate-modal">

    <a href="dashboard.php" class="retour-btn retour-top-right">Retour</a>

    <?php if ($new_motif_mode): ?>
        <h2>Créer un motif de validation</h2>
        <form action="handle-action.php" method="post" class="reason-form">
            <input type="hidden" name="action" value="new_accept_motif">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($justif_id); ?>">
            <div class="form-group">
                <label for="new_motif">Nom du nouveau motif :</label>
                <input type="text" id="new_motif" name="new_motif" required>
                <?php if ($error_message) : ?>
                    <span class="error-message"><?php echo htmlspecialchars($error_message); ?></span>
                <?php endif; ?>
            </div>
            <div class="action-modal-btns">
                <a href="valider-justificatif.php?id=<?php echo htmlspecialchars($justif_id); ?>" class="retour-btn">Annuler</a>
                <button type="submit" class="confirm-btn create-motif-btn validate-btn">Ajouter le motif</button>
            </div>
        </form>
    <?php else: ?>

        <h2>Voulez-vous valider le justificatif ?</h2>

        <form action="handle-action.php" method="post" class="reason-form">
            <input type="hidden" name="action" value="accept">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($justif_id); ?>">

            <div class="refuse-grid">
                <!-- Colonne de gauche - Motif -->
                <div class="grid-item motif-selection">
                    <div class="form-group">
                        <label for="acceptMotive">Motif :</label>
                        <select id="acceptMotive" name="motif_accept" required>
                            <option value="">Sélectionnez un motif</option>
                            <?php foreach ($_SESSION['accept_motifs'] as $motif) : ?>
                                <option value="<?php echo htmlspecialchars($motif); ?>"><?php echo htmlspecialchars($motif); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <?php if ($error_message) : ?>
                        <span class="error-message"><?php echo htmlspecialchars($error_message); ?></span>
                    <?php endif; ?>

                    <div class="action-footer-grid">
                        <a href="valider-justificatif.php?id=<?php echo htmlspecialchars($justif_id); ?>&new_motif=1"
                           class="confirm-btn create-motif-btn">Créer un motif</a>

                        <button type="submit" class="validate-btn">Valider</button>
                    </div>
                </div>


                <div class="grid-item comment-area">
                    <div class="form-group">
                        <label for="comment">Commentaire (Facultatif) :</label>
                        <textarea id="comment" name="comment" rows="8" placeholder="Écrivez un commentaire... (facultatif)"></textarea>
                    </div>
                </div>
            </div>
        </form>

    <?php endif; ?>
</div>
</body>
</html>