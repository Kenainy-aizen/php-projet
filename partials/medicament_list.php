<Table>
    <tr>
        <th>Numero </th>
        <th>Design</th>
        <th>prix_unitaire</th>
        <th>Stock</th>
        <th>Actions</th>
        
    </tr>
    <?php foreach ($medicament as $medicament) : ?>
    <tr>
        <td> <?= $medicament['numMedoc'] ?></td>
        <td> <?= $medicament['Design'] ?></td>
        <td> <?= $medicament['prix_unitaire'] ?></td>
        <td> <?= $medicament['stock'] ?></td>
        <td>
            <a href="index.php?entity=medicament&action=update&id=<?= $medicament['numMedoc'] ?>">Modifier</a>
            <a href="index.php?entity=medicament&action=delete&id=<?= $medicament['numMedoc'] ?>">Supprimer</a>
            
        </td>
    </tr>
    <?php endforeach; ?>

</Table>

<a href="index.php?entity=medicament&action=create">creer</a>