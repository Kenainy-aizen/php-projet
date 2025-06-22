<?php
     
    require_once __DIR__ . "/../Models/Entree.php";
    require_once __DIR__ . "/../config/db.php";

    class EntreeController {
        private $model;

        public function __construct() {
            $db = new Database;
            $this->model = new Entree($db->getConnection());
        }

    public function index() {
        $entree = $this->model->read();
        include __DIR__ . '/../Views1/entree/read.php';
    }

    public function create() {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $numEntree = $this->model->generateNumber();
            $this->model->create($numEntree,$_POST['numMedoc'],$_POST['stockEntree']);
            var_dump($_POST['numMedoc'],$_POST['stockEntree']);
            $this->model->addition($_POST['numMedoc'],$_POST['stockEntree']);

            header('Location: index.php?entity=entree');
        } else {
            include __DIR__ . '/../Views/entree/create.php';
        }
    }

    public function update($numEntree) {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $numMedoc = $_POST['numMedoc'];
            $numEntree ;
            $stockEntree = $_POST['stockEntree'];
            $dateEntree = $_POST['dateEntree'];
            $this->model->modAddition($_POST['numMedoc'],$_POST['stockEntree'],$numEntree);
            $this->model->update($numEntree,$numMedoc,$stockEntree,$dateEntree);

            header('Location: index.php?entity=entree');
        } else {
            include __DIR__ . '/../Views/entree/update.php';
        }
    }

    public function delete($numEntree) {
        if($numEntree) {
            $this->model->suppAddition($numEntree);
            $this->model->delete($numEntree);

            echo json_encode(['success' => true]);

        } else {
            echo json_encode(['success' => false]);
        }
      //  header('Location: index.php?entity=entree');
    }
    }



?>