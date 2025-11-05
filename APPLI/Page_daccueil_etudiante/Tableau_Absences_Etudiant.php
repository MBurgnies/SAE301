<?php

// On vérifie si l'utilisateur est connecté
if (!isset($_SESSION['idUtilisateur'])) {
    // S'il n'est pas connecté, on le renvoie
    header('Location: ../Page_de_connexion/Page_De_Connexion.php');
    exit();
}

$host = "localhost";
$dbname = "sae301";
$user = "plichon";
$passwordbd = "zsZ72ANM";


// On récupère l'ID de l'étudiant depuis la session.
$idEtudiantConnecte = isset($_SESSION['idUtilisateur']) ? $_SESSION['idUtilisateur'] : null;

$resultatsdujour = [];
$resultatsJustificatifs = [];

// On regarde si une date est sélectionnée
$isDateView = (isset($_GET['selected_date']) && !empty($_GET['selected_date']));


// S'il y a une date, on l'utilise, sinon on ne met rien
$dateSelectionnee = $isDateView ? $_GET['selected_date'] : '';


$conn = new PDO("pgsql:host=$host;dbname=$dbname", $user, $passwordbd);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


try {
    // si l'ID existe
    if ($idEtudiantConnecte) {

        // absences
        $sql = "SELECT 
                    TO_CHAR(absence.date, 'DD/MM/YYYY') as date, 
                    TO_CHAR(absence.heure, 'HH24:MI') as heure_formatee, 
                    TO_CHAR(absence.duree, 'HH24:MI') as duree_formatee, 
                    absence.matiere, 
                    absence.prof, 
                    absence.statut, 
                    absence.evaluation 
                 FROM absence
                 WHERE absence.idutilisateur = :idUtilisateur";

        if ($isDateView) {
            $sql .= " AND absence.date = :dateSelectionnee ORDER BY absence.heure";
            $orderBy = "";
        } else {
            $orderBy = "ORDER BY absence.date DESC, absence.heure";
        }
        $sql .= " " . $orderBy;

        $requete_jour = $conn->prepare($sql);
        $requete_jour->bindParam(':idUtilisateur', $idEtudiantConnecte, PDO::PARAM_INT);

        if ($isDateView) {
            $requete_jour->bindParam(':dateSelectionnee', $dateSelectionnee);
        }
        $requete_jour->execute();
        $resultatsdujour = $requete_jour->fetchAll(PDO::FETCH_ASSOC);


        // justificatifs en attentes
        $sql_justif = "SELECT
                            Justificatif.idjustificatif,
                            TO_CHAR(Justificatif.datedebut, 'DD/MM/YYYY') as datededebut,
                            TO_CHAR(Justificatif.heuredebut, 'HH24:MI') as heuredebut,
                            TO_CHAR(Justificatif.datefin, 'DD/MM/YYYY') as datedefin,
                            TO_CHAR(Justificatif.heurefin, 'HH24:MI') as heurefin,
                            Justificatif.statut
                        FROM Justificatif
                        JOIN Absence ON Absence.idjustificatif = Justificatif.idjustificatif
                        JOIN Utilisateur ON Utilisateur.idutilisateur = Absence.idutilisateur
                        WHERE Utilisateur.idutilisateur = :idutilisateur
                        GROUP BY Justificatif.idjustificatif
                        ORDER BY Justificatif.idjustificatif DESC";

        $requete_justif = $conn->prepare($sql_justif);
        $requete_justif->bindParam(':idutilisateur', $idEtudiantConnecte, PDO::PARAM_INT);
        $requete_justif->execute();

        $resultatsJustificatifs = $requete_justif->fetchAll(PDO::FETCH_ASSOC);

    }


} catch(PDOException $e) {
    header('Location: ../Page_de_connexion/Page_De_Connexion.php');
    $resultatsdujour = [];
    $resultatsJustificatifs = [];
}
$conn = null;

// fonction qui permet de convertir le statut en classe CSS
function getStatusClass($statut) {
    switch (strtolower($statut)) {
        case 'non justifie':
            return 'status-nojustified';
        case 'accepte':
        case 'accepté':
            return 'status-accepted';
        case 'refuse':
        case 'refusé':
        case 'plus valable':
            return 'status-refused';
        case 'demande de révision':
            return 'status-revision';
        default:
            return 'status-pending';
    }
}
?>