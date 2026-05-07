
<form action="index.php?entity=entree&action=update&id=<?= $_GET['id'] ?>" method="POST">
    <label for="numMedoc"> numMedoc </label>
    <input type="text" name="numMedoc" required>
    <label for="stockEntree"> stockEntree </label>
    <input type="number" name="stockEntree" required>
    <label for="dateEntree"> dateEntree </label>
    <input type="date" name="dateEntree" required>
    <button type="submit">ok</button>  
</form>
