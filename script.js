document.addEventListener('DOMContentLoaded', () => {
    // Sélecteurs pour les modales
    const unlockModal = document.getElementById('unlockModal');
    const viewModalAttente = document.getElementById('viewModalAttente');
    const viewModalHistorique = document.getElementById('viewModalHistorique');
    const rejectReasonModal = document.getElementById('rejectReasonModal');
    const acceptReasonModal = document.getElementById('acceptReasonModal');
    const revisionModal = document.getElementById('revisionModal');
    const selectEtudiantModal = document.getElementById('selectEtudiantModal'); // Nouvelle modale

    // Sélecteurs pour les corps des tableaux
    const attenteTableBody = document.querySelector('#justificatifs-attente-body');
    const historiqueTableBody = document.querySelector('#justificatifs-historique-body');

    // Nouveaux sélecteurs pour les motifs et les messages d'erreur
    const rejectMotiveSelect = document.getElementById('rejectMotive');
    const acceptMotiveSelect = document.getElementById('acceptMotive');
    const rejectError = document.getElementById('rejectError');
    const acceptError = document.getElementById('acceptError');

    // Sélecteurs pour la nouvelle fonctionnalité de stats
    const consultStatsBtn = document.getElementById('consult-stats-btn');
    const etudiantSelect = document.getElementById('etudiant-select');
    const confirmEtudiantBtn = document.getElementById('confirmEtudiantBtn');

    // Variable pour stocker la ligne en cours de consultation
    let currentRow = null;

    // Fonction pour fermer toutes les modales
    function closeAllModals() {
        document.querySelectorAll('.modal').forEach(modal => {
            modal.style.display = 'none';
        });
        // Réinitialiser les messages d'erreur et la sélection de motifs
        if (rejectError) rejectError.textContent = '';
        if (acceptError) acceptError.textContent = '';
        if (rejectMotiveSelect) rejectMotiveSelect.value = '';
        if (acceptMotiveSelect) acceptMotiveSelect.value = '';
        // Cacher l'aperçu à la fermeture
        const previewContainerAttente = document.getElementById('previewContainerAttente');
        const previewContainerHistorique = document.getElementById('previewContainerHistorique');
        if (previewContainerAttente) previewContainerAttente.style.display = 'none';
        if (previewContainerHistorique) previewContainerHistorique.style.display = 'none';
        const justificatifPreviewAttente = document.getElementById('justificatifPreviewAttente');
        const justificatifPreviewHistorique = document.getElementById('justificatifPreviewHistorique');
        if (justificatifPreviewAttente) justificatifPreviewAttente.src = '';
        if (justificatifPreviewHistorique) justificatifPreviewHistorique.src = '';
    }

    // Fonction pour afficher l'aperçu du justificatif
    function showJustificatifPreview(modalType) {
        const previewIframe = document.getElementById(`justificatifPreview${modalType}`);
        const previewContainer = document.getElementById(`previewContainer${modalType}`);

        // Simuler un fichier. En réalité, vous devriez avoir un chemin de fichier
        // ou un objet Blob/File à cet endroit.
        const fileUrl = 'https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf';

        if (previewIframe && previewContainer) {
            previewIframe.src = fileUrl;
            previewContainer.style.display = 'block';
        }
    }

    // Fonction pour gérer les actions sur les justificatifs (accepter/refuser/réviser)
    function handleJustificatifAction(action, motif) {
        if (!currentRow) return;

        const date = currentRow.dataset.date;
        const etudiant = currentRow.dataset.etudiant;
        const nom = currentRow.dataset.nom;
        const prenom = currentRow.dataset.prenom;
        const email = currentRow.dataset.email;

        let statut;
        let message;

        switch (action) {
            case 'accept':
                statut = 'Accepter';
                message = 'Le justificatif a bien été accepté.';
                break;
            case 'reject':
                statut = 'Refuser';
                message = 'Le justificatif a bien été refusé.';
                break;
            case 'revision':
                // La révision ne déplace pas le justificatif dans l'historique
                alert('La demande de révision a bien été envoyée.');
                closeAllModals();
                return;
            default:
                return;
        }

        // Créer une nouvelle ligne dans le tableau Historique
        const newRow = document.createElement('tr');
        newRow.setAttribute('data-date', date);
        newRow.setAttribute('data-etudiant', etudiant);
        newRow.setAttribute('data-statut', statut);
        newRow.setAttribute('data-nom', nom);
        newRow.setAttribute('data-prenom', prenom);
        newRow.setAttribute('data-email', email);
        newRow.innerHTML = `
            <td>${date}</td>
            <td>${etudiant}</td>
            <td>${statut}</td>
            <td><button class="consult-btn historique">Consulter</button></td>
            <td><button class="unlock-btn">Déverrouiller</button></td>
        `;
        if (historiqueTableBody) {
            historiqueTableBody.appendChild(newRow);
        }

        // Supprimer la ligne du tableau d'attente
        currentRow.remove();

        alert(message);
        closeAllModals();
        currentRow = null; // Réinitialiser la variable
    }

    // Gestion de l'ouverture des modales de consultation
    document.body.addEventListener('click', (e) => {
        if (e.target.classList.contains('consult-btn')) {
            closeAllModals();
            currentRow = e.target.closest('tr');
            const tableType = e.target.classList.contains('attente') ? 'attente' : 'historique';

            // Données statiques pour la démo
            const mockData = {
                cours: 'mathématique',
                motif: 'Pas de transport',
                commentaire: `Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.`
            };

            if (tableType === 'attente') {
                const nom = currentRow.dataset.nom;
                const prenom = currentRow.dataset.prenom;
                const email = currentRow.dataset.email;
                const date = currentRow.dataset.date;

                const absenceDateAttente = document.getElementById('absenceDateAttente');
                if (absenceDateAttente) absenceDateAttente.textContent = date;
                const absenceDateEndAttente = document.getElementById('absenceDateEndAttente');
                if (absenceDateEndAttente) absenceDateEndAttente.textContent = date;
                const motifAttente = document.getElementById('motifAttente');
                if (motifAttente) motifAttente.textContent = mockData.motif;
                const commentaireAttente = document.getElementById('commentaireAttente');
                if (commentaireAttente) commentaireAttente.textContent = mockData.commentaire;
                const etudiantNomAttente = document.getElementById('etudiantNomAttente');
                if (etudiantNomAttente) etudiantNomAttente.textContent = nom;
                const etudiantPrenomAttente = document.getElementById('etudiantPrenomAttente');
                if (etudiantPrenomAttente) etudiantPrenomAttente.textContent = prenom;
                const etudiantEmailAttente = document.getElementById('etudiantEmailAttente');
                if (etudiantEmailAttente) etudiantEmailAttente.textContent = email;

                if (viewModalAttente) viewModalAttente.style.display = 'flex';
            } else {
                const nom = currentRow.dataset.nom;
                const prenom = currentRow.dataset.prenom;
                const email = currentRow.dataset.email;
                const date = currentRow.dataset.date;
                const finalite = currentRow.dataset.statut;

                const absenceDateHistorique = document.getElementById('absenceDateHistorique');
                if (absenceDateHistorique) absenceDateHistorique.textContent = date;
                const absenceDateEndHistorique = document.getElementById('absenceDateEndHistorique');
                if (absenceDateEndHistorique) absenceDateEndHistorique.textContent = date;
                const motifHistorique = document.getElementById('motifHistorique');
                if (motifHistorique) motifHistorique.textContent = mockData.motif;
                const commentaireHistorique = document.getElementById('commentaireHistorique');
                if (commentaireHistorique) commentaireHistorique.textContent = mockData.commentaire;
                const etudiantNomHistorique = document.getElementById('etudiantNomHistorique');
                if (etudiantNomHistorique) etudiantNomHistorique.textContent = nom;
                const etudiantPrenomHistorique = document.getElementById('etudiantPrenomHistorique');
                if (etudiantPrenomHistorique) etudiantPrenomHistorique.textContent = prenom;
                const etudiantEmailHistorique = document.getElementById('etudiantEmailHistorique');
                if (etudiantEmailHistorique) etudiantEmailHistorique.textContent = email;
                const finaliteHistorique = document.getElementById('finaliteHistorique');
                if (finaliteHistorique) finaliteHistorique.textContent = finalite;

                if (viewModalHistorique) viewModalHistorique.style.display = 'flex';
            }
        }
    });

    // Gestion des clics sur les boutons des modales
    document.body.addEventListener('click', (e) => {
        if (e.target.id === 'rejectBtn') {
            closeAllModals();
            if (rejectReasonModal) rejectReasonModal.style.display = 'flex';
        } else if (e.target.id === 'acceptBtn') {
            closeAllModals();
            if (acceptReasonModal) acceptReasonModal.style.display = 'flex';
        } else if (e.target.id === 'requestRevisionBtn') {
            closeAllModals();
            if (revisionModal) revisionModal.style.display = 'flex';
        } else if (e.target.classList.contains('unlock-btn')) {
            currentRow = e.target.closest('tr');
            closeAllModals();
            if (unlockModal) unlockModal.style.display = 'flex';
        } else if (e.target.id === 'previewJustificatif') {
            showJustificatifPreview('Attente');
        } else if (e.target.id === 'previewJustificatifHistorique') {
            showJustificatifPreview('Historique');
        }
    });

    // Événement pour le bouton de confirmation de déverrouillage
    if (document.querySelector('.unlock-confirm-btn')) {
        document.querySelector('.unlock-confirm-btn').addEventListener('click', () => {
            if (currentRow) {
                // Récupérer les données de la ligne à déverrouiller
                const date = currentRow.dataset.date;
                const etudiant = currentRow.dataset.etudiant;
                const nom = currentRow.dataset.nom;
                const prenom = currentRow.dataset.prenom;
                const email = currentRow.dataset.email;

                // Créer une nouvelle ligne pour le tableau "en attente"
                const newRow = document.createElement('tr');
                newRow.setAttribute('data-date', date);
                newRow.setAttribute('data-etudiant', etudiant);
                newRow.setAttribute('data-nom', nom);
                newRow.setAttribute('data-prenom', prenom);
                newRow.setAttribute('data-email', email);
                newRow.innerHTML = `
                    <td>${date}</td>
                    <td>${etudiant}</td>
                    <td><button class="consult-btn attente">Consulter</button></td>
                `;

                // Ajouter la nouvelle ligne au tableau d'attente
                if (attenteTableBody) attenteTableBody.appendChild(newRow);

                // Supprimer la ligne de l'historique
                currentRow.remove();

                alert("L'absence a bien été déverrouillée et a été remise en attente.");
                closeAllModals();
                currentRow = null;
            }
        });
    }

    // Événements pour les boutons de confirmation d'acceptation et de refus
    if (document.querySelector('.accept-confirm-btn')) {
        document.querySelector('.accept-confirm-btn').addEventListener('click', () => {
            if (acceptMotiveSelect && acceptMotiveSelect.value === "") {
                if (acceptError) acceptError.textContent = "Vous n'avez sélectionné aucun motif.";
            } else {
                if (acceptError) acceptError.textContent = "";
                handleJustificatifAction('accept', acceptMotiveSelect.value);
            }
        });
    }

    if (document.querySelector('.reject-confirm-btn')) {
        document.querySelector('.reject-confirm-btn').addEventListener('click', () => {
            if (rejectMotiveSelect && rejectMotiveSelect.value === "") {
                if (rejectError) rejectError.textContent = "Vous n'avez sélectionné aucun motif.";
            } else {
                if (rejectError) rejectError.textContent = "";
                handleJustificatifAction('reject', rejectMotiveSelect.value);
            }
        });
    }

    // Événements de fermeture des modales
    document.querySelectorAll('.close-btn, .retour-btn').forEach(btn => {
        btn.addEventListener('click', closeAllModals);
    });

    window.addEventListener('click', (event) => {
        if (event.target.classList.contains('modal')) {
            closeAllModals();
        }
    });

    // Événement pour ouvrir la modale de sélection d'étudiant
    if (consultStatsBtn) {
        consultStatsBtn.addEventListener('click', () => {
            closeAllModals();
            if (selectEtudiantModal) selectEtudiantModal.style.display = 'flex';
        });
    }

    // Événement pour confirmer la sélection d'étudiant et rediriger
    if (confirmEtudiantBtn) {
        confirmEtudiantBtn.addEventListener('click', () => {
            const selectedEtudiantId = etudiantSelect.value;
            if (selectedEtudiantId) {
                // Redirection vers la nouvelle page de statistiques
                window.location.href = `stats_etudiant.php?id=${selectedEtudiantId}`;
            } else {
                alert('Veuillez sélectionner un étudiant.');
            }
        });
    }
});