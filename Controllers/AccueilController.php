<?php

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../Models/accueil.php';

class AcceuilController {
    private $model;

    public function __construct() {
        $db = new Database;
        $this->model = new accueil($db->getConnection());

    } 

    public function index() {
        include __DIR__ . '/../Views1/acceuil/read.php';
    }

    public function afficheTop5() {
        $top5Medicaments = $this->model->getTop5MedicamentsVendus();
       // include __DIR__ . '/../Views/medicament/resut.php';
       print_r($top5Medicaments);
    }
} 
?>