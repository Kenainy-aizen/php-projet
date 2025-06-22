document.addEventListener('DOMContentLoaded', function () {
    // Gérer le clic sur le bouton de suppression
    const boutonsSupprimer = document.querySelectorAll('.Delete');

    boutonsSupprimer.forEach(btn => {
    
    btn.addEventListener('click', function () {
        const idToDelete = this.getAttribute('data-id'); // Récupérer l'ID de l'élément à supprimer
        const param1 = this.getAttribute('data-param1');
        const param2 = this.getAttribute('data-param2');

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
                fetch(`index.php?entity=achat&action=delete&id=${idToDelete}&param1=${param1}&param2=${param2}`, {
                    method: 'GET',
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Afficher un message de succès
                        // Swal.fire(
                        //     'Supprimé !',
                        //     'L\'élément a été supprimé.',
                        //     'success'
                        // ).then(() => {
                        //     // Recharger la page pour mettre à jour la liste
                        //     window.location.reload();
                        // });
                        window.location.reload();
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


// document.addEventListener('DOMContentLoaded', function () {

//     const boutonsPdf = document.querySelectorAll('.pdf');

//       boutonsPdf.forEach(btn => {

//             btn.addEventListener('click', function () {

//                  const idToPdf = this.getAttribute('data-id2');
//                  console.log(idToPdf);

//         //          Swal.fire({
//         //     title: 'Êtes-vous sûr ?',
//         //     text: "Vous ne pourrez pas annuler cette action !",
//         //     icon: 'warning',
//         //     showCancelButton: true,
//         //     confirmButtonColor: '#3085d6',
//         //     cancelButtonColor: '#d33',
//         //     confirmButtonText: 'Oui, supprimer !',
//         //     cancelButtonText: 'Annuler'
//         // })

//                 fetch(`index.php?entity=achat&action=CreatePdf&id=${idToPdf}`)
//             .then(() => {
//                 console.log("yes");
//             })
//             .catch(error => {
//                 console.error('Erreur:', error);
//                 Swal.fire('Erreur', 'Une erreur est survenue.', 'error');
//             });


//             });

//       });

// });

document.addEventListener('DOMContentLoaded', function () {
    const boutonsPdf = document.querySelectorAll('.pdf');

    boutonsPdf.forEach(btn => {
        btn.addEventListener('click', function () {
            const idToPdf = this.getAttribute('data-id2');
            const url = `index.php?entity=achat&action=CreatePdf&id=${idToPdf}`;
            window.open(url, '_blank'); // ← ouvrir dans un nouvel onglet
        });
    });
});
