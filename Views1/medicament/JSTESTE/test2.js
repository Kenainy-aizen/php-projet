document.addEventListener('DOMContentLoaded', function() {
    const medicamentForm = document.getElementById('medicament-form');
    
    if (medicamentForm) {
        medicamentForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Afficher un indicateur de chargement
            Swal.fire({
                title: 'Envoi en cours...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            // Récupérer les données du formulaire
            const formData = new FormData(medicamentForm);
            const data = Object.fromEntries(formData.entries());

            // Envoyer les données via AJAX
            fetch('index.php?entity=medicament&action=create', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data)
            })
            .then(async response => {
                const text = await response.text();
                try {
                    const json = JSON.parse(text);
                    return {response, json};
                } catch {
                    throw new Error(`Réponse invalide: ${text}`);
                }
            })
            .then(({response, json}) => {
                if (!response.ok) {
                    throw new Error(json.message || 'Erreur serveur');
                }
                return json;
            })
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'Succès !',
                        text: data.message || 'Médicament ajouté avec succès',
                        icon: 'success'
                    }).then(() => {
                        medicamentForm.reset();
                        // Optionnel: Mettre à jour l'UI sans rechargement
                        if (data.data) {
                            console.log('Nouveau médicament:', data.data);
                        }
                    });
                } else {
                    throw new Error(data.message || 'Erreur lors de l\'ajout');
                }
            })
            .catch(error => {
                Swal.fire({
                    title: 'Erreur',
                    text: error.message,
                    icon: 'error'
                });
                console.error('Erreur:', error);
            });
        });
    }
});