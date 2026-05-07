<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Top 5 des médicaments les plus vendus</title>
    <style>
        /* Style personnalisé */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        h2 {
            color: #333;
            text-align: center;
        }
        table {
            width: 50%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        tr:hover {
            background-color: #f5f5f5;
        }
    </style>
</head>
<body>
    <h2>Top 5 des médicaments les plus vendus</h2>
    <table>
        <tr>
            <th>Médicament</th>
            <th>Quantité vendue</th>
        </tr>
        <?php foreach ($top5Medicaments as $medicament): ?>
            <tr>
                <td><?php echo htmlspecialchars($medicament['Design']); ?></td>
                <td><?php echo htmlspecialchars($medicament['total_vendu']); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>