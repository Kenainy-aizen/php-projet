<?php

    class Medicament {
        private $conn;

        public function __construct($db) {
            $this->conn = $db;
        }

        // creer un medicament

        public function create($numMedoc, $design, $prix_unitaire) {
            $query = "INSERT INTO medicament (numMedoc, design, prix_unitaire) VALUES (:numMedoc, :design, :prix_unitaire)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':numMedoc', $numMedoc);
            $stmt->bindParam(':design', $design);
            $stmt->bindParam(':prix_unitaire', $prix_unitaire);
           // $stmt->bindParam(':stock', $stock);
            return $stmt->execute();
        }

        //lire tous les medicament

        public function read() {
            $query = "SELECT * FROM medicament ORDER BY numMedoc ASC";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        //Mettre a jour un medicament 

        public function update($numMedoc, $design, $prix_unitaire) {        
            $query = "UPDATE medicament SET design = :design, prix_unitaire = :prix_unitaire WHERE numMedoc = :numMedoc";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':design', $design);
            $stmt->bindParam(':prix_unitaire', $prix_unitaire);
            //$stmt->bindParam(':stock', $stock);
            $stmt->bindParam(':numMedoc', $numMedoc);
            return $stmt->execute();
        }

        public function delete($numMedoc) {
            $query = "DELETE FROM medicament WHERE numMedoc = :numMedoc";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':numMedoc',$numMedoc);
            return $stmt->execute();
        }

        public function readOne($numMedoc) {
            $query = "SELECT * FROM medicament WHERE numMedoc = :numMedoc";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':numMedoc',$numMedoc);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function generateNumber() {
            $query = "SELECT numMedoc FROM medicament ORDER BY numMedoc DESC LIMIT 1";
            $stmt = $this->conn->query($query);
            if ($stmt && $stmt->rowCount() > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $lastNumMedoc = $row['numMedoc'];
                $lastNumber = intval(substr($lastNumMedoc,4));
                $newNumber = $lastNumber + 1;
            } else {
                $newNumber = 1;
            }

            $newNumMedoc = "MED-" . str_pad($newNumber, 3, "0", STR_PAD_LEFT);

            return $newNumMedoc;
        }

        public function recherche($design) {

            $query = "SELECT * FROM medicament WHERE Design LIKE :design ORDER BY numMedoc ASC ";
            $stmt = $this->conn->prepare($query);
            $keyword = "%$design%";
            $stmt->bindParam(':design',$keyword);
            $stmt->execute();
            $resultats = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $resultats;
        }

        public function ruptureDeStock() {

            $query = "SELECT * FROM medicament WHERE stock < 5";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function recetteTotal() {
            
            $query = "SELECT m.prix_unitaire, a.nbr FROM achat a JOIN medicament m ON a.numMedoc = m.numMedoc" ;
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $totalProduit = 0;
            
            //var_dump($result);
            foreach ($result as $row) {
                $totalProduit += $row['nbr'] * $row['prix_unitaire'] ;
            
            }

            return $totalProduit;
        }

        public function getTop5MedicamentsVendus() {
            // Requête SQL pour récupérer les médicaments les plus vendus
            $query = "
                SELECT m.Design, SUM(a.nbr) AS total_vendu
                FROM achat a
                JOIN medicament m ON a.numMedoc = m.numMedoc
                GROUP BY m.numMedoc
                ORDER BY total_vendu DESC
                LIMIT 5
            ";
            $stmt = $this->conn->query($query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function verification($design) {
            $query = "SELECT * FROM medicament WHERE Design = :Design";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':Design',$design);
            $stmt->execute();
            return $stmt->fetchALL(PDO::FETCH_ASSOC);
        }
    }
?>