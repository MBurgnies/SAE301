<?php
session_start();

// On vérifie si l'utilisateur est connecté
if (!isset($_SESSION['idUtilisateur'])) {
    header('Location: ../Page_de_connexion/Page_De_Connexion.php');
    exit();
}


// On stocke son ID pour l'insertion BDD
$idUtilisateurConnecte = $_SESSION['idUtilisateur'];


$host = "localhost";
$dbname = "sae301";
$user = "plichon";
$passwordbd = "zsZ72ANM";


if (isset($_POST['justifier'])) {

    try {
        $datedebut = $_POST['dateDebut'];
        $heuredebut = $_POST['heureDebut'];
        $datefin = $_POST['dateFin'];
        $heurefin = $_POST['heureFin'];
        $motif = $_POST['motif'];
        $commentaire = empty($_POST['commentaire']) ? null : $_POST['commentaire'];

        $conn1 = new PDO("pgsql:host=$host;dbname=$dbname", $user, $passwordbd);
        $conn1->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


        // Gestion de l'uploads du fichier du justificatif
        $cheminFichierPourBDD = null;

        if (isset($_FILES['fichierjustificatif']) && $_FILES['fichierjustificatif']['error'] === UPLOAD_ERR_OK) {

            $uploadDir = 'uploads/';
            $fileTmpName = $_FILES['fichierjustificatif']['tmp_name'];
            $fileName = basename($_FILES['fichierjustificatif']['name']);
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            $newFileName = uniqid('', true) . '.' . $fileExtension;
            $destination = $uploadDir . $newFileName;

            if (move_uploaded_file($fileTmpName, $destination)) {
                $cheminFichierPourBDD = $destination;
            } else {
                // Erreur donc on redirige avec un message
                header('Location: Page_Deposer_Justificatif.php?error=uploads');
                exit();
            }
        }
        // fin de la gestion de l'upload


        // créer les timestamps de début et de fin pour le justificatif
        $timestampDebutJustificatif = $datedebut . ' ' . $heuredebut;
        $timestampFinJustificatif = $datefin . ' ' . $heurefin;


        // verifier qu'il n'y a pas d'autres justificatifs en attente ou validé ou refusé pour pouvoir le déposer
        $requeteVerifierdoublons = $conn1->prepare("SELECT * FROM Absence WHERE idutilisateur = :idutilisateur
                                                        AND (date + heure + duree) <= TO_TIMESTAMP(:timestampfin, 'YYYY-MM-DD HH24:MI')
                                                        AND (date + heure) >= TO_TIMESTAMP(:timestampdebut, 'YYYY-MM-DD HH24:MI')
                                                        AND statut != 'Plus valable' AND statut != 'plus valable' 
                                                        AND statut != 'non justifie'
                                                        AND statut != 'demande de révision' ");
        $requeteVerifierdoublons->bindParam(':idutilisateur', $idUtilisateurConnecte );
        $requeteVerifierdoublons->bindParam(':idutilisateur', $idUtilisateurConnecte );
        $requeteVerifierdoublons->bindParam(':timestampdebut', $timestampDebutJustificatif);
        $requeteVerifierdoublons->bindParam(':timestampfin', $timestampFinJustificatif);
        $requeteVerifierdoublons->execute();

        $DejaUnJustificatif = (int)$requeteVerifierdoublons->fetchColumn();

        // s'il n'y a pas déjà un justificatif en cours
        if ($DejaUnJustificatif === 0) {


            // vérifier si le justificatif qui va être déposé concerne au moins une absence sinon on ne l'ajoute pas
            $requeteVerifierUtilite = $conn1->prepare("SELECT * FROM Absence WHERE idutilisateur = :idutilisateur
                                                        AND (date + heure + duree) <= TO_TIMESTAMP(:timestampfin, 'YYYY-MM-DD HH24:MI')
                                                        AND (date + heure) >= TO_TIMESTAMP(:timestampdebut, 'YYYY-MM-DD HH24:MI')");
            $requeteVerifierUtilite->bindParam(':idutilisateur', $idUtilisateurConnecte );
            $requeteVerifierUtilite->bindParam(':timestampdebut', $timestampDebutJustificatif);
            $requeteVerifierUtilite->bindParam(':timestampfin', $timestampFinJustificatif);
            $requeteVerifierUtilite->execute();

            $nbabsence = $requeteVerifierUtilite->rowCount();

            // si le justificatif concerne au moins une absence
            if ($nbabsence !== 0) {

                // ajouter le justificatif dans la table
                $requete = $conn1->prepare("INSERT INTO justificatif 
                    (datedebut, datefin, heuredebut, heurefin, commentaireeleve, commentairerespon, statut, motifeleve, motifrespon, fichier) 
                VALUES 
                    ( :datedebut, :datefin, :heuredebut, :heurefin, :commentaire, null, 'en attente', :motif, null, :cheminfichier)");

                $requete->bindParam(':datedebut', $datedebut);
                $requete->bindParam(':datefin', $datefin);
                $requete->bindParam(':heuredebut', $heuredebut);
                $requete->bindParam(':heurefin', $heurefin);
                $requete->bindParam(':commentaire', $commentaire);
                $requete->bindParam(':motif', $motif);
                $requete->bindParam(':cheminfichier', $cheminFichierPourBDD);
                $requete->execute();

                // récupérer l'identifiant du justificatif
                $justificatifID = $conn1->lastInsertId();


                // mettre l'identifiant du justificatif à toutes les absences concernées et les passer en attente à condition qu'ils ne soient pas refusé ou accepté
                $requeteAbsencesLiees = $conn1->prepare("UPDATE Absence SET idjustificatif = :justificatifID, statut = 'en attente' WHERE idutilisateur = :idutilisateur
                                                        AND (date + heure + duree) <= TO_TIMESTAMP(:timestampfin, 'YYYY-MM-DD HH24:MI')
                                                        AND (date + heure) >= TO_TIMESTAMP(:timestampdebut, 'YYYY-MM-DD HH24:MI')
                                                        AND statut != 'refusé' AND statut != 'accepté'");

                $requeteAbsencesLiees->bindParam(':justificatifID', $justificatifID);
                $requeteAbsencesLiees->bindParam(':idutilisateur', $idUtilisateurConnecte );
                $requeteAbsencesLiees->bindParam(':timestampdebut', $timestampDebutJustificatif);
                $requeteAbsencesLiees->bindParam(':timestampfin', $timestampFinJustificatif);
                $requeteAbsencesLiees->execute();




                // supprimer l'ancien justificatif qui était 'non valable' ou 'en révision' s'il y en avait un

                // récupérer tous les id de justificatifs actuellement utilisés dans la table Absence
                $stmtUsedIds = $conn1->query("SELECT DISTINCT idjustificatif FROM Absence WHERE idjustificatif IS NOT NULL");
                $usedJustificatifIds = $stmtUsedIds->fetchAll(PDO::FETCH_COLUMN);


                // sélectionner les id des justificatifs qui sont plus valable
                $stmtNonValableIds = $conn1->prepare("SELECT idjustificatif FROM Justificatif WHERE LOWER(statut) = 'plus valable' OR LOWER(statut) = 'demande de révision'");
                $stmtNonValableIds->execute();
                $nonValableJustificatifIds = $stmtNonValableIds->fetchAll(PDO::FETCH_COLUMN);

                // trouver les id 'Plus valable' qui ne sont pas dans la liste des id utilisés
                $idsToDelete = array_diff($nonValableJustificatifIds, $usedJustificatifIds);



                // Si on a trouvé des IDs à supprimer, on les supprime
                if (!empty($idsToDelete)) {
                    // Créer les placeholders (?, ?, ?) pour la clause IN
                    $placeholders = implode(',', array_fill(0, count($idsToDelete), '?'));


                    // On récupère d'abord les chemins des fichiers avant de supprimer les lignes en BDD
                    $recupererFichier = $conn1->prepare("SELECT fichier FROM Justificatif WHERE idjustificatif IN ($placeholders)");
                    $recupererFichier->execute(array_values($idsToDelete));
                    $fichiersASupprimer = $recupererFichier->fetchAll(PDO::FETCH_COLUMN);

                    // On parcourt la liste des fichiers et on les supprime
                    foreach ($fichiersASupprimer as $fichier) {
                        // On vérifie que le chemin n'est pas vide et que le fichier existe
                        if (!empty($fichier) && file_exists($fichier)) {
                            @unlink($fichier); // Supprime le fichier (le @ évite une erreur si la suppression échoue)
                        }
                    }



                    $stmtDelete = $conn1->prepare("DELETE FROM Justificatif WHERE idjustificatif IN ($placeholders)");

                    // Exécuter la suppression avec la liste des IDs
                    $stmtDelete->execute(array_values($idsToDelete));
                }
                // fin nettoyage de la bdd


            } else{
                header('Location: Page_Deposer_Justificatif.php?error=inutile');
                exit();
            }
        }
        else{
            header('Location: Page_Deposer_Justificatif.php?error=conflict');
            exit();

        }

        // Si tout s'est bien passé, on redirige vers l'accueil
        header('Location: Page_Deposer_Justificatif.php?succes');
        exit();


    } catch(PDOException $e) {
        header('Location: Page_Deposer_Justificatif.php');
        exit();
    }

}
?>