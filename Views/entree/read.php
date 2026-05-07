<Table>
    <tr>
        <th>Numero </th>
        <th>Numero medicament</th>
        <th>stock entree</th>
        <th>date entre</th>
        
    </tr>
    <?php foreach ($entree as $entree) : ?>
    <tr>
        <td> <?= $entree['numEntree'] ?></td>
        <td> <?= $entree['numMedoc'] ?></td>
        <td> <?= $entree['stockEntree'] ?></td>
        <td> <?= $entree['dateEntree'] ?></td>
        <td>
            <a href="index.php?entity=entree&action=update&id=<?= $entree['numEntree'] ?>">Modifier</a>
            <a href="index.php?entity=entree&action=delete&id=<?= $entree['numEntree'] ?>">Supprimer</a>
            
        </td> 

    </tr>
    <?php endforeach; ?>

</Table>

<a href="index.php?entity=entree&action=create">creer</a>
