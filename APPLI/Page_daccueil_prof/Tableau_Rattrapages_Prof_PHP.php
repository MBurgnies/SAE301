<?php

// vérifier si l'utilisateur s'est connecté
if (!isset($_SESSION['idUtilisateur'])) {
    header('Location: ../Page_de_connexion/Page_De_Connexion.php');
    exit();
}

$host = "localhost";
$dbname = "sae301";
$user = "plichon";
$passwordbd = "zsZ72ANM";

$idProf = $_SESSION['idUtilisateur'];


try {
    $conn1 = new PDO("pgsql:host=$host;dbname=$dbname", $user, $passwordbd);
    $conn1->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT TO_CHAR(Absence.date, 'DD/MM/YYYY') as date,
                   TO_CHAR(Absence.heure, 'HH24:MI') as heure,
                   TO_CHAR(Absence.duree, 'HH24:MI') as duree, 
                   Absence.matiere, Utilisateur.nom, Utilisateur.prénom,
                   Utilisateur.groupe, Utilisateur.email
    FROM Absence
    JOIN Utilisateur ON Utilisateur.idUtilisateur = Absence.idUtilisateur
    WHERE LOWER(Absence.statut) = 'accepté' AND Absence.evaluation IS TRUE
    ORDER BY date";
    $requeteRattrapages = $conn1->prepare($sql);
    $requeteRattrapages->execute();

    $lesRattrapages = $requeteRattrapages->fetchAll(PDO::FETCH_ASSOC);

}catch(PDOException $e) {
    header('Location: ../Page_de_connexion/Page_De_Connexion.php');
    exit();
}