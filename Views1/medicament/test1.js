document.addEventListener('DOMContentLoaded', function() {
    const medicamentForm = document.getElementById('medicament-form');
    
    if (medicamentForm) {
        medicamentForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Afficher un indicateur de chargement
            Swal.fire({
                title: 'Envoi en cours...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Récupérer les données du formulaire
            const formData = new FormData(medicamentForm);
            const data = {};
            formData.forEach((value, key) => {
                data[key] = value;
            });

            // Envoyer les données via AJAX
            fetch('index.php?entity=medicament&action=create', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams(data)
                // body: JSON.stringify(data)
            })
            .then(response => {
                // Vérifier d'abord si la réponse est du JSON valide
                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    throw new TypeError("La réponse n'est pas du JSON valide");
                }
                return response.json();
            })
            .then(data => {
                Swal.close();
                if (data.success) {
                    Swal.fire(
                        'Succès !',
                        'Le médicament a été ajouté avec succès.',
                        'success'
                    ).then(() => {
                        // Réinitialiser le formulaire
                        //medicamentForm.reset();
                        // Recharger la page si nécessaire
                        window.location.reload();
                    });
                } else {
                    Swal.fire(
                        'Erreur',
                        data.message || 'Une erreur est survenue lors de l\'ajout.',
                        'error'
                    );
                }
            })
            .catch(error => {
                Swal.close();
                console.error('Erreur:', error);
                Swal.fire(
                    'Erreur',
                    'Le serveur a renvoyé une réponse invalide. Veuillez vérifier la console pour plus de détails.',
                    'error'
                );
            });
        });
    }
});