document.addEventListener('DOMContentLoaded', function () {
    const boutonsSupprimer = document.querySelectorAll('.btnSupprimer');
    // Gérer le clic sur le bouton de suppression
    boutonsSupprimer.forEach(btn => {
    btn.addEventListener('click', function () {
        const idToDelete = this.getAttribute('data-id'); // Récupérer l'ID de l'élément à supprimer

        // Afficher une boîte modale de confirmation avec SweetAlert
        Swal.fire({
            title: 'Êtes-vous sûr ?',
            text: "Vous ne pourrez pas annuler cette action !",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Oui, supprimer !',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                // Si l'utilisateur confirme, envoyer une requête AJAX pour supprimer l'élément
                fetch(`index.php?entity=entree&action=delete&id=${idToDelete}`, {
                    method: 'GET',
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Afficher un message de succès
                        Swal.fire(
                            'Supprimé !',
                            'L\'élément a été supprimé.',
                            'success'
                        ).then(() => {
                            // Recharger la page pour mettre à jour la liste
                            window.location.reload();
                        });
                    } else {
                        // Afficher un message d'erreur
                        Swal.fire(
                            'Erreur',
                            'Une erreur est survenue lors de la suppression.',
                            'error'
                        );
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    Swal.fire(
                        'Erreur',
                        'Une erreur est survenue lors de la suppression.',
                        'error'
                    );
                });
            }
        });
    });
});
});