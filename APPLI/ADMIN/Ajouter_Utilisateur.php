<?php
session_start();

// vérifier si l'utilisateur s'est connecté
if (!isset($_SESSION['idUtilisateur'])) {
    header('Location: ../Page_de_connexion/Page_De_Connexion.php');
    exit();
}

$host = "localhost";
$dbname = "sae301";
$user = "plichon";
$passwordbd = "zsZ72ANM";

try {
    if (isset($_POST['CréerUtilisateur'])) {
        $role = $_POST['role'];
        $UserName = $_POST['UserName'];
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $mdp = $_POST['mdp'];
        $idIUT = $_POST['idIUT'];
        $groupe = $_POST["groupe"];
        $mail = $_POST['mail'];

        if ($idIUT === "") {$idIUT = null;}
        if ($groupe === "") {$groupe = null;}

        $hash = password_hash($mdp, PASSWORD_DEFAULT);

        $conn1 = new PDO("pgsql:host=$host;dbname=$dbname", $user, $passwordbd);
        $conn1->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $requete = $conn1->prepare( "INSERT INTO Utilisateur (nomUtilisateur, motDePasse, nom, prénom, role, identifiantiut, groupe, email) VALUES (:UserName, :mdp, :nom, :prenom, :role, :idIUT, :groupe, :mail)");
        $requete->bindParam(':UserName', $UserName);
        $requete->bindParam(':mdp', $hash);
        $requete->bindParam(':nom', $nom);
        $requete->bindParam(':prenom', $prenom);
        $requete->bindParam(':role', $role);
        $requete->bindParam(':idIUT', $idIUT);
        $requete->bindParam(':groupe', $groupe);
        $requete->bindParam(':mail', $mail);


        $requete->execute();

        header('Location: ADMIN.php?success=true');
        exit();
    }

} catch(PDOException $e) {
        header('Location: ADMIN.php');
        exit();
}
$conn1 = null;

?>
