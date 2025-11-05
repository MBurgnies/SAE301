<?php

// Vérifier si l'utilisateur s'est connecté
if (!isset($_SESSION['idUtilisateur']) || $_SESSION['role'] != 'Responsable Pedagogique') {
    header('Location: ../Page_de_connexion/Page_De_Connexion.php');
    exit();
}

// récupérer et Valider l'ID du justificatif
if (!isset($_GET['id']) || !filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    header('Location: Page_Accueil_Responsable.php');
    exit();
}
$justificatifID = (int)$_GET['id'];

$host = "localhost";
$dbname = "sae301";
$user = "plichon";
$passwordbd = "zsZ72ANM";


try {
    $conn1 = new PDO("pgsql:host=$host;dbname=$dbname", $user, $passwordbd);
    $conn1->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // récupérer tous les détails du justificatif
    $sql = "SELECT
                Justificatif.idjustificatif,
                TO_CHAR(Justificatif.datedebut, 'DD/MM/YYYY') as datedebut_f,
                TO_CHAR(Justificatif.heuredebut, 'HH24:MI') as heuredebut_f,
                TO_CHAR(Justificatif.datefin, 'DD/MM/YYYY') as datefin_f,
                TO_CHAR(Justificatif.heurefin, 'HH24:MI') as heurefin_f,
                Justificatif.motifeleve,
                Justificatif.commentaireeleve,
                Justificatif.fichier,
                Utilisateur.email,
                Utilisateur.nom,
                Utilisateur.prénom
            FROM Justificatif
            JOIN Absence ON Absence.idjustificatif = Justificatif.idjustificatif
            JOIN Utilisateur ON Utilisateur.idutilisateur = Absence.idutilisateur
            WHERE Justificatif.idjustificatif = :id
            LIMIT 1";

    $requete = $conn1->prepare($sql);
    $requete->bindParam(':id', $justificatifID, PDO::PARAM_INT);
    $requete->execute();

    $justificatifDetails = $requete->fetch(PDO::FETCH_ASSOC);

} catch(PDOException $e) {
    header('Location: Page_Accueil_Responsable.php');
    exit();
}
?>