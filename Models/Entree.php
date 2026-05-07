<?php

    class Entree {
        private $conn;

        public function __construct($db) {
            $this->conn = $db;
        }

        public function read() {
            $query = "SELECT * FROM entree";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();

            return $stmt->fetchALL(PDO::FETCH_ASSOC);
        }

        public function create($numEntree,$numMedoc,$stockEntree) {
            $query = "INSERT INTO entree ( numEntree, numMedoc, stockEntree, dateEntree) VALUES ( :numEntree, :numMedoc, :stockEntree, NOW())";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':numEntree',$numEntree);
            $stmt->bindParam(':numMedoc',$numMedoc);
            $stmt->bindParam(':stockEntree',$stockEntree);
          //  $stmt->bindParam(':dateEntree',$dateEntree);

            return $stmt->execute();
        }

        public function update($numEntree, $numMedoc, $stockEntree, $dateEntree) {
            $query ="UPDATE entree SET numMedoc = :numMedoc, stockEntree = :stockEntree, dateEntree = :dateEntree WHERE numEntree = :numEntree";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':numEntree',$numEntree);
            $stmt->bindParam(':numMedoc',$numMedoc);
            $stmt->bindParam(':stockEntree',$stockEntree);
            $stmt->bindParam(':dateEntree',$dateEntree);

            return $stmt->execute();
        }

        public function delete($numEntree) {
            $query = "DELETE FROM entree WHERE numEntree = :numEntree";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':numEntree',$numEntree);
            
            return $stmt->execute();
        }

        public function generateNumber() {
            $query = "SELECT numEntree FROM entree ORDER BY numEntree DESC LIMIT 1";
            $stmt = $this->conn->query($query);
            if ($stmt && $stmt->rowCount() > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $lastNumEntree = $row['numEntree'];
                $lastNumber = intval(substr($lastNumEntree,4));
                $newNumber = $lastNumber + 1;
            } else {
                $newNumber = 1;
            }

            $newNumEntree = "ENT-" . str_pad($newNumber, 3, "0", STR_PAD_LEFT);

            return $newNumEntree;
        } 

        public function addition($numMedoc,$stockEntree) {
            $query = "SELECT stock FROM medicament WHERE numMedoc = :numMedoc";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':numMedoc',$numMedoc);
            $stmt->execute();
            $lastStock = $stmt->fetch(PDO::FETCH_ASSOC);
            $lastNumber = $lastStock['stock'];   
            $stock = $lastNumber + $stockEntree;
            $stockFin = intval($stock);
            $query1 = "UPDATE medicament SET stock = :stock WHERE numMedoc = :numMedoc ";
            $stmt1 = $this->conn->prepare($query1);         
            $stmt1->bindParam(':stock',$stockFin);
            $stmt1->bindParam(':numMedoc',$numMedoc);
           
            return $stmt1->execute();  
        }

        public function modAddition($numMedoc,$stockEntree,$numEntree) {

            $query0 = "SELECT numMedoc FROM entree WHERE numEntree = :numEntree";
            $stmt0 = $this->conn->prepare($query0);
            $stmt0->bindParam(':numEntree',$numEntree);
            $stmt0->execute();
            $lastNumMedoc = $stmt0->fetch(PDO::FETCH_ASSOC);
            $numberLastNumMedoc = $lastNumMedoc['numMedoc'];
            var_dump($numberLastNumMedoc);
            var_dump($numMedoc);

            if($numberLastNumMedoc === $numMedoc) {

            $query = "SELECT stockEntree FROM entree WHERE numMedoc = :numMedoc";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':numMedoc',$numMedoc);
            $stmt->execute();
            $lastStockEntree = $stmt->fetch(PDO::FETCH_ASSOC);
            $numLastStockEntree = $lastStockEntree['stockEntree'];
            $diffMedoc = $stockEntree-$numLastStockEntree;
            var_dump($numLastStockEntree);
            $query1 = "SELECT stock FROM medicament WHERE numMedoc = :numMedoc";
            $stmt1 = $this->conn->prepare($query1);
            $stmt1->bindParam(':numMedoc',$numMedoc);
            $stmt1->execute();
            $lastStock = $stmt1->fetch(PDO::FETCH_ASSOC);
            $numberLastStock = $lastStock['stock'];
            $trueStock = $diffMedoc + $numberLastStock;
            
            $query2 = "UPDATE medicament SET stock = :stock WHERE numMedoc = :numMedoc";
            $stmt2 = $this->conn->prepare($query2);
            $stmt2->bindParam(':numMedoc',$numMedoc);
            $stmt2->bindParam(':stock',$trueStock);
            $stmt2->execute();

            } else {
            $query01 = "SELECT stock FROM medicament WHERE numMedoc = :numMedoc";
            $stmt01 = $this->conn->prepare($query01);
            $stmt01->bindParam(':numMedoc',$numberLastNumMedoc);
            var_dump($numberLastNumMedoc);
            $stmt01->execute();
            $suppStock = $stmt01->fetch(PDO::FETCH_ASSOC);
            $numberSuppStock = $suppStock['stock'];
            var_dump("teste".$numberSuppStock);

            $query02 = "SELECT stockEntree FROM entree WHERE numMedoc = :numMedoc";
            $stmt02 = $this->conn->prepare($query02);
            $stmt02->bindParam(':numMedoc',$numberLastNumMedoc);
            $stmt02->execute();
            $suppStockEntree = $stmt02->fetch(PDO::FETCH_ASSOC);
            $numbersuppStockEntree = $suppStockEntree['stockEntree'];
            var_dump($numbersuppStockEntree);
            $trueSuppStock = $numberSuppStock - $numbersuppStockEntree;

            $query03 = "UPDATE medicament SET stock = :stock WHERE numMedoc = :numMedoc";
            $numberTrue = intval($trueSuppStock);
            $stmt03 = $this->conn->prepare($query03);
            $stmt03->bindParam(':numMedoc',$numberLastNumMedoc);
            $stmt03->bindParam(':stock',$numberTrue);
            var_dump($numMedoc, $numberTrue);
            $stmt03->execute();

            $this->addition($numMedoc,$stockEntree);

            }
         }

         public function suppAddition($numEntree) {
            $query = "SELECT * FROM entree WHERE numEntree = :numEntree";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':numEntree',$numEntree);
            $stmt->execute();
            $lastStockEntree = $stmt->fetch(PDO::FETCH_ASSOC);
            $numberLastStockEntree = $lastStockEntree['stockEntree'];
            $numberLastnumMedoc = $lastStockEntree['numMedoc'];

            $query1 = "SELECT stock FROM medicament WHERE numMedoc = :numMedoc";
            $stmt1 = $this->conn->prepare($query1);
            $stmt1->bindParam(':numMedoc',$numberLastnumMedoc);
            $stmt1->execute();
            $lastStock = $stmt1->fetch(PDO::FETCH_ASSOC);
            $numberLastStock = $lastStock['stock'];

            $finStock = $numberLastStock - $numberLastStockEntree;

            $query3 = "UPDATE medicament SET stock = :stock WHERE numMedoc = :numMedoc";
            $stmt3 = $this->conn->prepare($query3);
            $stmt3->bindParam(':stock',$finStock);
            $stmt3->bindParam(':numMedoc',$numberLastnumMedoc);
            $stmt3->execute();
         }



    } 



?>