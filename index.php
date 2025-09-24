
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - UPHF</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body class="login-body">
<div class="login-container">
    <div class="logo-area">
        <img src="MBurgnies/SAE301/Images/logo.png" alt="Université Polytechnique Hauts-de-France Logo">
        <div class="logo-text">
            <span>Université</span>
            <span>Polytechnique</span>
            <span>HAUTS-DE-FRANCE</span>
        </div>
        <div class="logo-right">
            <span>ESPACE</span>
            <span>NUMÉRIQUE DE</span>
            <span>TRAVAIL</span>
        </div>
    </div>
    <form class="login-form" action="dashboard.php" method="post">
        <div class="form-group">
            <label for="username">Nom d'utilisateur</label>
            <input type="text" id="username" name="username" placeholder="Entrez votre nom..." required>
        </div>
        <div class="form-group">
            <label for="password">Mot de passe</label>
            <input type="password" id="password" name="password" placeholder="Entrez votre mot de passe..." required>
        </div>
        <button type="submit" class="submit-btn">Envoyer</button>
    </form>
</div>
</body>
</html>