<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Histogramme des Recettes</title>
    <style>
        .conteneur {
            display: flex;
            align-items: flex-end;
            gap: 20px;
            padding: 20px;
            border: 1px solid #ccc;
            position: relative;
        }
        .axe-y {
            display: flex;
            flex-direction: column-reverse;
            justify-content: space-between;
            height: 300px;
            padding-right: 10px;
            border-right: 1px solid #000;
        }
        .axe-y div {
            text-align: right;
            padding-right: 10px;
        }
        .histogramme {
            display: flex;
            align-items: flex-end;
            height: 300px;
            gap: 20px;
            flex-grow: 1;
        }
        .barre {
            width: 50px;
            text-align: center;
            color: black;
            font-weight: bold;
            position: relative;
        }
        .barre::before {
            content: '';
            position: absolute;
            left: 0;
            right: 0;
            height: 1px;
            background-color: #ccc;
            top: 0;
        }
        .axe-x {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
            padding: 0 20px;
        }
        .axe-x div {
            width: 50px;
            text-align: center;
        }
    </style>
</head>
<body>
    <h2>Histogramme des Recettes des 5 Derniers Mois</h2>

    <div class="conteneur">
        <!-- Axe des ordonnées (Y) -->
        <div class="axe-y">
            <?php
            $maxMontant = max($montants);
            $intervalles = 5; // Nombre de tirets sur l'axe Y
            for ($i = 0; $i <= $intervalles; $i++) {
                $valeur = ($maxMontant / $intervalles) * $i;
                echo "<div>" . number_format($valeur, 0, ',', ' ') . " Ar</div>";
            }
            ?>
        </div>

        <!-- Histogramme -->
        <div class="histogramme">
            <?php
            // Tableau de couleurs pour les barres
            $couleurs = ['#4CAF50', '#2196F3', '#FFC107', '#9C27B0', '#E91E63'];
            foreach ($montants as $index => $montant) :
                $couleur = $couleurs[$index % count($couleurs)]; // Choisir une couleur
            ?>
                <div class="barre" style="height: <?= ($montant / $maxMontant) * 100 ?>%; background-color: <?= $couleur ?>;">
                    <?= $mois[$index] ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Axe des abscisses (X) -->
    <!-- <div class="axe-x">
        <?php foreach ($mois as $m) : ?>
        
        <?php endforeach; ?>
    </div> -->
</body>
</html>