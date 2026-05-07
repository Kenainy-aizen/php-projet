// form-medicament.js
function setupMedicamentForm() {
    const form = document.getElementById('medicament-form');
    
    if (!form) return;
    
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        // 1. Récupération des données avec vérification
        const design = form.design.value.trim();
        const prix_unitaire = form.prix_unitaire.value.trim();
        
        // 2. Validation améliorée
        if (!design || !prix_unitaire || isNaN(prix_unitaire)) {
            alert('Veuillez remplir tous les champs correctement !');
            return;
        }

        // 3. Préparation des données
        const data = {
            design: design,
            prix_unitaire: parseFloat(prix_unitaire) // Conversion en nombre
        };

        try {
            // 4. Correction de l'URL (attention à l'orthographe)
            const response = await fetch('index.php?entity=medicament&action=create', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data)
            });

            // 5. Gestion des réponses HTTP
            if (!response.ok) {
                throw new Error(`Erreur HTTP: ${response.status}`);
            }

            const result = await response.json();
            
            if (result.success) {
                alert('Succès ! Médicament ajouté.');
                form.reset();
                closeModal();
                // Optional: Actualiser la liste
                window.location.reload();
            } else {
                throw new Error(result.message || 'Erreur du serveur');
            }
        } catch (error) {
            console.error('Erreur complète:', error);
            alert(`Erreur: ${error.message}`);
        }
    });
}

document.addEventListener('DOMContentLoaded', setupMedicamentForm);