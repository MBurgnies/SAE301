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

// vérifier si le formulaire a été soumis
if (isset($_POST['valider']) && isset($_POST['justificatifID'])) {

    // récupérer et valider les données du justificatif (id et commentaire)
    $justificatifID = filter_input(INPUT_POST, 'justificatifID', FILTER_VALIDATE_INT);
    $commentaireResponsable = trim($_POST['commentaireValider']);

    // Si le commentaire est vide, on stocke NULL dans la BDD
    if (empty($commentaireResponsable)) {
        $commentaireResponsable = null;
    }

    // Si l'ID n'est pas valide, on arrête
    if ($justificatifID === false || $justificatifID <= 0) {
        // Rediriger avec une erreur (ou afficher un message)
        header('Location: Page_Accueil_Responsable.php?error=invalid_id');
        exit();
    }

    try {
        $conn1 = new PDO("pgsql:host=$host;dbname=$dbname", $user, $passwordbd);
        $conn1->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "UPDATE Justificatif
                SET statut = 'accepté',          
                    commentairerespon = :commentaire  
                WHERE idjustificatif = :id";

        $requete = $conn1->prepare($sql);
        $requete->bindParam(':commentaire', $commentaireResponsable, $commentaireResponsable === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
        $requete->bindParam(':id', $justificatifID, PDO::PARAM_INT);
        $requete->execute();

        $sql2 = "UPDATE Absence
                SET statut = 'accepté'
                WHERE idjustificatif = :id";

        $requete2 = $conn1->prepare($sql2);
        $requete2->bindParam(':id', $justificatifID, PDO::PARAM_INT);
        $requete2->execute();


        header('Location: Page_Accueil_Responsable.php?traitement=succes');
        exit();

    } catch(PDOException $e) {
        header('Location: Page_Accueil_Responsable.php');
        exit();
    }

} else {
    // Si quelqu'un accède à ce script directement sans soumettre le formulaire
    header('Location: Page_Accueil_Responsable.php');
    exit();
}
?>