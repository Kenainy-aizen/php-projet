

<div id="modal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>Ajouter un Médicament</h2>
                <form action="index.php?action=create" method="POST">
                    <label for="design">Designation :</label>
                    <input type="text" name="design" required>
                    <label for="prix_unitaire">Prix unitaire :</label>
                    <input type="number" name="prix_unitaire" required>
                    <label for="stock">Stock</label>
                    <input type="number" name="stock" >
                    <button type="submit">creer</button>
                </form>
        </div>
</div>