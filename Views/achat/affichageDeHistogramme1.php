<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Histogramme des Recettes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .histogramme {
            display: flex;
            align-items: flex-end;
            justify-content: space-around;
            height: 300px;
            width: 90%;
            max-width: 600px;
            margin: 50px auto;
            border-left: 2px solid #333;
            border-bottom: 2px solid #333;
            padding: 10px;
            /* background-color: black; */
        }
        .barre {
            width: 15%;
            background-color: white;
            text-align: center;
            color: green;
            position: relative;
        }
        .barre span {
            position: absolute;
            top: -20px;
            width: 100%;
            font-size: 14px;
        }
        .mois {
            margin-top: 5px;
            text-align: center;
            font-size: 14px;
        }
    </style>
</head>
<body>

<div class="histogramme">
    <?php
    // Trouver la valeur maximale pour normaliser les hauteurs des barres
    $maxRecette = max(array_column($recettes, 'recette'));

    foreach ($recettes as $donnee) {
        $hauteur = ($donnee['recette'] / $maxRecette) * 100; // Hauteur en pourcentage
        echo '<div class="histogram-bar-container">';
        echo '<div class="bar-background">';
        echo '<div class="bar-fill" style="">' . $hauteur . '%;"><span>' . number_format($donnee['recette'], 0, ',', ' ') . ' €</span></div>';
        echo '<div class="mois">' . htmlspecialchars($donnee['mois']) . '</div>';
        echo '</div>';
    }
    ?>
</div>

</body>
</html>