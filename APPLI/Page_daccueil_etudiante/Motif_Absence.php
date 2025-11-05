<?php
session_start();


// vérifier si l'utilisateur s'est connecté
if (!isset($_SESSION['idUtilisateur'])) {
    header('Location: ../Page_de_connexion/Page_De_Connexion.php');
    exit();
}


require 'Motif_Absence_PHP.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Motif Justificatif</title>
    <link rel="stylesheet" href="Style_Motif_Absence.css">
</head>
<body>

<div class="header-main">
    <div class="logo-section">
        <img src="logo_uphf.png" alt="Logo Université Polytechnique">
    </div>
    <div class="header-right">
        <p>ESPACE NUMÉRIQUE DE TRAVAIL</p>
        <a href="Politique_Absence.html" class="bouton">Politique d'absence</a>
        <a href="../Page_de_connexion/Page_De_Connexion.php" class="bouton">Déconnexion</a>
    </div>
</div>


<div class="container">

    <div class="bouton-retour-wrapper">
        <a href="Page_Accueil_Etudiant.php" class="action-button">Retour</a>
    </div>
    <div class="content">
        <div class="header">
            <h3>Motif de l'absence</h3>
        </div>
        <div class="content-body">
            <div class="form-section">
                <div class="form-group">
                    <label>Motif du Resp. Pédagogique</label>
                    <p class="content-value">
                        <?php
                        // On affiche le motif OU le message par défaut si c'est null
                        echo htmlspecialchars($motifDetails['motifrespon'] ?? 'Le responsable n\'a pas donné de motif');
                        ?>
                    </p>
                </div>
                <div class="form-group">
                    <label>Commentaire du Resp. Pédagogique :</label>
                    <div class="comment-box">
                        <p>
                            <?php
                            // nl2br pour respecter les sauts de ligne
                            echo nl2br(htmlspecialchars($motifDetails['commentairerespon'] ?? 'Le responsable n\'a pas donné de commentaire'));
                            ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<footer class="main-footer"></footer>

</body>
</html>