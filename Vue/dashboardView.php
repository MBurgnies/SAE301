<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de bord</title>
    <link rel="stylesheet" href="style.css">
    <script src="script.js"></script>
</head>
<body>
<div class="header">
    <h2>Bonjour <?= htmlspecialchars($username) ?> </h2>
    <a href="logout.php">Déconnexion</a>
</div>

<h3>Absences</h3>
<table border="1">
    <tr><th>Date</th><th>Évaluation</th><th>Status</th><th>Action</th></tr>
    <?php foreach ($absences as $a): ?>
        <tr>
            <td><?= $a["date"] ?></td>
            <td><?= $a["eval"] ?></td>
            <td><?= $a["status"] ?></td>
            <td>
                <form method="POST">
                    <input type="hidden" name="absenceId" value="<?= $a["id"] ?>">
                    <button name="justify">Justifier</button>
                    <button name="unlock">Déverrouiller</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
</body>
</html>
