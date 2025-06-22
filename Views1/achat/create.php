<?php

    if(isset($_SESSION['error_message'])) {
        $error_message = $_SESSION['error_message'];
        $stock_initial = $_SESSION['stock_initial'];
        echo "<script>alert('$error_message Stock initial : $stock_initial');</script>";
        unset($_SESSION['error_message']);
        unset($_SESSION['stock_initial']);
    }
     
    if(isset($_SESSION['error_message'])) {
        echo "<p style='color: red;'>". $_SESSION['error_message'] . "</p>";
        unset($_SESSION['error_message']);
    }
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion de Pharmacie - Achats</title>
    <link rel="stylesheet" href="/ProjetPharma/styleCreate.css">
</head>
<body>
    <header>

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
            <!-- <form class="d-flex" action="">
              <input id="inputRecherche" type="text" placeholder="Taper ici pour rechercher">
              <button class="btnRecherche">Rechercher</button>
            </form> -->
          <!-- <a href=""><button class="notif">N</button></a> -->
            
          </div>
        </div>
      </nav>
        <!-- <nav>
            <ul>
                    <li><a href="index.php?entity=medicament">Médicaments</a></li>
                    <li><a href="index.php?entity=achat">Achats</a></li>
                    <li><a href="index.php?entity=entree">Entree</a></li>
            </ul>
        </nav> -->
    </header>

    <div class="container">
        <!-- Formulaire d'achat à gauche -->
        <section id="formulaire-achat">
            <h2>Formulaire d'Achat</h2>
            <form method="post" action="">
                <label for="numMedoc">Numéro du Médicament:</label>
                <input type="text" id="numMedoc" name="numMedoc" >

                <label for="nomClient">Nom du Client:</label>
                <input type="text" id="nomClient" name="nomClient" >

                <label for="nbr">Quantité:</label>
                <input type="number" id="nbr" name="nbr" >

                <label for="dateAchat">Date d'Achat:</label>
                <input type="date" id="dateAchat" name="dateAchat" >

                <button type="submit" name="valider">Valider l'Achat</button>
                <button type="submit" name="annuler">Annuler l'Achat</button>
                <button type="submit" name="nouvelleFacture">Nouvelle Facture</button>
                <button type="submit" name="genererPdf">Generer le pdf1</button>
            </form>
            <?php if (!empty($message)) : ?>
                <p><?php echo $message; ?></p>
            <?php endif; ?>
        </section>

        <!-- Affichage des achats à droite -->
        <section id="affichage-achats">
         
        <?php
            if (isset($_SESSION['numAchat'])) {
                $resultat = $this->model->afficherFacture($_SESSION['numAchat']);
                echo $resultat;
            }
        ?>
        </section>
    </div>

    <footer>
        <p>&copy; 2023 Gestion de Pharmacie</p>
    </footer>
</body>
</html>