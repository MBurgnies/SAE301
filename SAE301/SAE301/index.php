<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Responsable Pédagogique</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="login.css">
</head>
<body class="login-body">
<div class="login-container">
    <header class="login-header">
        <div class="logo-area">
            <div class="logo-text">
                <span>Université</span>
                <span>Polytechnique</span>
                <span>Hauts-de-France</span>
            </div>
            <div class="logo-right">
                <span>ESPACE</span>
                <span>NUMÉRIQUE DE</span>
                <span>TRAVAIL</span>
            </div>
        </div>
        <div class="login-yellow-line"></div>
    </header>

    <form class="login-form">
        <div class="form-group">
            <label for="username">Nom d'utilisateur</label>
            <input type="text" id="username" name="username" placeholder="Entrez votre nom...">
        </div>
        <div class="form-group">
            <label for="password">Mot de passe</label>
            <input type="password" id="password" name="password" placeholder="Entrez votre mot de passe...">
        </div>
        <a href="dashboard.php" class="submit-btn">Envoyer</a>
    </form>
</div>
</body>
</html>