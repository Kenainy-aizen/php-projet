<?php
session_start(); // Démarrer la session pour stocker le numéro de facture

// Connexion à la base de données
$host = 'localhost';
$dbname = 'pharmacie';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

// Fonction pour générer un numéro de facture incrémenté
function genererNumAchat() {
    if (!isset($_SESSION['numAchat'])) {
        $_SESSION['numAchat'] = 1; // Initialiser le compteur
    } else {
        $_SESSION['numAchat']++; // Incrémenter le compteur
    }
    return 'ACH-' . str_pad($_SESSION['numAchat'], 3, '0', STR_PAD_LEFT); // Format ACH-001
}

// Fonction pour valider et enregistrer un achat
function enregistrerAchat($pdo, $numAchat, $numMedoc, $nomClient, $nbr, $dateAchat) {
    // Vérifier si le médicament existe et a suffisamment de stock
    $stmt = $pdo->prepare("SELECT prix_unitaire, stock FROM MEDICAMENT WHERE numMedoc = ?");
    $stmt->execute([$numMedoc]);
    $medicament = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$medicament) {
        return "Médicament non trouvé.";
    }

    if ($medicament['stock'] < $nbr) {
        return "Stock insuffisant pour ce médicament.";
    }

    // Enregistrer l'achat dans la base de données
    $stmt = $pdo->prepare("INSERT INTO ACHAT (numAchat, numMedoc, nomClient, nbr, dateAchat) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$numAchat, $numMedoc, $nomClient, $nbr, $dateAchat]);

    // Mettre à jour le stock du médicament
    $newStock = $medicament['stock'] - $nbr;
    $stmt = $pdo->prepare("UPDATE MEDICAMENT SET stock = ? WHERE numMedoc = ?");
    $stmt->execute([$newStock, $numMedoc]);

    return "Achat enregistré avec succès.";
}

// Fonction pour afficher la facture d'un client
function afficherFacture($pdo, $numAchat) {
    $stmt = $pdo->prepare("
        SELECT A.numMedoc, M.Design, M.prix_unitaire, A.nbr, (M.prix_unitaire * A.nbr) AS total
        FROM ACHAT A
        JOIN MEDICAMENT M ON A.numMedoc = M.numMedoc
        WHERE A.numAchat = ?
    ");
    $stmt->execute([$numAchat]);
    $achats = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($achats)) {
        return "<p>Aucun achat trouvé pour cette facture.</p>";
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

// Traitement du formulaire
$message = "";
$numAchat = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['nouvelleFacture'])) {
        // Réinitialiser la facture
        unset($_SESSION['numAchat']);
        $message = "Nouvelle facture prête.";
    } elseif (isset($_POST['annuler'])) {
        // Annuler l'achat (ne rien enregistrer)
        $message = "Achat annulé.";
    } else {
        // Valider et enregistrer l'achat
        if (!isset($_SESSION['numAchat'])) {
            $_SESSION['numAchat'] = genererNumAchat(); // Générer un nouveau numéro de facture
        }
        $numAchat = $_SESSION['numAchat'];
        $numMedoc = $_POST['numMedoc'];
        $nomClient = $_POST['nomClient'];
        $nbr = $_POST['nbr'];
        $dateAchat = $_POST['dateAchat'];

        $message = enregistrerAchat($pdo, $numAchat, $numMedoc, $nomClient, $nbr, $dateAchat);
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Achats</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Gestion des Achats</h1>
        <nav>
            <ul>
                <li><a href="index_principal.php">Retour au Projet Principal</a></li>
            </ul>
        </nav>
    </header>

    <div class="container">
        <!-- Formulaire d'achat à gauche -->
        <?php include 'formulaire_achat.php'; ?>

        <!-- Affichage des achats à droite -->
        <?php include 'affichage_achats.php'; ?>
    </div>

    <footer>
        <p>&copy; 2023 Gestion des Achats</p>
    </footer>
</body>
</html>