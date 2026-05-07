
<form action="index.php?entity=medicament&action=update&id=<?= $_GET['id'] ?>" method="POST">
    <label for="design"> Design </label>
    <input type="text" name="design" required>
    <label for="prix_unitaire"> Prix unitaire </label>
    <input type="number" name="prix_unitaire" required>
    <label for="stock"> Stock </label>
    <input type="number" name="stock" required>
    <button type="submit">ok</button>  
</form>
