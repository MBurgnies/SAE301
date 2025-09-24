<?php
// Simulation de données statiques avec plus de détails.
$justifications_attente = [
        ['date' => '06/05/2025', 'etudiant' => 'Enzo LeGrand', 'nom' => 'LeGrand', 'prenom' => 'Enzo', 'email' => 'enzo.legrand@uphf.fr', 'id' => 1],
        ['date' => '06/05/2025', 'etudiant' => 'Arthus Baillon', 'nom' => 'Baillon', 'prenom' => 'Arthus', 'email' => 'arthus.baillon@uphf.fr', 'id' => 2],
        ['date' => '06/05/2025', 'etudiant' => 'Léon Marchand', 'nom' => 'Marchand', 'prenom' => 'Léon', 'email' => 'leon.marchand@uphf.fr', 'id' => 3],
        ['date' => '06/05/2025', 'etudiant' => 'Zinedine Zidane', 'nom' => 'Zidane', 'prenom' => 'Zinedine', 'email' => 'zinedine.zidane@uphf.fr', 'id' => 4],
        ['date' => '06/05/2025', 'etudiant' => 'Enzo LeGrand', 'nom' => 'LeGrand', 'prenom' => 'Enzo', 'email' => 'enzo.legrand@uphf.fr', 'id' => 1],
        ['date' => '06/05/2025', 'etudiant' => 'Arthus Baillon', 'nom' => 'Baillon', 'prenom' => 'Arthus', 'email' => 'arthus.baillon@uphf.fr', 'id' => 2],
        ['date' => '06/05/2025', 'etudiant' => 'Léon Marchand', 'nom' => 'Marchand', 'prenom' => 'Léon', 'email' => 'leon.marchand@uphf.fr', 'id' => 3],
        ['date' => '06/05/2025', 'etudiant' => 'Zinedine Zidane', 'nom' => 'Zidane', 'prenom' => 'Zinedine', 'email' => 'zinedine.zidane@uphf.fr', 'id' => 4]
];

$justifications_historique = [
        ['date' => '06/05/2025', 'etudiant' => 'Enzo LeGrand', 'statut' => 'Refuser', 'nom' => 'LeGrand', 'prenom' => 'Enzo', 'email' => 'enzo.legrand@uphf.fr', 'id' => 5],
        ['date' => '06/05/2025', 'etudiant' => 'Arthus Baillon', 'statut' => 'Accepter', 'nom' => 'Baillon', 'prenom' => 'Arthus', 'email' => 'arthus.baillon@uphf.fr', 'id' => 6],
        ['date' => '06/05/2025', 'etudiant' => 'Léon Marchand', 'statut' => 'Refuser', 'nom' => 'Marchand', 'prenom' => 'Léon', 'email' => 'leon.marchand@uphf.fr', 'id' => 7],
        ['date' => '06/05/2025', 'etudiant' => 'Zinedine Zidane', 'statut' => 'Accepter', 'nom' => 'Zidane', 'prenom' => 'Zinedine', 'email' => 'zinedine.zidane@uphf.fr', 'id' => 8]
];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord - Responsable Pédagogique</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<header class="main-header">
    <div class="logo-container">
        <img src="images/logo.png" alt="Université Polytechnique Hauts-de-France Logo">
        <div class="logo-text-block">
            <span>Université</span>
            <span>Polytechnique</span>
            <span>HAUTS-DE-FRANCE</span>
        </div>
        <div class="logo-right-block">
            <span>ESPACE</span>
            <span>NUMÉRIQUE DE</span>
            <span>TRAVAIL</span>
        </div>
    </div>
    <nav class="top-nav">
        <a href="statistiques.php" class="nav-btn">Statistique</a>
        <a href="index.php" class="nav-btn">Déconnexion</a>
    </nav>
    <div class="yellow-line"></div>
</header>

