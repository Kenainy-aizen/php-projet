<?php

    require __DIR__ . '/../pdf/fpdf.php';

    class Achat {
        private $conn;

        public function __construct($db) {
            $this->conn = $db;
        }

        public function create($numAchat,$numMedoc,$nomClient,$nbr,$dateAchat) {
            $query = "INSERT INTO achat (numAchat,numMedoc,nomClient,nbr,dateAchat) VALUES ( :numAchat, :numMedoc, :nomClient, :nbr, :dateAchat)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':numAchat', $numAchat);
            $stmt->bindParam(':numMedoc', $numMedoc);
            $stmt->bindParam(':nomClient', $nomClient);
            $stmt->bindParam(':nbr', $nbr);
            $stmt->bindParam(':dateAchat', $dateAchat);

            return $stmt->execute();
        }

        public function read() {
            $query = "SELECT * FROM achat ORDER BY numAchat ASC";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function update($numAchat,$numMedoc,$nomClient,$nbr,$dateAchat,$numMedoc1,$nbr1) {
            $query = "UPDATE achat SET numMedoc = :numMedoc, nomClient = :nomClient, nbr = :nbr, dateAchat = :dateAchat WHERE numAchat = :numAchat and numMedoc = :numMedoc1 and nbr = :nbr1";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':numMedoc',$numMedoc);
            $stmt->bindParam(':numMedoc1',$numMedoc1);
            $stmt->bindParam(':numAchat',$numAchat);
            $stmt->bindParam(':nomClient',$nomClient);
            $stmt->bindParam(':nbr',$nbr);
            $stmt->bindParam(':nbr1',$nbr1);
            $stmt->bindParam(':dateAchat',$dateAchat);

            return $stmt->execute();
        }

        public function delete($numAchat,$numMedoc,$nbr) {
            $query = "DELETE FROM achat WHERE numAchat = :numAchat and numMedoc = :numMedoc and nbr = :nbr";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':numMedoc',$numMedoc);
            $stmt->bindParam(':numAchat',$numAchat);
            $stmt->bindParam(':nbr',$nbr);
            
            return $stmt->execute();                              
        }

        public function delete1($numAchat) {
            $query = "DELETE FROM achat WHERE numAchat = :numAchat";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':numAchat',$numAchat);
            return $stmt->execute();                              
        }

       // public function delete($id, $numMedoc) {
            // Logique de suppression avec les paramètres supplémentaires
          //  echo "Suppression de l'achat avec ID: $id, Param1: $numMedoc ";
        
            // Exemple : Supprimer l'achat de la base de données
           // $this->achatModel->deleteAchat($id);
        
            // Rediriger ou afficher un message de succès
           // header('Location: index.php?entity=achat&action=read');
            //exit();
       // }

        public function readOne($numAchat) {
            $query = "SELECT * FROM achat WHERE numAchat = :numAchat";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':numAchat',$numAchat);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        
        public function generateNumber() {
            $query = "SELECT numAchat FROM achat ORDER BY numAchat DESC LIMIT 1";
            $stmt = $this->conn->query($query);
            if ($stmt && $stmt->rowCount() > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $lastNumAchat = $row['numAchat'];
                $lastNumber = intval(substr($lastNumAchat,4));
                $newNumber = $lastNumber + 1;
            } else {
                $newNumber = 1;
            }

            $newNumAchat = "ACH-" . str_pad($newNumber, 3, "0", STR_PAD_LEFT);

            return $newNumAchat;
        }

        public function soustraction($numMedoc,$nbr) {
            $query = "SELECT stock FROM medicament WHERE numMedoc = :numMedoc";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':numMedoc',$numMedoc);
            $stmt->execute();
            $lastStock = $stmt->fetch(PDO::FETCH_ASSOC);
            $numberlaststock = $lastStock['stock'];
            $totalStock = $numberlaststock - $nbr;
            var_dump($totalStock);
        
            if($totalStock > 0) {
            $query01 = "UPDATE medicament SET stock = :stock WHERE numMedoc = :numMedoc";
            $stmt1 = $this->conn->prepare($query01);
            $stmt1->bindParam(':stock',$totalStock);
            $stmt1->bindParam(':numMedoc',$numMedoc);
            $stmt1->execute();
            return true;
            
            } else {
                return [
                    'error' => "Achat refuse : numero medicament invalide ou stock insuffisant .",
                    'stock_initial' => $numberlaststock
                ];
            }
        }

        public function modSoustraction($numAchat,$numMedoc,$nbr,$numMedoc1,$nbr1) {
            $query = "SELECT nbr,numMedoc FROM achat WHERE numAchat = :numAchat and numMedoc = :numMedoc and nbr = :nbr";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':numAchat',$numAchat);
            $stmt->bindParam(':numMedoc',$numMedoc1);
            $stmt->bindParam(':nbr',$nbr1);
            $stmt->execute();
            $lastAchat = $stmt->fetch(PDO::FETCH_ASSOC);
            $numberLastNbr = $lastAchat['nbr'];
            $numberLastNumMedoc = $lastAchat['numMedoc'];
            
            if($numberLastNumMedoc !== $numMedoc) {

                $query1 = "SELECT stock FROM medicament WHERE numMedoc = :numMedoc";
                $stmt1 = $this->conn->prepare($query1);
                $stmt1->bindParam(':numMedoc',$numberLastNumMedoc);
                $stmt1->execute();
                $lastStock = $stmt1->fetch(PDO::FETCH_ASSOC);
                $numberLastStock = $lastStock['stock'];
                
                $updateStock = $numberLastNbr + $numberLastStock;
                var_dump($updateStock);
                $query2 = "UPDATE medicament SET stock = :stock WHERE numMedoc = :numMedoc";
                $stmt2 = $this->conn->prepare($query2);
                $stmt2->bindParam(':stock',$updateStock);
                $stmt2->bindParam(':numMedoc',$numberLastNumMedoc);
                $stmt2->execute();
                $this->soustraction($numMedoc,$nbr);
            
            } else { 
                
                $query3 = "SELECT stock FROM medicament WHERE numMedoc = :numMedoc";
                $stmt3 = $this->conn->prepare($query3);
                $stmt3->bindParam(':numMedoc',$numMedoc);
                $stmt3->execute();
                $lastStock1 = $stmt3->fetch(PDO::FETCH_ASSOC);
                $numberLastStock1 = $lastStock1['stock'];
                
                $finStock1 = $numberLastStock1 - $nbr + $numberLastNbr;
                 
                var_dump($finStock1);
                $query4 = "UPDATE medicament SET stock = :stock WHERE numMedoc = :numMedoc";
                $stmt4 = $this->conn->prepare($query4);
                $stmt4->bindParam(':stock',$finStock1);
                $stmt4->bindParam(':numMedoc',$numMedoc);
                $stmt4->execute();

            } 
        }

        public function supSoustraction($numAchat, $numMedoc1, $nbr) {
            $query = "SELECT nbr,numMedoc FROM achat WHERE numAchat = :numAchat and numMedoc = :numMedoc and nbr = :nbr";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':numAchat',$numAchat);
            $stmt->bindParam(':numMedoc',$numMedoc1);
            $stmt->bindParam(':nbr',$nbr);
            $stmt->execute();
            $numLast = $stmt->fetch(PDO::FETCH_ASSOC);
            $numberNumLastNbr = $numLast['nbr'];
            $numMedoc = $numLast['numMedoc'];
            
            $query1 = "SELECT stock FROM medicament WHERE numMedoc = :numMedoc" ;
            $stmt1 = $this->conn->prepare($query1);
            $stmt1->bindParam(':numMedoc',$numMedoc);
            $stmt1->execute();
            $lastStock = $stmt1->fetch(PDO::FETCH_ASSOC);
            $numberLastStock = $lastStock['stock'];

            $finStock = $numberLastStock + $numberNumLastNbr;

            $query2 = "UPDATE medicament SET stock = :stock WHERE numMedoc = :numMedoc";
            $stmt2 = $this->conn->prepare($query2);
            $stmt2->bindParam(':numMedoc',$numMedoc);
            $stmt2->bindParam(':stock',$finStock);
            $stmt2->execute();

        }

        public function getAchatDetails($numAchat) {
            $query = "SELECT m.Design, a.nbr, m.prix_unitaire FROM achat a JOIN medicament m ON a.numMedoc = m.numMedoc WHERE a.numAchat = :numAchat";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam('numAchat',$numAchat);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function finishTransaction() {
            if(isset($_SESSION['numAchat'])) {
                unset($_SESSION['numAchat']);
                echo "Transaction termine. Numero d'achat : " . $_SESSION['numAchat'] ;
            } else {
                echo "Aucun transaction en cours.";
            }
        }

        public function genererPdf($numAchat) {
            
           // require __DIR__ . '/../fpdf.php'; 
           //var_dump("Appel OK : " . $numAchat);

            if (!$numAchat) {
                die("Numéro d'achat manquant.");
            }

            // Récupérer les données de l'achat groupées par numAchat
            $query = "SELECT a.numAchat, a.nomClient, a.dateAchat, m.Design, m.prix_unitaire, a.nbr
                    FROM achat a
                    JOIN medicament m ON a.numMedoc = m.numMedoc
                    WHERE a.numAchat = :numAchat";
            $stmt = $this->conn->prepare($query);
            $stmt->execute(['numAchat' => $numAchat]);
            $achats = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (empty($achats)) {
                die("Achat non trouvé.");
            }

            // Créer une instance de PDF
            $pdf = new FPDF();
            $pdf->AddPage();

            // En-tête du PDF
            $pdf->SetFont('Arial', 'B', 14);
            $pdf->Cell(0, 10, 'Facture', 0, 1, 'C');
            $pdf->Ln(10);

            // Informations de la facture
            $pdf->SetFont('Arial', '', 12);
            $pdf->Cell(0, 10, 'Date : ' . $achats[0]['dateAchat'], 0, 1);
            $pdf->Cell(0, 10, 'Nom du Client : ' . $achats[0]['nomClient'], 0, 1);
            $pdf->Ln(10);

            // En-tête du tableau
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(60, 10, 'Designation', 1, 0, 'C');
            $pdf->Cell(40, 10, 'Prix Unitaire', 1, 0, 'C');
            $pdf->Cell(40, 10, 'Nombre', 1, 0, 'C');
            $pdf->Cell(40, 10, 'Total', 1, 1, 'C');

            // Données du tableau
            $pdf->SetFont('Arial', '', 12);
            $totalGeneral = 0;

            foreach ($achats as $row) {
                $total = $row['prix_unitaire'] * $row['nbr'];
                $totalGeneral += $total;

                $pdf->Cell(60, 10, $row['Design'], 1, 0, 'L');
                $pdf->Cell(40, 10, $row['prix_unitaire'], 1, 0, 'C');
                $pdf->Cell(40, 10, $row['nbr'], 1, 0, 'C');
                $pdf->Cell(40, 10, $total . ' Ar', 1, 1, 'C');
            }

            // Total général
            $pdf->Cell(140, 10, '', 0, 0);
            $pdf->Cell(40, 10, $totalGeneral . ' Ar', 1, 1, 'C');

            // Générer le PDF
            $pdf->Output('I', 'Facture_' . $numAchat . '.pdf');
        }

        function afficherFacture($numAchat) {
            $stmt = $this->conn->prepare("
                SELECT A.numMedoc, M.Design, M.prix_unitaire, A.nbr, (M.prix_unitaire * A.nbr) AS total
                FROM achat A
                JOIN medicament M ON A.numMedoc = M.numMedoc
                WHERE A.numAchat = ?
            ");
            $stmt->execute([$numAchat]);
            $achats = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
            if (empty($achats)) {
               // return "<p>Aucun achat trouvé pour cette facture.</p>";
               //return true;
            }
        
            $facture = "<h2>Facture $numAchat</h2>";
            $facture .= "<table>
                            <tr>
                                <th>Médicament</th>
                                <th>Prix Unitaire</th>
                                <th>Quantité</th>
                                <th>Total</th>
                            </tr>";
        
            $totalGeneral = 0;
            foreach ($achats as $achat) {
                $facture .= "<tr>
                                <td>{$achat['Design']}</td>
                                <td>{$achat['prix_unitaire']} Ar</td>
                                <td>{$achat['nbr']}</td>
                                <td>{$achat['total']} Ar</td>
                             </tr>";
                $totalGeneral += $achat['total'];
            }
        
            $facture .= "<tr>
                            <td colspan='3'><strong>Total Général</strong></td>
                            <td><strong>{$totalGeneral} Ar</strong></td>
                         </tr>";
            $facture .= "</table>";
        
            return $facture;
        }

        public function getRecettes5DerniersMois() {
            $query = "
                SELECT 
                    DATE_FORMAT(dateAchat, '%Y-%m') AS mois, 
                    SUM(M.prix_unitaire * A.nbr) AS recette
                FROM achat A
                JOIN medicament M ON A.numMedoc = M.numMedoc
                WHERE dateAchat >= DATE_SUB(CURDATE(), INTERVAL 5 MONTH)
                GROUP BY DATE_FORMAT(dateAchat, '%Y-%m')
                
            ";
            $stmt = $this->conn->query($query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        
        public function rechercher($nomClient) {
            $query = "SELECT * FROM achat WHERE nomClient LIKE :nomClient ORDER BY numAchat ASC";
            $stmt = $this->conn->prepare($query);
            $keyword = "%$nomClient%";
            $stmt->bindParam(':nomClient',$keyword);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        
    }


?>