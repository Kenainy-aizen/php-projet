<section id="formulaire-achat">
    <h2>Formulaire d'Achat</h2>
    <form method="post" action="">
        <label for="numMedoc">Numéro du Médicament:</label>
        <input type="text" id="numMedoc" name="numMedoc" required>

        <label for="nomClient">Nom du Client:</label>
        <input type="text" id="nomClient" name="nomClient" required>

        <label for="nbr">Quantité:</label>
        <input type="number" id="nbr" name="nbr" required>

        <label for="dateAchat">Date d'Achat:</label>
        <input type="date" id="dateAchat" name="dateAchat" required>

        <button type="submit" name="valider">Valider l'Achat</button>
        <button type="submit" name="annuler">Annuler l'Achat</button>
        <button type="submit" name="nouvelleFacture">Nouvelle Facture</button>
    </form>
    <?php if (!empty($message)) : ?>
        <p><?php echo $message; ?></p>
    <?php endif; ?>
</section>