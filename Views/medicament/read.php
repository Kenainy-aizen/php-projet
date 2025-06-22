<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion de pharmacie</title>

    <link rel="stylesheet" href="/../styles1.css">
</head>
<body>
    <header>
            <nav>
                <ul>
                    <li><a href="index.php?entity=medicament">Médicaments</a></li>
                    <li><a href="index.php?entity=achat">Achats</a></li>
                    <li><a href="index.php?entity=entree">Entree</a></li>
                </ul>
            </nav>
    </header>


<!-- <form action="index.php?entity=medicament&action=rechercher" method="POST">
    <label for="design">Recherche un medicament :</label>
    <input type="text" id="design" name="design" placeholder="Entrez un mot-cle">
    <button type="submit">rechercher</button>
</form> -->

<!-- <a href="index.php?entity=medicament&action=ruptureDeStock" >Rupture de stock </a> -->


<!-- </form> -->

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

<!-- <a href="index.php?entity=medicament&action=create" class="btn" >creer</a> -->

<button class="btn1" onclick="openModal()">Ajouter</button>

<div id="modal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>Ajouter un Médicament</h2>
            <form action="index.php?entity=medicament&action=create" method="POST">
                <label for="design">Désignation :</label>
                <input type="text" name="design" id="design" required>

                <label for="prix_unitaire">Prix Unitaire :</label>
                <input type="number" name="prix_unitaire" id="prix_unitaire" required>

                <label for="stock">Stock :</label>
                <input type="number" name="stock" id="stock" value="0" required>

                <button type="submit" class="btn">Enregistrer</button>
            </form>
        </div>
</div>

<script>
        // Fonction pour ouvrir la boîte modale
        function openModal() {
            document.getElementById("modal").style.display = "flex";
        }

        // Fonction pour fermer la boîte modale
        function closeModal() {
            document.getElementById("modal").style.display = "none";
        }

</script>

        <?php
              $total = $this->model->recetteTotal();
              echo "<p> $total <p>";
        ?>
<a href="index.php?entity=medicament&action=afficheTop5">top5</a>

</body>
</html>