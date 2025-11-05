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

$ressource_selectionnee = isset($_GET['ressource']) ? $_GET['ressource'] : '';

$lesRattrapages = [];
$nbrRattrapages = 0;

if (!empty($ressource_selectionnee)) {
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
                WHERE Absence.statut = 'accepté' AND Absence.evaluation IS TRUE";

        if ($ressource_selectionnee != 'TOUT') {
            $sql .= " AND Absence.ressource = :ressource";
        }

        $sql .= " ORDER BY date";

        $requeteRattrapages = $conn1->prepare($sql);

        if ($ressource_selectionnee != 'TOUT') {
            $requeteRattrapages->bindParam(':ressource', $ressource_selectionnee);
        }

        $requeteRattrapages->execute();

        $lesRattrapages = $requeteRattrapages->fetchAll(PDO::FETCH_ASSOC);
        $nbrRattrapages = count($lesRattrapages);

    } catch(PDOException $e) {
        $lesRattrapages = [];
        $nbrRattrapages = 0;
        $errorMessage = "Erreur de connexion à la base de données. Impossible de charger les données.";
    }
}

?>