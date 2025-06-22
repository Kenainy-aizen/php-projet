<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>STOCK</title>
    <link rel="stylesheet" href="/ProjetPharma/lib/sweetalert2/sweetalert2.min.css">
    <link rel="stylesheet" href="/ProjetPharma/Views1/entree/read.css">
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
</head>
<body>
<div class="titre">
        <h1>Liste des stocks</h1>
    </div>
    <div  style="margin-top: 55px;"><br><br><br>     
        
        <table style="margin-left: 250px;" class="tbl" id="enter">
            <colgroup>
                <col style="width: 200px;">
                <col style="width: 200px;">
                <col style="width: 200px;">
                <col style="width: 200px;">
                
            </colgroup>
            <thead style="position: fixed;">
                <tr class="trMedocTble">
                    <th style="width: 200px;">Numero d'Entree</th>
                    <th style="width: 200px;">Numero de Medicament</th>
                    <th style="width: 200px;">Stock Entree</th>
                    <th style="width: 200px;">Date D'Entree</th>
                    <th style="width: 210px; background-color:rgb(33, 33, 33);color: white;">Action</th>
                </tr>
            </thead>
            <tr style="height: 40px;"></tr>
            <tbody>

              <?php foreach ($entree as $entree) : ?>
              <tr>
                  <td> <?= $entree['numEntree'] ?></td>
                  <td> <?= $entree['numMedoc'] ?></td>
                  <td> <?= $entree['stockEntree'] ?></td>
                  <td> <?= $entree['dateEntree'] ?></td>
                  <td>
                      <button onclick="openModal1('<?= $entree['numEntree'] ?>', '<?= $entree['numMedoc'] ?>', <?= $entree['stockEntree'] ?>, '<?= $entree['dateEntree'] ?>')" style="background-color: rgb(193, 215, 251); border: 2px solid darkblue;" id="Edit">Editer</button>
                      <!-- <a href="index.php?entity=entree&action=delete&id=""><button style="background-color: rgb(255, 189, 189); border: 2px solid rgb(158, 3, 3);" id="Delete">Supprimer</button></a> -->
                      <button style="background-color: rgb(255, 189, 189); border: 2px solid rgb(158, 3, 3);" class="btnSupprimer" data-id="<?= $entree['numEntree'] ?>">Supprimer</button>
                        
                  </td>
              </tr>
              <?php endforeach; ?>

            </tbody>
            
        </table>

        <button onclick = "openModal()" class="btn1">Ajouter</button>
    </div>

    <div id="modal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeModal()">&times;</span>
                <h2>Ajouter un Médicament</h2>
                <form action="index.php?entity=entree&action=create" method="POST">
                    <label for="numMedoc">Numero de medicament :</label>
                    <input type="text" name="numMedoc" id="numMedoc" required>

                    <label for="stockEntree">stock entree :</label>
                    <input type="number" name="stockEntree" id="stockEntree" required>

                    <!-- <label for="dateEntree">Stock :</label>
                    <input type="date" name="dateEntree" id="dateEntree" required> -->

                    <button type="submit" class="btn">Enregistrer</button>
                </form>
            </div>
    </div>

    <div id="modal1" class="modal1">
            <div class="modal-content1">
                <span class="close" onclick="closeModal1()">&times;</span>
                <h2>Modification un Médicament</h2>
                <form id="medicament-form" action="index.php?entity=entree&action=update" method="POST">
                    <label for="numMedoc">Numero medicament :</label>
                    <input type="text" name="numMedoc" id="numMedoc1" value="" >

                    <label for="stockEntree">Stock entree :</label>
                    <input type="number" name="stockEntree" id="stockEntree1" value="" >

                    <label for="dateEntree">Date d'entree:</label>
                    <input type="date" name="dateEntree" id="dateEntree1" value="" >

                    <button type="submit" class="btn2">Enregistrer</button>
                </form>
            </div>
    </div>

    <script>
        // Fonction pour ouvrir la boîte modale
        function openModal() {
            document.getElementById("modal").style.display = "flex";
        }

        function openModal1(numEntree, numMedoc, stockEntree, dateEntree) {
            document.getElementById('numMedoc1').value = numMedoc;
            document.getElementById('stockEntree1').value = stockEntree;
            document.getElementById('dateEntree1').value = dateEntree;

            const form = document.getElementById('medicament-form');
            form.action = `index.php?entity=entree&action=update&id=${numEntree}`;

            document.getElementById("modal1").style.display = "flex";
            console.log(numMedoc);
            console.log(stockEntree);
            console.log(dateEntree);
        }


        // Fonction pour fermer la boîte modale
        function closeModal() {
            document.getElementById("modal").style.display = "none";
        }

        function closeModal1() {
            document.getElementById("modal1").style.display = "none";
        }

        window.onclick = function(event) {
            const modal1 = document.getElementById('modal1');
            const modal = document.getElementById('modal');
            if(event.target === modal1) {
                modal1.style.display = "none";
            } else if(event.target == modal) {
                modal.style.display = "none";
            }
        };
        
    </script>

    <script src="/ProjetPharma/lib/sweetalert2/sweetalert2.all.min.js" ></script>
    <script src="/ProjetPharma/Views1/entree/script.js"></script>
       

</body>
</html>