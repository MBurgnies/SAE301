<?php

// vérifier si l'utilisateur s'est connecté
if (!isset($_SESSION['idUtilisateur'])) {
    header('Location: ../Page_de_connexion/Page_De_Connexion.php');
    exit();
}


// récupérer et valider l'ID du Justificatif
if (!isset($_GET['id']) || !filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    echo "Erreur : ID du justificatif invalide ou manquant.";
    exit();
}
$justificatifID = (int)$_GET['id'];

$host = "localhost";
$dbname = "sae301";
$user = "plichon";
$passwordbd = "zsZ72ANM";

// variable pour stocker les détails
$motifDetails = null;

try {
    $conn1 = new PDO("pgsql:host=$host;dbname=$dbname", $user, $passwordbd);
    $conn1->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // récupérer motifrespon et commentairerespon
    $sql = "SELECT
                motifrespon,
                commentairerespon
            FROM Justificatif
            WHERE idjustificatif = :id
            LIMIT 1";

    $requete = $conn1->prepare($sql);
    $requete->bindParam(':id', $justificatifID, PDO::PARAM_INT);
    $requete->execute();

    $motifDetails = $requete->fetch(PDO::FETCH_ASSOC);

} catch(PDOException $e) {
    header('Location: Page_Accueil_Etudiant.php');
    exit();
}

// vérifier si le justificatif a été trouvé
if (!$motifDetails) {
    header('Location: Page_Accueil_Etudiant.php');
    exit();
}

?>