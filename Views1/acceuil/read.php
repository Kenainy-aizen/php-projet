<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ACCUEIL</title>
    <link rel="stylesheet" href="/ProjetPharma/Views1/acceuil/cssAcceuil.css">
    
    <nav class="navbarAll">
        <div>
          <a class="logo">G-pharm</a>
          
          <div class="" id="mynavbar">
            <ul>
            <li class="nav-item">
                  <a href="index.php?entity=acceuil&action=read"><button class="nav-link">Acceuil</button></a>
                </li>
                <li class="nav-item">
                  <a href="index.php?entity=medicament&action=read"><button class="nav-link" >Medicaments</button></a>
                </li>
                <li class="nav-item">
                  <a href="index.php?entity=achat&action=read"><button class="nav-link">Achats</button></a>
                </li>
                <li class="nav-item">
                   <a href="index.php?entity=entree&action=read"><button class="nav-link">Stocks</button></a>
          </li>
              
            </ul>          
          </div>
        </div>
      </nav>
    
</head>
<body> 
        <div class="divTop5">
          <h4></h4>
        <?php
          $result = $this->model->getTop5MedicamentsVendus();
            if(!empty($result)) {
              echo "<table>";
                echo "<tr><th>Nom medicament</th><th>Nombre d'achat</th></tr>";
                foreach ($result as $detail) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($detail['Design']) . "</td>";
                    echo "<td>" . htmlspecialchars($detail['total_vendu']) . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
              //echo $result;
            } 
        ?>
       </div> 
       <div class="divRupture">
        <?php
          $result1 = $this->model->ruptureDeStock();
          if(!empty($result1)) {
            echo "<table>";
              echo "<tr><th>Numero de medicament</th><th>Nom de medicament </th><th>Prix unitaire</th><th>Nombre de stock</th></tr>";
              foreach ($result1 as $detail1) {
                  echo "<tr>";
                  echo "<td>" . htmlspecialchars($detail1['numMedoc']) . "</td>";
                  echo "<td>" . htmlspecialchars($detail1['Design']) . "</td>";
                  echo "<td>" . htmlspecialchars($detail1['prix_unitaire']) . "</td>";
                  echo "<td>" . htmlspecialchars($detail1['stock']) . "</td>";
                  echo "</tr>";
              }
              echo "</table>";
          } 
        ?>
       </div>  
       <div class="divRecette">
        <?php
          $result2 = $this->model->recetteTotal();
          echo "<h4> $result2 ar </h4>";
        ?>
       </div>
       <div class="divHistogramme">
        <?php
            $recettes = $this->model->getRecettes5DerniersMois(); 
              
               //Préparer les données pour l'histogramme
              $mois = [];
              $montants = [];
              foreach ($recettes as $recette) {
                  $date = DateTime::createFromFormat('Y-m', $recette['mois']);
                  $mois[] = $date->format('F Y');
                  $montants[] = $recette['recette'];
              };
         ?>
              <div class="conteneur">
              <!-- Axe des ordonnées (Y) -->
                    <div class="axe-y">
                        <?php

                              if (!empty($montants)) {
                                  $maxMontant = max($montants);
                              } else {
                                  $maxMontant = 0; // ou une valeur par défaut
                              }



       
                        //$maxMontant = max($montants);
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
        
       </div>
</body>
</html>
