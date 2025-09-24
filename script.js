document.addEventListener('DOMContentLoaded', () => {
    const unlockModal = document.getElementById('unlockModal');
    const viewModalAttente = document.getElementById('viewModalAttente');
    const viewModalHistorique = document.getElementById('viewModalHistorique');
    const rejectReasonModal = document.getElementById('rejectReasonModal');
    const acceptReasonModal = document.getElementById('acceptReasonModal');
    const revisionModal = document.getElementById('revisionModal');

    const attenteTableBody = document.querySelector('#justificatifs-attente-body');
    const historiqueTableBody = document.querySelector('#justificatifs-historique-body');

    const rejectMotiveSelect = document.getElementById('rejectMotive');
    const acceptMotiveSelect = document.getElementById('acceptMotive');
    const rejectError = document.getElementById('rejectError');
    const acceptError = document.getElementById('acceptError');

    let currentRow = null;

    function closeAllModals() {
        document.querySelectorAll('.modal').forEach(modal => {
            modal.style.display = 'none';
        });
        if (rejectError) rejectError.textContent = '';
        if (acceptError) acceptError.textContent = '';
        if (rejectMotiveSelect) rejectMotiveSelect.value = '';
        if (acceptMotiveSelect) acceptMotiveSelect.value = '';
    }

    function handleJustificatifAction(action, motif) {
        if (!currentRow) return;

        const date = currentRow.dataset.date;
        const etudiant = currentRow.dataset.etudiant;

        const mockData = {
            cours: 'mathématique',
            motif: 'Pas de transport',
            commentaire: `Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.`
        };

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
                alert('La demande de révision a bien été envoyée.');
                closeAllModals();
                return;
            default:
                return;
        }

        const newRow = document.createElement('tr');
        newRow.setAttribute('data-date', date);
        newRow.setAttribute('data-etudiant', etudiant);
        newRow.setAttribute('data-statut', statut);
        newRow.innerHTML = `
            <td>${date}</td>
            <td>${etudiant}</td>
            <td>${statut}</td>
            <td><button class="consult-btn historique">Consulter</button></td>
            <td><button class="unlock-btn">Déverrouiller</button></td>
        `;
        historiqueTableBody.appendChild(newRow);

        currentRow.remove();

        alert(message);
        closeAllModals();
        currentRow = null;
    }

    document.body.addEventListener('click', (e) => {
        if (e.target.classList.contains('consult-btn')) {
            closeAllModals();
            currentRow = e.target.closest('tr');
            const tableType = e.target.classList.contains('attente') ? 'attente' : 'historique';

            const mockData = {
                cours: 'mathématique',
                motif: 'Pas de transport',
                commentaire: `Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.`
            };

            if (tableType === 'attente') {
                document.getElementById('absenceDateAttente').textContent = currentRow.dataset.date;
                document.getElementById('coursConcerneAttente').textContent = mockData.cours;
                document.getElementById('motifAttente').textContent = mockData.motif;
                document.getElementById('commentaireAttente').textContent = mockData.commentaire;
                viewModalAttente.style.display = 'flex';
            } else {
                document.getElementById('absenceDateHistorique').textContent = currentRow.dataset.date;
                document.getElementById('coursConcerneHistorique').textContent = mockData.cours;
                document.getElementById('motifHistorique').textContent = mockData.motif;
                document.getElementById('commentaireHistorique').textContent = mockData.commentaire;
                document.getElementById('finaliteHistorique').textContent = currentRow.dataset.statut;
                viewModalHistorique.style.display = 'flex';
            }
        }
    });

    document.body.addEventListener('click', (e) => {
        if (e.target.id === 'rejectBtn') {
            closeAllModals();
            rejectReasonModal.style.display = 'flex';
        } else if (e.target.id === 'acceptBtn') {
            closeAllModals();
            acceptReasonModal.style.display = 'flex';
        } else if (e.target.id === 'requestRevisionBtn') {
            closeAllModals();
            revisionModal.style.display = 'flex';
        } else if (e.target.classList.contains('unlock-btn')) {
            closeAllModals();
            unlockModal.style.display = 'flex';
        }
    });

    if (document.querySelector('.unlock-confirm-btn')) {
        document.querySelector('.unlock-confirm-btn').addEventListener('click', () => {
            alert("L'absence a bien été déverrouillée.");
            closeAllModals();
        });
    }

    if (document.querySelector('.accept-confirm-btn')) {
        document.querySelector('.accept-confirm-btn').addEventListener('click', () => {
            if (acceptMotiveSelect.value === "") {
                acceptError.textContent = "Vous n'avez sélectionné aucun motif.";
            } else {
                acceptError.textContent = "";
                handleJustificatifAction('accept', acceptMotiveSelect.value);
            }
        });
    }

    if (document.querySelector('.reject-confirm-btn')) {
        document.querySelector('.reject-confirm-btn').addEventListener('click', () => {
            if (rejectMotiveSelect.value === "") {
                rejectError.textContent = "Vous n'avez sélectionné aucun motif.";
            } else {
                rejectError.textContent = "";
                handleJustificatifAction('reject', rejectMotiveSelect.value);
            }
        });
    }

    document.querySelectorAll('.close-btn, .retour-btn').forEach(btn => {
        btn.addEventListener('click', closeAllModals);
    });

    window.addEventListener('click', (event) => {
        if (event.target.classList.contains('modal')) {
            closeAllModals();
        }
    });
});