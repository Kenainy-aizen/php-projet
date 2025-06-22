
document.addEventListener('DOMContentLoaded', function () {
    const modalConfirmation = document.getElementById('modalConfirmation');
    const overlay = document.getElementById('overlay');
    const btnConfirmer = document.getElementById('btnConfirmer');
    const btnAnnuler = document.getElementById('btnAnnuler');
    let idToDelete = null; // Variable pour stocker l'ID à supprimer

    // Ouvrir la boîte modale lors du clic sur un bouton de suppression
    document.querySelectorAll('.btnSupprimer').forEach(btn => {
        btn.addEventListener('click', function () {
            idToDelete = this.getAttribute('data-id'); // Récupérer l'ID de l'élément à supprimer
            modalConfirmation.style.display = 'block';
            overlay.style.display = 'block';
        });
    });

    // Confirmer la suppression
    btnConfirmer.addEventListener('click', function () {
        if (idToDelete) {
            // Envoyer une requête AJAX pour supprimer l'élément
            fetch(`index.php?entity=medicament&action=delete&id=${idToDelete}`, {
                method: 'GET',
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Suppression réussie.');
                    window.location.reload(); // Recharger la page pour mettre à jour la liste
                } else {
                    alert('Erreur lors de la suppression.');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
            }); 

            // Fermer la boîte modale
            modalConfirmation.style.display = 'none';
            overlay.style.display = 'none';
        }
    });

    // Annuler la suppression
    btnAnnuler.addEventListener('click', function () {
        modalConfirmation.style.display = 'none';
        overlay.style.display = 'none';
    });
});
