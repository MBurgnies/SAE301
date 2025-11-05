<?php
// vérifier si l'utilisateur s'est connecté
if (!isset($_SESSION['idUtilisateur']) || $_SESSION['role'] != 'Responsable Pedagogique') {
    header('Location: ../Page_de_connexion/Page_De_Connexion.php');
    exit();
}

// récupérer et valider l'ID du Justificatif
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
                j.idjustificatif,
                TO_CHAR(j.datedebut, 'DD/MM/YYYY') as datedebut_f,
                TO_CHAR(j.heuredebut, 'HH24:MI') as heuredebut_f,
                TO_CHAR(j.datefin, 'DD/MM/YYYY') as datefin_f,
                TO_CHAR(j.heurefin, 'HH24:MI') as heurefin_f,
                j.motifeleve,
                j.commentaireeleve,
                j.fichier,
                u.email,
                j.statut,
                j.motifrespon,
                j.commentairerespon,
                u.nom,
                u.prénom
            FROM Justificatif j
            JOIN Absence a ON a.idjustificatif = j.idjustificatif
            JOIN Utilisateur u ON u.idutilisateur = a.idutilisateur
            WHERE j.idjustificatif = :id
            GROUP BY j.idjustificatif, u.nom, u.prénom, u.email
            LIMIT 1";

    $requete = $conn1->prepare($sql);
    $requete->bindParam(':id', $justificatifID, PDO::PARAM_INT);
    $requete->execute();

    $justificatifDetailsHisto = $requete->fetch(PDO::FETCH_ASSOC);

} catch(PDOException $e) {
    header('Location: Page_Accueil_Responsable.php');
    exit();
}
// fonction qui permet d'attribuer un css au texte (ex : rouge si refusé)
function getStatusClass($statut) {
    switch (strtolower($statut)) {
        case 'non justifie':
            return 'status-nojustified';
        case 'accepté':
            return 'status-accepted';
        case 'refusé':
            return 'status-refused';
        case 'en attente':
        default:
            return 'status-pending';
    }
}