<?php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter de Medicament</title>
    <link rel="stylesheet" href="css.css">
</head>
<body>
    
    <form id="ajouterMedic" action="ajouterMedic">
        <h1 class="titleAjouter">Ajouter de Medicament</h1>
        <div>
            <label id="labelMedic" for="numMedic">Numero de Medicament:</label>
            <input style="margin: 10px 0px 0px 20px;" id="inputMedicAjou" type="text" required>
        </div>
        <div>
            <label id="labelMedic" for="design">Design:</label>
            <input style="margin: 10px 0px 0px 146px;" id="inputMedicAjou" type="text" required>
        </div>
        <div>
            <label id="labelMedic" for="PU">Prix Unitaire:</label>
            <input style="margin: 10px 0px 0px 100px;" id="inputMedicAjou" type="number" required>
        </div>
        <div>
            <label id="labelMedic" for="Stock">Stock:</label>
            <input style="margin: 10px 0px 0px 152px;" id="inputMedicAjou" type="number" required>
        </div>
        <div style="margin: 20px 0px 0px 200px;">
            <button onclick="window.open('tableMedoc.html')" style="background-color: yellow;" id="buttonAjMedic" >Ajouter</button>
            <button onclick="window.open('tableMedoc.html')" style="background-color: red; color: white;" id="buttonAjMedic" >Annuler</button>
        </div>
    </form>
</body>
</html>