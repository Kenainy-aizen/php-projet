<?php

error_reporting(E_ALL);
require_once "config/app.php";
ini_set("display_errors", 1);
ini_set("display_startup_errors", 1);

session_start();
require_once "Controllers/AccueilController.php";
require_once "Controllers/MedicamentController.php";
require_once "Controllers/EntreeController.php";
require_once "Controllers/AchatController.php";

$action = $_GET["action"] ?? "index";
$entity = $_GET["entity"] ?? "acceuil";

switch ($entity) {
    case "medicament":
        $controller = new MedicamentController();
        $idkey = "numMedoc";
        break;
    case "achat":
        $controller = new AchatController();
        $idkey = "numAchat";
        break;
    case "entree":
        $controller = new EntreeController();
        $idkey = "numEntree";
        break;
    case "acceuil":
        $controller = new AcceuilController();
        break;
    default:
        echo "Entite non reconnue .";
        exit();
}

switch ($action) {
    case "create":
        $controller->create();
        break;
    case "update":
        $id = $_GET["id"] ?? null;
        if ($id) {
            if ($entity === "achat") {
                $param1 = $_GET["param1"] ?? null; // Premier paramètre supplémentaire
                $param2 = $_GET["param2"] ?? null; // Deuxième paramètre supplémentaire
                $controller->update($id, $param1, $param2);
                echo "yes" . $param1;
            } else {
                $controller->update($id);
            }
            echo $id;
        } else {
            echo "ID manquant pour la mise a jour";
        }
        break;
    case "delete":
        $id = $_GET["id"] ?? null;
        if ($id) {
            if ($entity === "achat") {
                $param1 = $_GET["param1"] ?? null; // Premier paramètre supplémentaire
                $param2 = $_GET["param2"] ?? null; // Deuxième paramètre supplémentaire
                $controller->delete($id, $param1, $param2);
                //echo "yes".$param1;
            } else {
                $controller->delete($id);
            }
        } else {
            echo "ID manquant pour la suppression.";
        }
        break;
    case "rechercher":
        $controller->rechercher();

        break;
    case "ruptureDeStock":
        $controller->ruptureDeStock();
        break;
    case "finishTransaction":
        $controller->finishTransaction();
        break;
    case "genererPdf":
        $id = $_GET["id"] ?? null;
        if ($id) {
            $controller->genererPdf($id);
        } else {
            echo "ID manquant pour la suppression.";
        }
    case "CreatePdf":
        $id = $_GET["id"];
        $controller->CreatePdf($id);
        break;

    case "afficheTop5":
        $controller->afficheTop5();
        break;
    // case 'recetteTotal' :

    //         $controller->recetteTotal();
    case "afficherHistogrammeRecettes":
        $controller->afficherHistogrammeRecettes();
        break;

    default:
        $controller->index();
        break;
}

//include 'Views/layout.php';

?>
