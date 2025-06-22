<section id="affichage-achats">
    <?php
    if (isset($_SESSION['numAchat'])) {
        echo afficherFacture($pdo, $_SESSION['numAchat']);
    }
    ?>
</section>