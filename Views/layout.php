<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion de Pharmacie</title>
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/../styles.css">
</head>
<body>
    <!-- Header -->
    <header>
        <nav>
            <ul>
                <li><a href="index.php?entity=medicament">Médicaments</a></li>
                <li><a href="index.php?entity=achat">Achats</a></li>
                <li><a href="index.php?entity=entree">Entree</a></li>
            </ul>
        </nav>
    </header>

    <!-- Main Content -->
    <main>
        <!-- <h1>Bienvenue dans la gestion de pharmacie</h1> -->
        <section id="content">
            <!-- Le contenu dynamique sera inséré ici -->
            <?php echo $content; ?>
        </section>
    </main>

    <!-- Footer -->
    <footer>
        <p>&copy; 2023 Gestion de Pharmacie. Tous droits réservés.</p>
    </footer>
</body>
</html>