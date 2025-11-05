<?php
session_start();

$host = "localhost";
$dbname = "sae301";
$user = "plichon";
$passwordbd = "zsZ72ANM";


if (isset($_POST['identifiants'])) {

    try {
        $username = $_POST['UserName'];
        $password = $_POST['mdp'];

        $conn1 = new PDO("pgsql:host=$host;dbname=$dbname", $user, $passwordbd);
        $conn1->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // on récupère le mot de passe
        $requete = $conn1->prepare( "SELECT motdepasse FROM Utilisateur WHERE nomutilisateur = :nom");
        $requete->bindParam(':nom', $username);
        $requete->execute();
        $hash = $requete->fetchColumn();

        // on récupère le rôle
        $requeteRole = $conn1->prepare( "SELECT role FROM Utilisateur WHERE nomutilisateur = :nom");
        $requeteRole->bindParam(':nom', $username);
        $requeteRole->execute();

        // on récupère l'ID de l'utilisateur
        $requeteId = $conn1->prepare( "SELECT idutilisateur FROM Utilisateur WHERE nomutilisateur = :nom");
        $requeteId->bindParam(':nom', $username);
        $requeteId->execute();
        $idUtilisateur = $requeteId->fetchColumn();


        // On vérifie le mot de passe
        if ($hash && password_verify($password, $hash)) {

            // On stocke l'ID dans la session pour le réutiliser sur les autres pages
            $_SESSION['idUtilisateur'] = $idUtilisateur;

            $role = $requeteRole->fetchColumn();

            $_SESSION['role'] = $role;

            // pour savoir sur quelle page rediriger l'utilisateur en fonction de son rôle
            switch ($role) {
                case "Etudiant":
                    header('Location: ../Page_daccueil_etudiante/Page_Accueil_Etudiant.php');
                    break;

                case "Professeur":
                    header('Location: ../Page_daccueil_prof/Page_Accueil_Professeur.php');
                    break;

                case "ADMIN":
                    header('Location: ../ADMIN/ADMIN.php');
                    break;

                case "Responsable Pedagogique":
                    header('Location: ../Page_daccueil_responsable/Page_Accueil_Responsable.php');
                    break;

                case "Secretaire":
                    header('Location: ../Page_daccueil_secretaire/Page_Accueil_Secretaire.php');
                    break;

            }
            exit();

        } else {
            // Si le mot de passe ou le nom sont faux
            $_SESSION['login_error'] = "Nom d'utilisateur ou mot de passe incorrect.";
            header('Location: Page_De_Connexion.php');
            exit();
        }

    } catch(PDOException $e) {
        $_SESSION['login_error'] = "Erreur de connexion. Veuillez réessayer plus tard.";
        $_SESSION['login_error'] = $e->getMessage();
    }
    $conn1 = null;
}
?>