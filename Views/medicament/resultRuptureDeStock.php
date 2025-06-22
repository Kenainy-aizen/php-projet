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
    <?php foreach ($result as $result) : ?>
    <tr>
        <td> <?= $result['numMedoc'] ?></td>
        <td> <?= $result['Design'] ?></td>
        <td> <?= $result['prix_unitaire'] ?></td>
        <td> <?= $result['stock'] ?></td>
        <td>
            <a href="index.php?entity=medicament&action=update&id=<?= $result['numMedoc'] ?>">Modifier</a>
            <a href="index.php?entity=medicament&action=delete&id=<?= $result['numMedoc'] ?>">Supprimer</a>
            
        </td>
    </tr>
    <?php endforeach; ?>

</Table>

<a href="index.php?entity=medicament&action=create">creer</a>