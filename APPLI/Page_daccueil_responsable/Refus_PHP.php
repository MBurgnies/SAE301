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

// vérifier si le formulaire de refus a été soumis
if (isset($_POST['refuser']) && isset($_POST['justificatifID'])) {

    // récupérer l'ID du justificatif
    $justificatifID = filter_input(INPUT_POST, 'justificatifID', FILTER_VALIDATE_INT);

    // récupérer les deux options de motif
    $motifSelect = trim($_POST['motifRefus']);
    $motifPerso = trim($_POST['motifPerso']);

    $commentaireRefus = trim($_POST['commentaireRefus']);

    // Si l'ID n'est pas valide, on arrête
    if ($justificatifID === false || $justificatifID <= 0) {
        header('Location: Page_Accueil_Responsable.php');
        exit();
    }

    // permet de savoir quel champ a été choisi
    $motifSelectRempli = !empty($motifSelect);
    $motifPersoRempli = !empty($motifPerso);

    // On vérifie quand même qu'il y a exactement un champ de choisi (pas 0 et pas 2)
    if (($motifSelectRempli + $motifPersoRempli) !== 1) {
        // rediriger vers la page de refus avec un message d'erreur
        header('Location: Page_De_Refus.php?id=' . $justificatifID . '&error=xor');
        exit();
    }

    // On prend le motif qui a été rempli
    $motifFinal = $motifSelectRempli ? $motifSelect : $motifPerso;

    $commentaireFinal = !empty($commentaireRefus) ? $commentaireRefus : null;


    try {
        $conn1 = new PDO("pgsql:host=$host;dbname=$dbname", $user, $passwordbd);
        $conn1->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // passer le justificatif en refusé
        $sql = "UPDATE Justificatif
                SET statut = 'refusé',          
                    commentairerespon = :commentaire,
                    motifrespon = :motif
                WHERE idjustificatif = :id";

        $requete = $conn1->prepare($sql);
        $requete->bindParam(':commentaire', $commentaireFinal, $commentaireFinal === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
        $requete->bindParam(':motif', $motifFinal, PDO::PARAM_STR);
        $requete->bindParam(':id', $justificatifID, PDO::PARAM_INT);
        $requete->execute();

        // passer les absences en refusées
        $sql2 = "UPDATE Absence
                SET statut = 'refusé'
                WHERE idjustificatif = :id";
        $requete2 = $conn1->prepare($sql2);
        $requete2->bindParam(':id', $justificatifID, PDO::PARAM_INT);
        $requete2->execute();

        header('Location: Page_Accueil_Responsable.php?traitement=refuse');
        exit();

    } catch(PDOException $e) {
        // En cas d'erreur
        header('Location: Page_Accueil_Responsable.php');
        exit();
    }

} else {
    // Si quelqu'un accède à ce script directement
    header('Location: Page_Accueil_Responsable.php');
    exit();
}
?>