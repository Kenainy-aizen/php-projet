<form action="index.php?entity=medicament&action=rechercher" method="POST">
    <label for="design">Recherche un medicament :</label>
    <input type="text" id="design" name="design" placeholder="Entrez un mot-cle">
    <button type="submit">rechercher</button>
</form>


<Table>
    <tr>
        <th>Numero </th>
        <th>Design</th>
        <th>prix_unitaire</th>
        <th>Stock</th>
        <th>Actions</th>
        
    </tr>
    <?php foreach ($resultats as $resultats) : ?>
    <tr>
        <td> <?= $resultats['numMedoc'] ?></td>
        <td> <?= $resultats['Design'] ?></td>
        <td> <?= $resultats['prix_unitaire'] ?></td>
        <td> <?= $resultats['stock'] ?></td>
        <td>
            <a href="index.php?entity=medicament&action=update&id=<?= $resultats['numMedoc'] ?>">Modifier</a>
            <a href="index.php?entity=medicament&action=delete&id=<?= $resultats['numMedoc'] ?>">Supprimer</a>
            
        </td>
    </tr>
    <?php endforeach; ?>

</Table>

<a href="index.php?entity=medicament&action=create">creer</a>