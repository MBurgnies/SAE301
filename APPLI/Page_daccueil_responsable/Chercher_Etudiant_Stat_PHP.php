<?php

if (!isset($_SESSION['idUtilisateur']) || $_SESSION['role'] != 'Responsable Pedagogique') {
    header('Location: ../Page_de_connexion/Page_De_Connexion.php');
    exit();
}

$host = "localhost";
$dbname = "sae301";
$user = "plichon";
$passwordbd = "zsZ72ANM";

$lesEtudiants = [];
$errorMessage = '';

try {
    $conn = new PDO("pgsql:host=$host;dbname=$dbname", $user, $passwordbd);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Requête pour récupérer tous les étudiants
    $sql = "SELECT idUtilisateur, nom, prénom, groupe 
            FROM Utilisateur 
            WHERE role = 'Etudiant' 
            ORDER BY nom, prénom";

    $requete = $conn->prepare($sql);
    $requete->execute();

    $lesEtudiants = $requete->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $errorMessage = "Erreur de connexion à la base de données : ";
}

?>