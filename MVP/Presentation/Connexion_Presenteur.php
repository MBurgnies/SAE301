<?php


session_start();

require_once '../Modele/ConnexionBDD.php';
require_once '../Modele/Connexion_Modele.php';

// On vérifie si le formulaire a été soumis
if (isset($_POST['identifiants'])) {

    try {
        $username = $_POST['UserName'];
        $password = $_POST['mdp'];

        $conn = connecterBDD();

        // demander les informations au Modele
        $utilisateur = trouverUtilisateurParNom($conn, $username);

        // vérifier si le compte est bloqué
        if ($utilisateur && $utilisateur['blocage'] && strtotime($utilisateur['blocage']) > time()) {

            // calcul du temps restant en secondes
            $temps = strtotime($utilisateur['blocage']) - time();

            // conversion minutes
            $minutes = floor($temps / 60);

            $_SESSION['login_error'] = "Compte bloqué. Temps restant : {$minutes} min.";
            header('Location: ../Vue/Page_De_Connexion.php');
            exit();
        }

        // On vérifie le mot de passe
        if ($utilisateur && $utilisateur['hash'] && password_verify($password, $utilisateur['hash'])) {

            // reset des tentatives et du blocage
            $reqReset = $conn->prepare("UPDATE Utilisateur SET tentatives_echouees = 0, date_fin_blocage = NULL WHERE idutilisateur = :id");
            $reqReset->bindParam(':id', $utilisateur['idUtilisateur']);
            $reqReset->execute();

            // On stocke l'ID dans la session
            $_SESSION['idUtilisateur'] = $utilisateur['idUtilisateur'];

            // On récupère le rôle
            $role = $utilisateur['role']; // Utilise le rôle récupéré par la fonction
            $_SESSION['role'] = $role;

            // pour savoir sur quelle page rediriger l'utilisateur
            switch ($role) {
                case "Etudiant":
                    header('Location: ../Vue/Page_Accueil_Etudiant.php');
                    break;
                case "Professeur":
                    header('Location: ../Vue/Page_Accueil_Professeur.php');
                    break;
                case "ADMIN":
                    header('Location: ../Vue/ADMIN.php');
                    break;
                case "Responsable Pedagogique":
                    header('Location: ../Vue/Page_Accueil_Responsable.php');
                    break;
                case "Secretaire":
                    header('Location: ../Vue/Page_Accueil_Secretaire.php');
                    break;
            }
            exit();

        } else {
            // gestions des tentatives échouées
            if ($utilisateur) {

                $nouvellesTentatives = $utilisateur['tentatives'] + 1;

                // Si on atteint 5 échecs, on bloque 15 minutes
                if ($nouvellesTentatives >= 5) {

                    $finBlocage = date('Y-m-d H:i:s', time() + (15 * 60));
                    $reqBlocage = $conn->prepare("
                        UPDATE Utilisateur 
                        SET tentatives_echouees = :t, date_fin_blocage = :d 
                        WHERE idutilisateur = :id
                    ");
                    $reqBlocage->bindParam(':t', $nouvellesTentatives);
                    $reqBlocage->bindParam(':d', $finBlocage);
                    $reqBlocage->bindParam(':id', $utilisateur['idUtilisateur']);
                    $reqBlocage->execute();

                    $_SESSION['login_error'] = "Trop de tentatives. Compte bloqué 15 minutes.";

                } else {

                    // Sinon, on incrémente juste
                    $reqUpdate = $conn->prepare("
                        UPDATE Utilisateur 
                        SET tentatives_echouees = :t 
                        WHERE idutilisateur = :id
                    ");
                    $reqUpdate->bindParam(':t', $nouvellesTentatives);
                    $reqUpdate->bindParam(':id', $utilisateur['idUtilisateur']);
                    $reqUpdate->execute();

                    $_SESSION['login_error'] = "Nom d'utilisateur ou mot de passe incorrect.";
                }

            } else {
                // utilisateur non trouvé
                $_SESSION['login_error'] = "Nom d'utilisateur ou mot de passe incorrect.";
            }

            header('Location: ../Vue/Page_De_Connexion.php');
            exit();
        }

    } catch(Exception $e) {
        $_SESSION['login_error'] = "Erreur de connexion. Veuillez réessayer plus tard.";
        header('Location: ../Vue/Page_De_Connexion.php');
        exit();
    }
}
?>