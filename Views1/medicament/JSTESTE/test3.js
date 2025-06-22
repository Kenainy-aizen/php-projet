function openModal() {
    document.getElementById('modal').style.display = 'block';
}

function closeModal() {
    document.getElementById('modal').style.display = 'none';
}

// Gestion du formulaire
function setupMedicamentForm() {
    const form = document.getElementById('medicament-form');
    
    if (!form) return; // Si le formulaire n'existe pas, on sort
    
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        // Récupération des données
        const formData = new FormData(form);
        const data = {
            design: formData.get('design').trim(),
            prix_unitaire: formData.get('prix_unitaire')
        };

        // Validation simple côté client
        if (!data.design || !data.prix_unitaire) {
            alert('Veuillez remplir tous les champs !');
            return;
        }

        try {
            // Envoi des données
            const response = await fetch('index.php?entity=medicament&action=create', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                
                body: JSON.stringify(data)
            });
            console.log(data);
            const result = await response.json();
            
            if (result.success) {
                alert('Médicament ajouté avec succès !');
                form.reset();
                closeModal();
            } else {
                throw new Error(result.message || 'Erreur lors de l\'ajout');
            }
        } catch (error) {
            console.error('Erreur:', error);
            alert('Erreur: ' + (error.message || 'Une erreur est survenue'));
        }
    });
}

// Initialisation quand le DOM est chargé
document.addEventListener('DOMContentLoaded', function() {
    setupMedicamentForm();
});


