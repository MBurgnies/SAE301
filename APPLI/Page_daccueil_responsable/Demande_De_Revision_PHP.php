<?php
session_start();

// Vérifier si l'utilisateur s'est connecté
if (!isset($_SESSION['idUtilisateur']) || $_SESSION['role'] != 'Responsable Pedagogique') {
    header('Location: ../Page_de_connexion/Page_De_Connexion.php');
    exit();
}

$host = "localhost";
$dbname = "sae301";
$user = "plichon";
$passwordbd = "zsZ72ANM";


// Récupérer l'ID du justificatif concerné via un champ caché et vérifier s'il est soumis
if (isset($_POST['revision']) && isset($_POST['justificatifID_form'])) {

    // récupérer l'ID du justificatif et son commentaire
    $justificatifID = filter_input(INPUT_POST, 'justificatifID_form', FILTER_VALIDATE_INT);
    $commentaireResponsable = trim($_POST['commentaireModif']);

    // Si l'ID n'est pas valide, on arrête
    if ($justificatifID === false || $justificatifID <= 0) {
        header('Location: Page_Accueil_Responsable.php');
        exit();
    }

    // vérifier que le commentaire est bien rempli (verification côté serveur)
    if (empty($commentaireResponsable)) {
        // Rediriger vers la page de demande avec une erreur
        header('Location: Page_Demande_Revision.php?id=' . $justificatifID);
        exit();
    }


    try {
        $conn1 = new PDO("pgsql:host=$host;dbname=$dbname", $user, $passwordbd);
        $conn1->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // La requête UPDATE pour le Justificatif
        $sql = "UPDATE Justificatif
                SET statut = 'demande de révision',          
                    commentairerespon = :commentaire,
                    motifrespon = 'Le responsable pédagogique vous demande de refaire un justificatif'
                WHERE idjustificatif = :id";

        $requete = $conn1->prepare($sql);
        $requete->bindParam(':commentaire', $commentaireResponsable, PDO::PARAM_STR);
        $requete->bindParam(':id', $justificatifID, PDO::PARAM_INT);
        $requete->execute();

        // La requête UPDATE pour l'Absence (pour que l'étudiant la voie)
        $sql2 = "UPDATE Absence
                SET statut = 'demande de révision'
                WHERE idjustificatif = :id";

        $requete2 = $conn1->prepare($sql2);
        $requete2->bindParam(':id', $justificatifID, PDO::PARAM_INT);
        $requete2->execute();

        // Rediriger vers la page d'accueil du responsable
        header('Location: Page_Accueil_Responsable.php?traitement=revision');
        exit();

    } catch(PDOException $e) {
        // En cas d'erreur, rediriger vers la page de demande
        header('Location: Page_Demande_Revision.php?id=' . $justificatifID);
        exit();
    }

} else {
    // Si quelqu'un accède à ce script directement sans soumettre le formulaire
    header('Location: Page_Accueil_Responsable.php');
    exit();
}
?>