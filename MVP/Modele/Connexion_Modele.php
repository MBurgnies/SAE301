<?php


/**
 * Fonction qui cherche un utilisateur dans la base de données par son nom.
 * Elle REÇOIT la connexion en paramètre.
 */
function trouverUtilisateurParNom($conn1, $username) {
    try {
        // on récupère le mot de passe
        $requete = $conn1->prepare( "SELECT motdepasse FROM Utilisateur WHERE nomutilisateur = :nom");
        $requete->bindParam(':nom', $username);
        $requete->execute();
        $hash = $requete->fetchColumn();

        // on récupère le rôle
        $requeteRole = $conn1->prepare( "SELECT role FROM Utilisateur WHERE nomutilisateur = :nom");
        $requeteRole->bindParam(':nom', $username);
        $requeteRole->execute();
        $role = $requeteRole->fetchColumn();

        // on récupère l'ID de l'utilisateur
        $requeteId = $conn1->prepare( "SELECT idutilisateur FROM Utilisateur WHERE nomutilisateur = :nom");
        $requeteId->bindParam(':nom', $username);
        $requeteId->execute();
        $idUtilisateur = $requeteId->fetchColumn();

        // tentatives échouées
        $reqTentatives = $conn1->prepare("SELECT tentatives_echouees FROM Utilisateur WHERE nomutilisateur = :nom");
        $reqTentatives->bindParam(':nom', $username);
        $reqTentatives->execute();
        $tentatives = $reqTentatives->fetchColumn();

        // date fin blocage
        $reqBlocage = $conn1->prepare("SELECT date_fin_blocage FROM Utilisateur WHERE nomutilisateur = :nom");
        $reqBlocage->bindParam(':nom', $username);
        $reqBlocage->execute();
        $blocage = $reqBlocage->fetchColumn();

        return [
            'hash' => $hash,
            'role' => $role,
            'idUtilisateur' => $idUtilisateur,
            'tentatives' => $tentatives,
            'blocage' => $blocage
        ];

    } catch(PDOException $e) {
        return false;
    }
}
?>