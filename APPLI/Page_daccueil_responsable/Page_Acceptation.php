<?php
session_start();

// vérifier si l'utilisateur s'est connecté
if (!isset($_SESSION['idUtilisateur']) || $_SESSION['role'] != 'Responsable Pedagogique') {
    header('Location: ../Page_de_connexion/Page_De_Connexion.php');
    exit();
}

// récupérer l'ID depuis l'URL pour le champ caché
if (!isset($_GET['id']) || !filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    header('Location: Page_Accueil_Responsable.php');
    exit();
}
$justificatifID_from_url = (int)$_GET['id'];

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Accepter Justificatif</title>
    <link rel="stylesheet" href="Style_Page_Acceptation.css">
</head>
<body>

<div class="header-main">
    <div class="logo-section">
        <img src="logo_uphf.png" alt="Logo Université Polytechnique">
    </div>
    <div class="header-right">
        <p>ESPACE NUMÉRIQUE DE TRAVAIL</p>
        <a href="index.html" class="bouton-statistique">Statistique</a>
        <a href="../Page_de_connexion/Page_De_Connexion.php" class="bouton-deconnexion">Déconnexion</a>
    </div>
</div>



<div class="container">

    <div class="bouton-retour-wrapper">
        <a href="Page_Consultation_Justificatif_En_Attente.php?id=<?php echo $justificatifID_from_url; ?>" class="action-button">Retour</a>
    </div>

    <div id="validation-comment" class="validation-comment">

        <div class="validation-comment-content">

            <div class="validation-comment-body">
                <p class="validation-instruction">
                    Vous êtes sur le point de valider ce justificatif. Vous pouvez ajouter un commentaire (facultatif).
                </p>

                <h3 class="title-validation">Commentaire :</h3>

                <form method="POST" action="Acceptation_PHP.php" id="validerJustificatif">

                    <input type="hidden" name="justificatifID" value="<?php echo $justificatifID_from_url; ?>">

                    <textarea id="commentaireValider" name="commentaireValider" class="revision-textarea"
                              placeholder="Écrivez quelque chose... (Facultatif)"></textarea>

                    <div class="validation-buttons-wrapper">
                        <button type="submit" id="valider" class="action-button" name="valider">Valider</button>
                    </div>
                </form>

            </div>

        </div>
    </div>
</div>

<footer class="main-footer"></footer>

</body>
</html>