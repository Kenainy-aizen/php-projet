document.addEventListener('DOMContentLoaded', function () {
    const btnAjouter = document.getElementById('btn1');
    
    // Gérer le clic sur le bouton d'ajout
    btnAjouter.addEventListener('click', function () {
        // Afficher une boîte modale avec le formulaire
        Swal.fire({
            // title: 'Ajouter un nouveau médicament',
            html: `              
                <div id="modal" class="modal">
                    <div class="modal-content">
                        <span class="close" onclick="closeModal()">&times;</span>
                        <h2>Ajouter un Médicament</h2>
                        <form action="index.php?entity=medicament&action=create" method="POST">
                            <label for="design">Désignation :</label>
                            <input type="text" name="design" id="design" required>

                            <label for="prix_unitaire">Prix Unitaire :</label>
                            <input type="number" name="prix_unitaire" id="prix_unitaire" required>

                            <!-- <label for="stock">Stock :</label>
                            <input type="number" name="stock" id="stock" value="0" required> -->

                            <button type="submit" class="btn">Enregistrer</button>
                        </form>
                    </div>
                </div>
            `,
            focusConfirm: false,
            showCancelButton: true,
            confirmButtonText: 'Enregistrer',
            //cancelButtonText: 'Annuler',
            preConfirm: () => {
                // Récupérer les valeurs du formulaire
                //const nom = document.getElementById('nomMedoc').value;
                const description = document.getElementById('design').value;
                const prix = document.getElementById('prix_unitaire').value;
                //const stock = document.getElementById('stock').value;
                
                // Validation simple
                if (!prix || !description) {
                    Swal.showValidationMessage('Veuillez remplir tous les champs obligatoires');
                    return false;
                }
                
                // Retourner les données pour le traitement
                return {

                    description: description,
                    prix: prix,
            
                };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Si l'utilisateur confirme, envoyer les données via AJAX
                const formData = result.value;
                
                fetch('index.php?entity=medicament&action=create', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(formData)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire(
                            'Succès !',
                            'Le médicament a été ajouté avec succès.',
                            'success'
                        ).then(() => {
                            // Recharger la page pour afficher le nouveau médicament
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
                    console.error('Erreur:', error);
                    Swal.fire(
                        'Erreur',
                        'Une erreur est survenue lors de la communication avec le serveur.',
                        'error'
                    );
                });
            }
        });
    });
});