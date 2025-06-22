
<?php
// Regrouper les lignes par numéro d’achat
$groupes = [];
foreach ($achat as $ligne) {
    $key = $ligne['numAchat'];
    $groupes[$key][] = $ligne;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ACHAT</title>
    <link rel="stylesheet" href="/ProjetPharma/lib/sweetalert2/sweetalert2.min.css">
    <link rel="stylesheet" href="/ProjetPharma/Views1/achat/read.css">
    <nav class="navbarAll">
        <div>
          <a class="logo">G-pharm</a>
          
          <div class="" id="mynavbar">
           
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
            <form class="d-flex" action="index.php?entity=achat&action=rechercher" method="POST" >
              <input id="inputRecherche" name="inputRecherche" type="text" placeholder="Taper ici pour rechercher">
              <button class="btnRecherche" type="submit" >Rechercher</button>
            </form>
          <a href=""><button class="notif">N</button></a>
            
          </div>
        </div>
      </nav>
</head>
<body>
    
<div class="titre">
        <h1>Liste des achat</h1>
    </div>
    <div  style="margin-top: 55px;"><br><br><br>   
        <div class="teste">
    
        </div>
        <br><br><br>   
        
        <table class="tbl" id="AchatsVidy">
          
            <colgroup>
                <col style="width: 200px;">
                <col style="width: 200px;">
                <col style="width: 520px;">
                <col style="width: 100px;">
                <col style="width: 200px;">
            </colgroup>
            
            <thead  style="position: fixed;">
                <tr class="trMedocTble">
                    <th style="width: 200px;">Numero d'Achat</th>
                    <th style="width: 200px;">Numero de Medicament</th>
                    <th style="width: 520px;">Nom du Client</th>
                    <th style="width: 100px;">Nombre</th>
                    <th style="width: 200px;">Date d'Achat</th>
                    <th style="width: 210px; background-color:rgb(33, 33, 33);color: white;">Action</th>
                </tr>
            </thead>
            <tr style="height: 40px;"></tr>

        
 

<?php foreach ($groupes as $numAchat => $lignes): ?>
    <?php 
        $rowspan = count($lignes);
        $client = $lignes[0]['nomClient'];
        $dateAchat = $lignes[0]['dateAchat'];
    ?>
    <?php foreach ($lignes as $i => $ligne): ?>
        <tr>
            <?php if ($i === 0): ?>
                <td rowspan="<?= $rowspan ?>"><?= htmlspecialchars($numAchat) ?></td>
            <?php endif; ?>

            <td><?= htmlspecialchars($ligne['numMedoc']) ?></td>

            <?php if ($i === 0): ?>
                <td rowspan="<?= $rowspan ?>"><?= htmlspecialchars($client) ?></td>
            <?php endif; ?>

            <td><?= htmlspecialchars($ligne['nbr']) ?></td>

            <?php if ($i === 0): ?>
                <td rowspan="<?= $rowspan ?>"><?= htmlspecialchars($dateAchat) ?></td>
                <td rowspan="<?= $rowspan ?>">
                    <!-- <button 
                        onclick="openModal1('<?= $ligne['numAchat'] ?>', '<?= $ligne['numMedoc'] ?>', '<?= $ligne['nomClient'] ?>', <?= $ligne['nbr'] ?>, '<?= $ligne['dateAchat'] ?>')" 
                        style="background-color: rgb(193, 215, 251); border: 2px solid darkblue;" 
                        id="Edit">Editer</button> -->

                    <button  style="background-color: rgb(193, 215, 251); border: 2px solid darkblue;" id="Edit" class="pdf" data-id2="<?= $ligne['numAchat'] ?>">Editer en pdf</button>

                    <button 
                        style="background-color: rgb(255, 189, 189); border: 2px solid rgb(158, 3, 3);" 
                        class="Delete" 
                        data-id="<?= $ligne['numAchat'] ?>" 
                        data-param1="<?= $ligne['numMedoc'] ?>" 
                        data-param2="<?= $ligne['nbr'] ?>">Supprimer</button>
                </td>
            <?php endif; ?>
        </tr>
    <?php endforeach; ?>
<?php endforeach; ?>



          
        </table>
        <div><a href="index.php?entity=achat&action=create" ><button class="btn1">Ajouter</button></a></div>
        <!-- <div><button class="ExporPdf">Exporter en pdf</button></div> -->
    </div>

    <div class="">

    </div>

    <div id="modal1" class="modal1">
            <div class="modal-content1">
                <span class="close" onclick="closeModal1()">&times;</span>
                <h2>Modification de liste d'achat</h2>
                <form id="medicament-form" action="index.php?entity=achat&action=update" method="POST">
                    <label for="numMedoc">Numero medicament :</label>
                    <input type="text" name="numMedoc" id="numMedoc" value="" >

                    <label for="nomClient">Nom client :</label>
                    <input type="text" name="nomClient" id="nomClient" value="" >

                    <label for="nbr">Stock :</label>
                    <input type="number" name="nbr" id="nbr" value="" >

                    <label for="dateAchat">Date d'achat :</label>
                    <input type="date" name="dateAchat" id="dateAchat">

                    <button type="submit" class="btn2">Enregistrer</button>
                </form>
            </div>
    </div>
    <script>
        // Fonction pour ouvrir la boîte modal

        function openModal1(numAchat, numMedoc, nomClient, nbr, dateAchat) {
            document.getElementById('numMedoc').value = numMedoc;
            document.getElementById('nomClient').value = nomClient;
            document.getElementById('dateAchat').value = dateAchat;
            document.getElementById('nbr').value = nbr;

            const form = document.getElementById('medicament-form');
            form.action = `index.php?entity=achat&action=update&id=${numAchat}&param1=${numMedoc}&param2=${nbr}`;

            document.getElementById("modal1").style.display = "flex";
            console.log(numMedoc);
            console.log(nomClient);
            console.log(form.action);
        }


        // Fonction pour fermer la boîte modale

        function closeModal1() {
            document.getElementById("modal1").style.display = "none";
        }

        window.onclick = function(event) {
            const modal1 = document.getElementById('modal1');

            if(event.target === modal1) {
                modal1.style.display = "none";
            } 

        };
    </script>

    <script src="/ProjetPharma/lib/sweetalert2/sweetalert2.all.min.js" ></script>
    <script src="/ProjetPharma/Views1/achat/script.js"></script>
    
    
</body>
</html>

 