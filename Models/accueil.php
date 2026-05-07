
<?php
class Accueil {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;       
    }

    public function read() {
        
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

    public function getRecettes5DerniersMois() {
        $query = "
            SELECT 
                DATE_FORMAT(dateAchat, '%Y-%m') AS mois, 
                SUM(M.prix_unitaire * A.nbr) AS recette
            FROM achat A
            JOIN medicament M ON A.numMedoc = M.numMedoc
            WHERE dateAchat >= DATE_SUB(CURDATE(), INTERVAL 5 MONTH)
            GROUP BY DATE_FORMAT(dateAchat, '%Y-%m')
            ORDER BY mois DESC
        ";
        $stmt = $this->conn->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>