<main class="dashboard-main">
    <h1>Tableau De Bord</h1>
    <div class="table-grid">
        <section class="table-section">
            <h2>Justificatifs en attente</h2>
            <div class="table-wrapper">
                <table>
                    <thead>
                    <tr>
                        <th>Date</th>
                        <th>Étudiant</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody id="justificatifs-attente-body">
                    <?php foreach ($justifications_attente as $justif) : ?>
                        <tr
                                data-date="<?php echo htmlspecialchars($justif['date']); ?>"
                                data-etudiant="<?php echo htmlspecialchars($justif['etudiant']); ?>"
                                data-nom="<?php echo htmlspecialchars($justif['nom']); ?>"
                                data-prenom="<?php echo htmlspecialchars($justif['prenom']); ?>"
                                data-email="<?php echo htmlspecialchars($justif['email']); ?>">
                            <td><?php echo htmlspecialchars($justif['date']); ?></td>
                            <td><?php echo htmlspecialchars($justif['etudiant']); ?></td>
                            <td><button class="consult-btn attente">Consulter</button></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>
        <section class="table-section">
            <h2>Historique</h2>
            <div class="table-wrapper">
                <table>
                    <thead>
                    <tr>
                        <th>Date</th>
                        <th>Étudiant</th>
                        <th>Statut</th>
                        <th></th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody id="justificatifs-historique-body">
                    <?php foreach ($justifications_historique as $justif) : ?>
                        <tr
                                data-date="<?php echo htmlspecialchars($justif['date']); ?>"
                                data-etudiant="<?php echo htmlspecialchars($justif['etudiant']); ?>"
                                data-statut="<?php echo htmlspecialchars($justif['statut']); ?>"
                                data-nom="<?php echo htmlspecialchars($justif['nom']); ?>"
                                data-prenom="<?php echo htmlspecialchars($justif['prenom']); ?>"
                                data-email="<?php echo htmlspecialchars($justif['email']); ?>">
                            <td><?php echo htmlspecialchars($justif['date']); ?></td>
                            <td><?php echo htmlspecialchars($justif['etudiant']); ?></td>
                            <td><?php echo htmlspecialchars($justif['statut']); ?></td>
                            <td><button class="consult-btn historique">Consulter</button></td>
                            <td><button class="unlock-btn">Déverrouiller</button></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</main>

<div id="unlockModal" class="modal">
    <div class="modal-content small-modal">
        <span class="close-btn">&times;</span>
        <p>Êtes-vous sûr de vouloir déverrouiller l'absence, celle-ci sera de nouveau dans la liste des absences à justifier de l'étudiant.</p>
        <button class="confirm-btn unlock-confirm-btn">Confirmer déverrouillage</button>
    </div>
</div>

<div id="viewModalAttente" class="modal">
    <div class="modal-content attente-view-modal">
        <span class="close-btn">&times;</span>
        <div class="modal-body-content">
            <div class="left-section">
                <p>Absent du : <span id="absenceDateAttente"></span> à 10h</p>
                <p>Au : <span id="absenceDateEndAttente"></span> à 15h</p>
                <p>Motif : <span id="motifAttente"></span></p>
                <div class="comment-section">
                    <p>Commentaire :</p>
                    <textarea readonly id="commentaireAttente"></textarea>
                </div>
            </div>
            <div class="right-section">
                <p class="modal-title">Justificatif :</p>
                <button class="justificatif-btn" id="downloadJustificatif">Télécharger le justificatif</button>
                <button class="justificatif-btn" id="previewJustificatif">Voir un aperçu</button>
                <p>Nom : <span id="etudiantNomAttente"></span></p>
                <p>Prénom : <span id="etudiantPrenomAttente"></span></p>
                <p>Adresse mail : <span id="etudiantEmailAttente"></span></p>

                <div id="previewContainerAttente">
                    <iframe id="justificatifPreviewAttente" class="preview-iframe" frameborder="0"></iframe>
                </div>
            </div>
        </div>
        <div class="modal-actions">
            <button class="action-btn" id="requestRevisionBtn">Demander une révision</button>
            <button class="action-btn" id="rejectBtn">Refuser</button>
            <button class="action-btn" id="acceptBtn">Valider</button>
        </div>
    </div>
</div>

