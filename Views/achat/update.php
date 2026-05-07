<form action="index.php?entity=achat&action=update&id=<?= $_GET['id']?>&param1=<?= $_GET['param1'] ?>" method="POST">
    <label for="numMedoc"> numMedoc</label>
    <input type="text" name="numMedoc" >
    <label for="nomClient"> Nom client </label>
    <input type="text" name="nomClient" required>
    <label for="nbr"> nbr </label>
    <input type="number" name="nbr" required>
    <label for="dateAchat"> Date achat </label>
    <input type="date" name="dateAchat" required>
    <button type="submit">ok</button>  
</form>
