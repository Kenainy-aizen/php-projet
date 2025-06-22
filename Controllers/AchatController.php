<?php 

require_once __DIR__ . '/../Models/Achat.php';
require_once __DIR__ . '/../config/db.php';

    class AchatController {
        private $model;

        public function __construct() {
            $db = new Database;
            $this->model = new Achat($db->getConnection());
        }

        public function index() {
            $achat = $this->model->read();
            include __DIR__ . '/../Views1/achat/read.php';
        }


        public function create() {
            if($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (isset($_POST['nouvelleFacture'])) {
                    // Réinitialiser la facture
                    unset($_SESSION['numAchat']);
                    header('Location: index.php?entity=achat&action=create');
                } elseif (isset($_POST['valider'])) {

                    if(!isset($_SESSION['numAchat'])) {
                        $_SESSION['numAchat'] = $this->model->generateNumber(); 
                    }
                        $trueAchat = $_SESSION['numAchat'];

                    if(isset($_POST['nomClient'],$_POST['nbr'],$_POST['dateAchat'])) {
                        
                        $result = $this->model->soustraction($_POST['numMedoc'],$_POST['nbr']);

                        if($result === true) {
                            $this->model->create($trueAchat,$_POST['numMedoc'],$_POST['nomClient'],$_POST['nbr'],$_POST['dateAchat']);       
                                header('Location: index.php?entity=achat&action=create');
                            } else {
                                $_SESSION['error_message'] = $result['error'];
                                $_SESSION['stock_initial'] = $result['stock_initial'];
                                header('Location: index.php?entity=achat&action=create');
                            }                 
                    }
                 
                } 
                elseif(isset($_POST['genererPdf'])){
                    if(isset($_SESSION['numAchat'])) {
                    $this->model->genererPdf($_SESSION['numAchat']);
                    }

                }
                
                else {
                    echo "TOUS LE champ doit etre remplis";
                    $this->model->delete1($_SESSION['numAchat']);
                    header('Location: index.php?entity=achat&action=create');

                } 
            } else {
                //echo "Hello";
                include __DIR__ . '/../Views1/achat/create1.php';
            }
        
        }

        public function update($numAchat,$numMedoc1,$nbr1) {
            if($_SERVER['REQUEST_METHOD'] === 'POST') {
                $numAchat = $_GET['id'] ;
                $numMedoc1 = $_GET['param1'] ;
                $numMedoc = $_POST['numMedoc'];
                $nomClient = $_POST['nomClient'];
                $nbr = $_POST['nbr'];
                $dateAchat = $_POST['dateAchat'];

                $this->model->modSoustraction($numAchat,$numMedoc,$nbr,$numMedoc1,$nbr1);

                if($this->model->update($numAchat,$numMedoc,$nomClient,$nbr,$dateAchat,$numMedoc1,$nbr1)){
                    $this->model->read();
                    header('Location: index.php?entity=achat');
                  echo $numMedoc1;

                } else {
                    echo "erreur";
                }
                
            } else {
                include __DIR__ . '/../Views/achat/update.php';
            }
        }

        public function CreatePdf($numAchat){

              header('Content-Type: application/json');
    //            var_dump("CONTROLLER OK : $numAchat");
    // exit;

            if($numAchat) {
             

             $this->model->genererPdf($numAchat);
            echo json_encode(['success' => true]);  

            } else {
             echo json_encode(['success' => false]); 
            }

            //exit;

        }

        public function delete($numAchat, $numMedoc, $nbr) {
  
        if($numAchat) {
            $this->model->supSoustraction($numAchat, $numMedoc, $nbr);
            $this->model->delete($numAchat, $numMedoc, $nbr);
            echo json_encode(['success' => true]);       
        } else {
            echo json_encode(['success' => false]);       
        }
          // header('Location: index.php?entity=achat');
    
        }    
        
        public function finishTransaction() {
            if(isset($_SESSION['numAchat'])) {
                unset($_SESSION['numAchat']);
                echo "Transaction termine ";    
            } else {
                echo "Aucune transaction en cours.";
            }
        }

        public function genererPdf($numAchat) {
            if($_SERVER['REQUEST_METHOD'] === 'POST') {
            
                $this->model->genererPdf($numAchat);
            }
            else {
                echo "Kelly";
            }
        }

        public function gestionAchats() {
            // Récupérer les achats existants
            $achats = $this->achatModel->getAllAchats();
        
            // Traitement du formulaire d'ajout d'achat
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (isset($_POST['valider'])) {
                    $numMedoc = $_POST['numMedoc'];
                    $nomClient = $_POST['nomClient'];
                    $nbr = $_POST['nbr'];
                    $dateAchat = $_POST['dateAchat'];
        
                    // Générer un numéro de facture
                    $numAchat = $this->achatModel->genererNumAchat();
        
                    // Enregistrer l'achat
                    $this->achatModel->createAchat($numAchat, $numMedoc, $nomClient, $nbr, $dateAchat);
        
                    // Rediriger pour éviter la soumission multiple du formulaire
                    header('Location: index.php?entity=achat&action=gestionAchats');
                    exit();
                } elseif (isset($_POST['annuler'])) {
                    // Annuler l'achat (ne rien faire)
                    $message = "Achat annulé.";
                }
            }
        
            // Afficher la vue
            include __DIR__ . '/../Views/achat/gestionAchats.php';
        }

        public function afficherHistogrammeRecettes() {
            // Récupérer les données des recettes
            $recettes = $this->model->getRecettes5DerniersMois();
        
            //Préparer les données pour l'histogramme
            $mois = [];
            $montants = [];
            foreach ($recettes as $recette) {
                $date = DateTime::createFromFormat('Y-m', $recette['mois']);
                $mois[] = $date->format('F Y');
                $montants[] = $recette['recette'];
            }
           // print_r($recettes);
            //Inclure la vue
           include __DIR__ . '/../Views/achat/affichageDeHistogramme.php';
        }

        // public function afficherHistogrammeRecettes() {
        //     // Récupérer les données des recettes
        //     $donneesRecettes = $this->model->getRecettes5DerniersMois();
            
        //     // Préparer les données pour l'histogramme
        //     $mois = [];
        //     $montants = [];
            
        //     foreach ($donneesRecettes as $ligne) {
        //         $mois[] = $this->traduireMois($ligne['mois']);
        //         $montants[] = $ligne['recette'];
        //     }
            
        //     // Inverser l'ordre pour avoir du plus récent au plus ancien
        //     $mois = array_reverse($mois);
        //     $montants = array_reverse($montants);
            
        //     // Créer l'histogramme
        //     $histogramme = "<div style='width: 100%; max-width: 600px; margin: 0 auto;'>";
        //     $histogramme .= "<h3 style='text-align: center;'>Recettes des 5 derniers mois</h3>";
        //     $histogramme .= "<div style='display: flex; height: 300px; align-items: flex-end; justify-content: space-around; border-left: 1px solid #000; border-bottom: 1px solid #000; padding: 10px;'>";
            
        //     $maxMontant = max($montants);
        //     foreach ($montants as $index => $montant) {
        //         $hauteur = ($montant / $maxMontant) * 100;
        //         $histogramme .= "<div style='display: flex; flex-direction: column; align-items: center; width: 15%;'>";
        //         $histogramme .= "<div style='background-color: #4CAF50; width: 80%; height: {$hauteur}%;'></div>";
        //         $histogramme .= "<div style='margin-top: 5px;'>{$mois[$index]}</div>";
        //         $histogramme .= "<div style='font-size: 12px;'>" . number_format($montant, 2) . " €</div>";
        //         $histogramme .= "</div>";
        //     }
            
        //     $histogramme .= "</div>";
        //     $histogramme .= "</div>";
            
        

        //     include __DIR__ . '/../Views/achat/affichageDeHistogramme2.php';

        // }

        // public function traduireMois($moisAnnee) {
        //     $mois = [
        //         '01' => 'Janvier', '02' => 'Février', '03' => 'Mars', 
        //         '04' => 'Avril', '05' => 'Mai', '06' => 'Juin', 
        //         '07' => 'Juillet', '08' => 'Août', '09' => 'Septembre', 
        //         '10' => 'Octobre', '11' => 'Novembre', '12' => 'Décembre'
        //     ];
            
        //     $parts = explode('-', $moisAnnee);
        //     $annee = $parts[0];
        //     $moisNum = $parts[1];
            
        //     return $mois[$moisNum] . ' ' . $annee;
        // }

        public function rechercher() {
            if($_SERVER['REQUEST_METHOD'] === 'POST') {
               $nomClient = $_POST['inputRecherche'];
                $achat = $this->model->rechercher($nomClient);
                include __DIR__ . '/../Views1/achat/read.php';
            
            }
        }

    }


?>