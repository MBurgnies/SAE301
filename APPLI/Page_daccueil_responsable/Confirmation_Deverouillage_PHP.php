<?php
session_start();

// vérifier si l'utilisateur s'est connecté
if (!isset($_SESSION['idUtilisateur']) || $_SESSION['role'] != 'Responsable Pedagogique') {
    header('Location: ../Page_de_connexion/Page_De_Connexion.php');
    exit();
}

$host = "localhost";
$dbname = "sae301";
$user = "plichon";
$passwordbd = "zsZ72ANM";

// vérifier si le formulaire de confirmation a été soumis
if (isset($_POST['confirm-deverrouiller']) && isset($_POST['justificatifID'])) {

    // récupérer et Valider l'ID
    $justificatifID = filter_input(INPUT_POST, 'justificatifID', FILTER_VALIDATE_INT);

    // Si l'ID n'est pas valide, on redirige
    if ($justificatifID === false || $justificatifID <= 0) {
        header('Location: Page_Accueil_Responsable.php');
        exit();
    }

    try {
        $conn1 = new PDO("pgsql:host=$host;dbname=$dbname", $user, $passwordbd);
        $conn1->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


        $sql2 = "UPDATE Absence
                SET statut = 'non justifie'
                WHERE idjustificatif = :id";

        $requete2 = $conn1->prepare($sql2);
        $requete2->bindParam(':id', $justificatifID, PDO::PARAM_INT);
        $requete2->execute();


        $sql = "UPDATE Justificatif
                SET statut = 'plus valable', commentairerespon = 'Le responsable pédagogique est revenus sur ce justificatif, afin de justifier les absences qui étaient concernées veuillez créer un nouveau justificatif', motifrespon = null
                WHERE idjustificatif = :id";

        $requete = $conn1->prepare($sql);
        $requete->bindParam(':id', $justificatifID, PDO::PARAM_INT);
        $requete->execute();


        // Rediriger vers la page d'accueil du responsable
        header('Location: Page_Accueil_Responsable.php');
        exit();

    } catch(PDOException $e) {
        header('Location: Page_Accueil_Responsable.php');
        exit();
    }

} else {
    header('Location: Page_Accueil_Responsable.php');
    exit();
}
?>