<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

</head>
<body>
    
<Table>
    <tr>
        <th>Numero </th>
        <th>Numero medicament</th>
        <th>nomclient</th>
        <th>nbr</th>
        <th>date</th>
        
    </tr>
    <?php foreach ($achat as $achat) : ?>
    <tr>
        <td> <?= $achat['numAchat'] ?></td>
        <td> <?= $achat['numMedoc'] ?></td>
        <td> <?= $achat['nomClient'] ?></td>
        <td> <?= $achat['nbr'] ?></td>
        <td> <?= $achat['dateAchat'] ?></td>
        <td>
            <a href="index.php?entity=achat&action=update&id=<?= $achat['numAchat'] ?>">Modifier</a>
            <a href="index.php?entity=achat&action=delete&id=<?= $achat['numAchat'] ?>">Supprimer</a>
            
        </td>

    </tr>
    <?php endforeach; ?>

</Table>

<a href="index.php?entity=achat&action=create">creer</a>
<a href="fonctionAchat.php">creer</a>
<a href="index.php?entity=achat&action=afficherHistogrammeRecettes"> affiche histogramme</a>

</body>
</html>