<div id="viewModalHistorique" class="modal">
    <div class="modal-content attente-view-modal">
        <span class="close-btn">&times;</span>
        <div class="modal-body-content">
            <div class="left-section">
                <p>Absent du : <span id="absenceDateHistorique"></span> à 10h</p>
                <p>Au : <span id="absenceDateEndHistorique"></span> à 15h</p>
                <p>Motif : <span id="motifHistorique"></span></p>
                <div class="comment-section">
                    <p>Commentaire :</p>
                    <textarea readonly id="commentaireHistorique"></textarea>
                </div>
            </div>
            <div class="right-section">
                <p class="modal-title">Justificatif :</p>
                <button class="justificatif-btn" id="downloadJustificatifHistorique">Télécharger le justificatif</button>
                <button class="justificatif-btn" id="previewJustificatifHistorique">Voir un aperçu</button>
                <p>Nom : <span id="etudiantNomHistorique"></span></p>
                <p>Prénom : <span id="etudiantPrenomHistorique"></span></p>
                <p>Adresse mail : <span id="etudiantEmailHistorique"></span></p>
                <p>Finalité : <span id="finaliteHistorique"></span></p>

                <div id="previewContainerHistorique">
                    <iframe id="justificatifPreviewHistorique" class="preview-iframe" frameborder="0"></iframe>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="rejectReasonModal" class="modal action-modal">
    <div class="modal-content">
        <span class="close-btn">&times;</span>
        <p>Pour refuser le justificatif vous devez saisir un motif.</p>
        <div class="reason-form">
            <div class="form-group">
                <label for="rejectMotive">Motif</label>
                <select id="rejectMotive">
                    <option value="">Choisissez le motif</option>
                    <option value="Justificatif non valide">Justificatif non valide</option>
                    <option value="Motif non recevable">Motif non recevable</option>
                    <option value="Justificatif en retard">Justificatif en retard</option>
                    <option value="Incomplet">Incomplet</option>
                </select>
                <span id="rejectError" class="error-message"></span>
            </div>
            <div class="form-group">
                <label for="rejectComment">Commentaire :</label>
                <textarea id="rejectComment" placeholder="Écrivez un commentaire... (facultatif)"></textarea>
            </div>
            <div class="action-modal-btns">
                <button class="confirm-btn reject-confirm-btn">Valider</button>
                <button class="retour-btn">Retour</button>
            </div>
        </div>
    </div>
</div>

<div id="acceptReasonModal" class="modal action-modal">
    <div class="modal-content">
        <span class="close-btn">&times;</span>
        <p>Pour accepter le justificatif vous devez saisir un motif.</p>
        <div class="reason-form">
            <div class="form-group">
                <label for="acceptMotive">Motif</label>
                <select id="acceptMotive">
                    <option value="">Choisissez le motif</option>
                    <option value="Justificatif médical">Justificatif médical</option>
                    <option value="Raison familiale">Raison familiale</option>
                    <option value="Convocation officielle">Convocation officielle</option>
                    <option value="Autre raison valable">Autre raison valable</option>
                </select>
                <span id="acceptError" class="error-message"></span>
            </div>
            <div class="form-group">
                <label for="acceptComment">Commentaire :</label>
                <textarea id="acceptComment" placeholder="Écrivez un commentaire... (facultatif)"></textarea>
            </div>
            <div class="action-modal-btns">
                <button class="confirm-btn accept-confirm-btn">Valider</button>
                <button class="retour-btn">Retour</button>
            </div>
        </div>
    </div>
</div>

<div id="revisionModal" class="modal action-modal">
    <div class="modal-content">
        <span class="close-btn">&times;</span>
        <p>Pour demander une révision du justificatif vous devez saisir un commentaire.</p>
        <div class="reason-form">
            <div class="form-group">
                <label for="revisionComment">Commentaire :</label>
                <textarea id="revisionComment" placeholder="Indiquer ce que l'étudiant doit modifier/ajouter..."></textarea>
            </div>
            <div class="action-modal-btns">
                <button class="confirm-btn">Valider</button>
                <button class="retour-btn">Retour</button>
            </div>
        </div>
    </div>
</div>

<script src="script.js"></script>
</body>
</html>