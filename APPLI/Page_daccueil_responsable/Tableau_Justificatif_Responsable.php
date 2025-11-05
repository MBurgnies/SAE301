<?php

// vérifier si l'utilisateur s'est connecté
if (!isset($_SESSION['idUtilisateur']) || $_SESSION['role'] != 'Responsable Pedagogique') {
    header('Location: ../Page_de_connexion/Page_De_Connexion.php');
    exit();
}

$host = "localhost";
$dbname = "sae301";
$user = "plichon";
$passwordbd = "zsZ72ANM";

// On initialise la variable pour la page HTML
$lesjustificatifs = [];

try {
    $conn1 = new PDO("pgsql:host=$host;dbname=$dbname", $user, $passwordbd);
    $conn1->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $requeteAvoirJustificatifs = $conn1->prepare("
        SELECT
            Justificatif.idjustificatif,
            TO_CHAR(Justificatif.datedebut, 'DD/MM/YYYY') as datededebut,
            TO_CHAR(Justificatif.heuredebut, 'HH24:MI') as heuredebut,
            TO_CHAR(Justificatif.datefin, 'DD/MM/YYYY') as datedefin,
            TO_CHAR(Justificatif.heurefin, 'HH24:MI') as heurefin,
            Utilisateur.nom as nom,
            Utilisateur.prénom as prénom,
            Utilisateur.groupe as groupe
        FROM Justificatif
        JOIN Absence ON Absence.idjustificatif = Justificatif.idjustificatif
        JOIN Utilisateur ON Utilisateur.idutilisateur = Absence.idutilisateur
        WHERE Justificatif.statut = 'en attente'
        GROUP BY Justificatif.idjustificatif, Utilisateur.nom, Utilisateur.prénom, Utilisateur.groupe
        ORDER BY Justificatif.idjustificatif");

    $requeteAvoirJustificatifs->execute();
    $lesjustificatifs = $requeteAvoirJustificatifs->fetchAll(PDO::FETCH_ASSOC);


    $requeteAvoirJustificatifsHistorique = $conn1->prepare("
        SELECT
            Justificatif.idjustificatif,
            TO_CHAR(Justificatif.datedebut, 'DD/MM/YYYY') as datededebut,
            TO_CHAR(Justificatif.heuredebut, 'HH24:MI') as heuredebut,
            TO_CHAR(Justificatif.datefin, 'DD/MM/YYYY') as datedefin,
            TO_CHAR(Justificatif.heurefin, 'HH24:MI') as heurefin,
            Utilisateur.nom as nom,
            Utilisateur.prénom as prénom,
            Utilisateur.groupe as groupe
        FROM Justificatif
        JOIN Absence ON Absence.idjustificatif = Justificatif.idjustificatif
        JOIN Utilisateur ON Utilisateur.idutilisateur = Absence.idutilisateur
        WHERE Justificatif.statut = 'refusé' OR Justificatif.statut = 'accepté'
        GROUP BY Justificatif.idjustificatif, Utilisateur.nom, Utilisateur.prénom, Utilisateur.groupe 
        ORDER BY Justificatif.idjustificatif DESC");

    $requeteAvoirJustificatifsHistorique->execute();
    $lesjustificatifsHisto = $requeteAvoirJustificatifsHistorique->fetchAll(PDO::FETCH_ASSOC);




} catch(PDOException $e) {
    header('Location: ../Page_de_connexion/Page_De_Connexion.php');
    exit();
}

?>