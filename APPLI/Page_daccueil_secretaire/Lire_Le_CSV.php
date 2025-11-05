<?php
session_start();


// vérifier que l'utilisateur s'est bien connecté
if (!isset($_SESSION['idUtilisateur']) || $_SESSION['role'] != 'Secretaire') {
    header('Location: ../Page_de_connexion/Page_De_Connexion.php');
    exit();
}

$host = "localhost";
$dbname = "sae301";
$user = "plichon";
$passwordbd = "zsZ72ANM";

try {
    $conn = new PDO("pgsql:host=$host;dbname=$dbname", $user, $passwordbd);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Vérifie si le fichier a été correctement envoyé
    if (isset($_FILES['csv_file']) && $_FILES['csv_file']['error'] === UPLOAD_ERR_OK) {
        $fichier = $_FILES['csv_file']['tmp_name'];

        // Ouvre le fichier
        if (($f = fopen($fichier, "r")) !== FALSE) {
            $csv_data = [];
            while (($data = fgetcsv($f, 1000, ";", "\"", '')) !== FALSE) {
                $csv_data[] = $data;
            }
            fclose($f);

            // on enlève l'entête du tableau
            $header = array_shift($csv_data);

            // initialiser les compteurs
            $totalLignesLues = count($csv_data);
            $countAjoutes = 0;
            $countDoublons = 0;
            $countDejaJustifiees = 0;
            $countEtuInexistant = 0;
            $ListeEtudiantInexistant = [];

            // on parcours le fichier
            foreach ($csv_data as $ligne) {

                if (!is_array($ligne) || count($ligne) < 24) {
                    $countIgnorees++; // On ignore la ligne
                    continue; // Passe à la ligne suivante
                }


                $nom = $ligne[0];
                $prenom = $ligne[1];
                $idIUT = $ligne[4];
                $date = trim($ligne[8]);
                $heure = trim($ligne[9]);
                $duree = trim($ligne[10]);
                $typecours = trim($ligne[11]);
                $matiere = $ligne[12];
                $justification = $ligne[17];
                $prof = trim($ligne[22]);
                $evaluation_str = trim($ligne[23]);





                // Initialiser la variable ressource
                $ressource = null;

                // Pattern expliqué :
                // \(     -> Cherche une parenthèse ouvrante littérale
                // .* -> Cherche n'importe quel caractère
                // (      -> Début du groupe à capturer (à partir de R)
                //   R[1-6] -> Cherche "R" suivi d'un chiffre de 1 à 6
                //   [^\)]* -> Cherche n'importe quel caractère qui n'est pas une parenthèse fermante
                // )      -> Fin du groupe à capturer
                // \)     -> Cherche la parenthèse fermante
                $patternR = '/\(.*(R[1-6][^\)]*)\)/';

                $patternS = '/\(.*(S[1-6][^\)]*)\)/';

                // Si on a trouvé, $matches[1] contient notre capture (ex: 'R6.A.06')
                if (preg_match($patternR, $matiere, $text)) {
                    $ressource = $text[1];
                }
                elseif (preg_match($patternS, $matiere, $text)) {
                    $ressource = $text[1];
                }



                // transformer le $evaluation en true ou false pour l'ajouter dans la BDD
                $evaluation_bool = ($evaluation_str == "Oui");

                if ($justification === '') {$justification = null;}

                if ($prof === '') {$prof = null;}

                // récupérer l'id de l'étudiant
                $requeteIdEtu = $conn->prepare("SELECT idUtilisateur FROM Utilisateur WHERE nom = :nom AND prénom = :prenom AND identifiantiut = :idIUT");
                $requeteIdEtu->bindParam(':nom', $nom);
                $requeteIdEtu->bindParam(':prenom', $prenom);
                $requeteIdEtu->bindParam(':idIUT', $idIUT);
                $requeteIdEtu->execute();

                $idEtu = $requeteIdEtu->fetch(PDO::FETCH_COLUMN);

                // si ce n'est pas justifié et que l'étudiant est dans la base de donnée
                if ($idEtu !== false) {
                    if ($justification === "Non justifié") {
                        // requete qui permet de vérifier s'il y a un doublon en prenant date, heure, duree, idIUT
                        $requeteDoublons = $conn->prepare(
                            "SELECT * FROM absence
                               JOIN Utilisateur ON absence.idutilisateur = Utilisateur.idutilisateur
                               WHERE Utilisateur.identifiantiut = :idIUT AND date = TO_DATE(:date, 'DD/MM/YYYY') AND heure = REPLACE(:heure, 'H', ':')::time AND duree = REPLACE(:duree, 'H', ':')::time;"
                        );
                        $requeteDoublons->bindParam(':date', $date);
                        $requeteDoublons->bindParam(':heure', $heure);
                        $requeteDoublons->bindParam(':duree', $duree);
                        $requeteDoublons->bindParam(':idIUT', $idIUT);
                        $requeteDoublons->execute();

                        $double = $requeteDoublons->fetchColumn();

                        // si il n'y a pas de doublon alors on peut l'ajouter
                        if ($double == null) {
                            $requeteInsertionAbsence = $conn->prepare("INSERT INTO Absence (date, heure, duree, evaluation, matiere, prof, idutilisateur, idjustificatif, statut, ressource, typecours) VALUES(TO_DATE(:date, 'DD/MM/YYYY'),REPLACE(:heure, 'H', ':')::time,REPLACE(:duree, 'H', ':')::time, :evaluation_bool, :matiere, :prof, :idutilisateur,null, 'non justifie', :ressource, :typecours)");
                            $requeteInsertionAbsence->bindParam(':date', $date);
                            $requeteInsertionAbsence->bindParam(':heure', $heure);
                            $requeteInsertionAbsence->bindParam(':duree', $duree);
                            $requeteInsertionAbsence->bindParam(':evaluation_bool', $evaluation_bool, PDO::PARAM_BOOL);
                            $requeteInsertionAbsence->bindParam(':matiere', $matiere);
                            $requeteInsertionAbsence->bindParam(':prof', $prof);
                            $requeteInsertionAbsence->bindParam(':idutilisateur', $idEtu, PDO::PARAM_INT);
                            $requeteInsertionAbsence->bindParam(':ressource', $ressource);
                            $requeteInsertionAbsence->bindParam(':typecours', $typecours);
                            $requeteInsertionAbsence->execute();

                            $countAjoutes++;
                        } else {
                            $countDoublons++;
                        }

                        // absence déjà justifiée
                    } else {
                        $countDejaJustifiees++;
                    }
                    // étudiant non trouvé
                }else {
                    $countEtuInexistant++;
                    $nomComplet = $nom . " " . $prenom;
                    if (!in_array($nomComplet, $ListeEtudiantInexistant)) {
                        $ListeEtudiantInexistant[] = $nomComplet;
                    }
                }
            }

            $_SESSION['lignesLues'] = $totalLignesLues;
            $_SESSION['countAjoutes'] = $countAjoutes;
            $_SESSION['countDoublons'] = $countDoublons;
            $_SESSION['countDejaJustifiees'] = $countDejaJustifiees;
            $_SESSION['countEtuInexistant'] = $countEtuInexistant;
            $_SESSION['ListeEtudiantInexistant'] = $ListeEtudiantInexistant;

            // message de succès
            header('Location: ' . "Page_Accueil_Secretaire.php" . '?status=success');
            exit;

        } else {
            // Erreur lors de l'ouverture
            header('Location: ' . "Page_Accueil_Secretaire.php" . '?status=error');
            exit;
        }
    } else {
        // Erreur d'upload
        header('Location: ' . "Page_Accueil_Secretaire.php" . '?status=error');
        exit;
    }

} catch (PDOException $e) {
    // on stocke un message pour l'utilisateur
    $_SESSION['import_error_public'] = "Une erreur technique est survenue. Veuillez contacter le service compétent.";
    // On redirige comme avant
    header('Location: ' . "Page_Accueil_Secretaire.php" . '?status=error_db');
    exit;
}
?>