document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.btn1').forEach(btn => {
        btn.addEventListener('click', function () {

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
