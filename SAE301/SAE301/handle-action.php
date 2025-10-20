<?php
session_start();

$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
$justif_id = isset($_REQUEST['id']) ? (int)$_REQUEST['id'] : null;
$redirect_url = 'dashboard.php';

if (!$justif_id) {
    header('Location: ' . $redirect_url);
    exit;
}

switch ($action) {
    case 'reject':
        $motif = isset($_POST['motif']) ? $_POST['motif'] : '';
        $comment = isset($_POST['comment']) ? trim($_POST['comment']) : '';

        if (empty($motif)) {
            $_SESSION['error_message'] = "Veuillez sélectionner un motif.";
            $redirect_url = 'refuse-justificatif.php?id=' . $justif_id;
        } else {
            $justif_to_move = null;
            $justif_index = -1;
            foreach ($_SESSION['justifications_attente'] as $index => $justif) {
                if ($justif['id'] === $justif_id) {
                    $justif_to_move = $justif;
                    $justif_index = $index;
                    break;
                }
            }

            if ($justif_to_move) {
                array_splice($_SESSION['justifications_attente'], $justif_index, 1);
                $justif_to_move['statut'] = 'Refuser';
                $justif_to_move['motif'] = $motif;
                $justif_to_move['commentaire_decision'] = !empty($comment) ? $comment : 'Aucun commentaire de refus.';
                $_SESSION['justifications_historique'][] = $justif_to_move;
                $_SESSION['message'] = "Le justificatif a été refusé pour le motif : " . htmlspecialchars($motif);
            }
        }
        $redirect_url = 'dashboard.php';
        break;

    case 'accept':
        $comment = isset($_POST['comment']) ? trim($_POST['comment']) : '';

        $justif_to_move = null;
        $justif_index = -1;
        foreach ($_SESSION['justifications_attente'] as $index => $justif) {
            if ($justif['id'] === $justif_id) {
                $justif_to_move = $justif;
                $justif_index = $index;
                break;
            }
        }

        if ($justif_to_move) {
            array_splice($_SESSION['justifications_attente'], $justif_index, 1);
            $justif_to_move['statut'] = 'Accepter';
            $justif_to_move['motif'] = 'Accepté';
            $justif_to_move['commentaire_decision'] = !empty($comment) ? $comment : 'Aucun commentaire d\'acceptation.';
            $_SESSION['justifications_historique'][] = $justif_to_move;
            $_SESSION['message'] = "Le justificatif a bien été accepté.";
        } else {
            $_SESSION['message'] = "Erreur : justificatif non trouvé dans la liste d'attente.";
        }
        $redirect_url = 'dashboard.php';
        break;

    case 'new_refus_motif':
        $new_motif = isset($_POST['new_motif']) ? trim($_POST['new_motif']) : '';
        if (!empty($new_motif) && !in_array($new_motif, $_SESSION['refus_motifs'])) {
            $_SESSION['refus_motifs'][] = $new_motif;
            $_SESSION['message'] = "Le nouveau motif a été ajouté.";
            $redirect_url = 'refuse-justificatif.php?id=' . $justif_id;
        } else {
            $_SESSION['error_message'] = "Erreur : le motif est vide ou existe déjà.";
            $redirect_url = 'refuse-justificatif.php?id=' . $justif_id . '&new_motif=1';
        }
        break;

    case 'unlock':
        $unlocked_justificatif = null;
        $justif_index = -1;
        foreach ($_SESSION['justifications_historique'] as $index => $justif) {
            if ($justif['id'] === $justif_id) {
                $unlocked_justificatif = $justif;
                $justif_index = $index;
                break;
            }
        }
        if ($unlocked_justificatif) {
            array_splice($_SESSION['justifications_historique'], $justif_index, 1);
            unset($unlocked_justificatif['statut']);
            unset($unlocked_justificatif['motif']);
            unset($unlocked_justificatif['commentaire_decision']);
            $_SESSION['justifications_attente'][] = $unlocked_justificatif;
            $_SESSION['message'] = "L'absence a bien été déverrouillée et a été remise en attente.";
        } else {
            $_SESSION['message'] = "Erreur : justificatif non trouvé dans l'historique.";
        }
        break;

    case 'revision':
        $_SESSION['message'] = "L'option de révision n'est pas encore implémentée.";
        break;
}

header('Location: ' . $redirect_url);
exit;