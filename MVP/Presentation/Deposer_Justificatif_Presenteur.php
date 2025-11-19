<?php
/*
 * Fichier Presentation
 * Gère le dépôt de justificatif par l'étudiant.
*/
session_start();

// On inclut les fichiers Modele
require_once '../Modele/ConnexionBDD.php';
require_once '../Modele/Etudiant_Modele.php';

// Fonction d'aide pour gérer l'upload d'un fichier
function gérerUploadFichier($fileKey, $uploadDir) {
    // Vérifie si le fichier existe DANS LA REQUÊTE et s'il a été uploadé sans erreur
    if (isset($_FILES[$fileKey]) && $_FILES[$fileKey]['error'] === UPLOAD_ERR_OK) {

        $fileTmpName = $_FILES[$fileKey]['tmp_name'];
        $fileName = basename($_FILES[$fileKey]['name']);
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $newFileName = uniqid('', true) . '.' . $fileExtension;
        $destination = $uploadDir . $newFileName;

        if (move_uploaded_file($fileTmpName, $destination)) {
            return $destination; // Retourne le chemin du fichier si l'upload réussit
        } else {
            // En cas d'échec du déplacement de fichier
            header('Location: ../Vue/Page_Deposer_Justificatif.php?error=upload');
            exit();
        }
    }
    // Si le fichier n'est pas présent
    // ou s'il y a une erreur autre
    return null;
}

// On vérifie si l'utilisateur est connecté
if (!isset($_SESSION['idUtilisateur'])) {
    header('Location: ../Vue/Page_De_Connexion.php');
    exit();
}

$idUtilisateurConnecte = $_SESSION['idUtilisateur'];

if (isset($_POST['justifier'])) {

    $uploadDir = '../Modele/uploads/';

    // On appelle la fonction d'aide pour le premier fichier
    $cheminFichier1PourBDD = gérerUploadFichier('fichierjustificatif1', $uploadDir);

    // On appelle la fonction d'aide pour le second fichier
    $cheminFichier2PourBDD = gérerUploadFichier('fichierjustificatif2', $uploadDir);

    $motif = $_POST['motif'];
    $datedebut = '';
    $heuredebut = '';
    $datefin = '';
    $heurefin = '';

    // On vérifie si le mode "jour entier" a été utilisé
    if (isset($_POST['dateJourEntier']) && !empty($_POST['dateJourEntier'])) {
        // Mode Jour Entier
        $datedebut = $_POST['dateJourEntier'];
        $datefin = $_POST['dateJourEntier'];
        $heuredebut = '00:00'; // Début de la journée
        $heurefin = '23:59';   // Fin de la journée
    } else {
        // Mode Intervalle
        $datedebut = $_POST['dateDebut'];
        $heuredebut = $_POST['heureDebut'];
        $datefin = $_POST['dateFin'];
        $heurefin = $_POST['heureFin'];
    }
    $commentaire = empty($_POST['commentaire']) ? null : $_POST['commentaire'];

    $conn1 = connecterBDD();

    $resultat = deposerJustificatif($conn1, $idUtilisateurConnecte, $datedebut, $heuredebut, $datefin, $heurefin, $motif, $commentaire, $cheminFichier1PourBDD, $cheminFichier2PourBDD);

    $conn1 = null;

    if ($resultat === "succes") {
        header('Location: ../Vue/Page_Deposer_Justificatif.php?succes');
        exit();
    } elseif ($resultat === "inutile") {
        header('Location: ../Vue/Page_Deposer_Justificatif.php?error=inutile');
        exit();
    } elseif ($resultat === "conflict") {
        header('Location: ../Vue/Page_Deposer_Justificatif.php?error=conflict');
        exit();
    } else { // "db_error"
        header('Location: ../Vue/Page_Deposer_Justificatif.php?error=db');
        exit();
    }
}
?>