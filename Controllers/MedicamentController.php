<?php
require_once __DIR__ . '/../Models/Medicament.php';
require_once __DIR__ . '/../config/db.php';

class MedicamentController {
    private $model;

    public function __construct() {
        $db = new Database();
        $this->model = new Medicament($db->getConnection());
    }

    public function index() {
        $medicament = $this->model->read();
        include __DIR__ . '/../Views1/medicament/read.php';
        //include __DIR__ . '/../Views/medicament/update.php';
    }

    // public function create() {
    //     $design = $_POST['design'];
    //     $result = $this->model->verification($design);
    //     print_r($result);
    //     if(!(count($result) > 0)) {
    //     if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //         if(isset($_POST['design'],$_POST['prix_unitaire'])){
    //             $trueMedoc = $this->model->generateNumber();
    //             $this->model->create($trueMedoc,$_POST['design'],$_POST['prix_unitaire']);
    //             //header('Location: index.php?entity=medicament');
    //         } else {
    //             echo "TOUS LE champ doit etre remplis";
    //         }
           
    //     } 
    //     header('Content-Type: application/json');
    //     echo json_encode(['success' => true]);
    // } else {
    //     header('Content-Type: application/json');

    //     echo json_encode(['success' => false]);
    // }
    // }

    public function create() {
        // Toujours définir le Content-Type en premier
        header('Content-Type: application/json');
    
        // Vérifier la méthode HTTP
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405); // Method Not Allowed
            echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
            return;
        }
    
        // Vérifier les champs obligatoires
        if (!isset($_POST['design']) || !isset($_POST['prix_unitaire'])) {
            http_response_code(400); // Bad Request
            echo json_encode(['success' => false, 'message' => 'Tous les champs doivent être remplis']);
            return;
        }
    
        $design = $_POST['design'];
        $prix_unitaire = $_POST['prix_unitaire'];
    
        // Vérification de l'existence du médicament
        $result = $this->model->verification($design);
        
        if (count($result) > 0) {
            http_response_code(409); // Conflict
            echo json_encode(['success' => false, 'message' => 'Ce médicament existe déjà']);
            return;
        }
    
        // Création du médicament
        try {
            $trueMedoc = $this->model->generateNumber();
            $this->model->create($trueMedoc, $design, $prix_unitaire);
            
            echo json_encode([
                'success' => true,
                'message' => 'Médicament ajouté avec succès',
                'data' => [
                    'code' => $trueMedoc,
                    'designation' => $design,
                    'prix' => $prix_unitaire
                ]
            ]);
        } catch (Exception $e) {
            http_response_code(500); // Internal Server Error
            echo json_encode(['success' => false, 'message' => 'Erreur lors de la création: ' . $e->getMessage()]);
        }
    }

    public function update($numMedoc) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            echo "<pre>";
            print_r($_POST);
            echo "<pre>";
            $numMedoc;
            $design = $_POST['design1'];
            $prix_unitaire = $_POST['prix_unitaire1'];
            //$stock = $_POST['stock'];
            $medicament1 = $this->model->update($numMedoc, $design, $prix_unitaire);
            if ($medicament1) {
            header('Location: index.php?entity=medicament');
            }
            else {
                echo "probleme";
            }
    
            
        } //else {
             //$medicament = $this->model->readOne($numMedoc);
             //if($medicament) {
             //$medicament = $this->model->read(); 
             //include __DIR__ . '/../Views1/medicament/read.php';
             //} else {
             //      echo "Medicament non trouve";
             //}
        //}
    }

    public function delete($numMedoc) {
        if($numMedoc) {
            $this->model->delete($numMedoc);
           // header('Location: index.php?entity=medicament');
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false]);
        }
    }

    public function rechercher() {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
           $design = $_POST['design'];
            $medicament = $this->model->recherche($design);
            
         include __DIR__ . '/../Views1/medicament/read.php';   
            
        }
    }

    public function ruptureDeStock() {
        $result = $this->model->ruptureDeStock();
        include __DIR__ . '/../Views/medicament/resultRuptureDeStock.php';
    }

    // public function recetteTotal() {
    //     if($_server['REQUEST_METHOD'] === 'POST') {
    //         $total = $this->model->recetteTotal();
    //     } else {
    //         echo "probleme";
    //     }
    // }

    public function afficheTop5() {
        $top5Medicaments = $this->model->getTop5MedicamentsVendus();
        include __DIR__ . '/../Views/medicament/resut.php';
    }
}   


?>