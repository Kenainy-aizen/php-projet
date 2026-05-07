<?php
$groupes = [];
foreach ($achat as $ligne) {
    $groupes[$ligne["numAchat"]][] = $ligne;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ventes &amp; Achats — G-Pharm</title>
  <link rel="stylesheet" href="<?= BASE_URL ?>/lib/sweetalert2/sweetalert2.min.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/Views1/shared/global.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/Views1/achat/read.css">
</head>
<body>

<?php
$activeNav = "achat";
include __DIR__ . "/../shared/sidebar.php";
?>

<div class="main">

  <!-- ── TOPBAR ── -->
  <div class="topbar">
    <div class="topbar-left">
      <span class="page-title">Ventes &amp; Achats</span>
      <span class="count-badge"><?= count($groupes) ?></span>
      <span class="count-label">facture<?= count($groupes) !== 1
          ? "s"
          : "" ?></span>
    </div>
    <div class="topbar-right">
      <form class="search-box" action="index.php?entity=achat&action=rechercher" method="POST">
        <span class="si">🔍</span>
        <input name="inputRecherche" type="text" placeholder="Rechercher une vente...">
        <button class="btn btn-primary btn-sm" type="submit">Rechercher</button>
      </form>
      <a href="index.php?entity=achat&action=create" class="btn btn-primary">➕ Nouvelle Vente</a>
    </div>
  </div>

  <!-- ── PAGE CONTENT ── -->
  <div class="page-wrap">
    <div class="card">
      <div class="tbl-wrap scrollable">
        <table class="dtable">
          <thead>
            <tr>
              <th>N° Facture</th>
              <th>Médicament</th>
              <th>Client</th>
              <th>Qté</th>
              <th>Date</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>

          <?php if (empty($groupes)): ?>
            <tr>
              <td colspan="6">
                <div class="empty">
                  <div class="empty-ico">🛒</div>
                  <div class="empty-txt">Aucune vente enregistrée</div>
                  <div class="empty-sub">Créez une nouvelle vente ou ajustez votre recherche.</div>
                </div>
              </td>
            </tr>

          <?php else: ?>
            <?php $groupIndex = 0; ?>
            <?php foreach ($groupes as $numAchat => $lignes): ?>
              <?php
              $rowspan = count($lignes);
              $firstLine = $lignes[0];
              $rowClass = $groupIndex % 2 === 0 ? "group-even" : "group-odd";
              ?>
              <?php foreach ($lignes as $i => $ligne): ?>
                <tr class="<?= $rowClass ?>">

                  <?php if ($i === 0): ?>
                    <td rowspan="<?= $rowspan ?>" class="num-facture">
                      <?= htmlspecialchars($numAchat) ?>
                    </td>
                  <?php endif; ?>

                  <td><?= htmlspecialchars($ligne["numMedoc"]) ?></td>

                  <?php if ($i === 0): ?>
                    <td rowspan="<?= $rowspan ?>">
                      <?= htmlspecialchars($firstLine["nomClient"]) ?>
                    </td>
                  <?php endif; ?>

                  <td><?= htmlspecialchars($ligne["nbr"]) ?></td>

                  <?php if ($i === 0): ?>
                    <td rowspan="<?= $rowspan ?>">
                      <?= date("d/m/Y", strtotime($firstLine["dateAchat"])) ?>
                    </td>
                    <td rowspan="<?= $rowspan ?>">
                      <div class="action-group">
                        <button class="btn btn-info btn-sm pdf"
                          data-id2="<?= htmlspecialchars($numAchat) ?>">
                          📄 PDF
                        </button>
                        <button class="btn btn-danger btn-sm Delete"
                          data-id="<?= htmlspecialchars($numAchat) ?>"
                          data-param1="<?= htmlspecialchars(
                              $firstLine["numMedoc"],
                          ) ?>"
                          data-param2="<?= htmlspecialchars(
                              $firstLine["nbr"],
                          ) ?>">
                          🗑 Supprimer
                        </button>
                      </div>
                    </td>
                  <?php endif; ?>

                </tr>
              <?php endforeach; ?>
              <?php $groupIndex++; ?>
            <?php endforeach; ?>
          <?php endif; ?>

          </tbody>
        </table>
      </div>
    </div>
  </div>

</div><!-- /.main -->

<script src="<?= BASE_URL ?>/lib/sweetalert2/sweetalert2.all.min.js"></script>
<script>
  /* ── PDF handler ── */
  document.querySelectorAll('.pdf').forEach(btn => {
    btn.addEventListener('click', function () {
      const id = this.getAttribute('data-id2');
      window.open('index.php?entity=achat&action=CreatePdf&id=' + id, '_blank');
    });
  });

  /* ── Delete handler ── */
  document.querySelectorAll('.Delete').forEach(btn => {
    btn.addEventListener('click', function () {
      const id     = this.getAttribute('data-id');
      const param1 = this.getAttribute('data-param1');
      const param2 = this.getAttribute('data-param2');

      Swal.fire({
        title: 'Êtes-vous sûr ?',
        text: 'La facture n° ' + id + ' sera définitivement supprimée.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e53e3e',
        cancelButtonColor: '#718096',
        confirmButtonText: 'Oui, supprimer !',
        cancelButtonText: 'Annuler'
      }).then(result => {
        if (result.isConfirmed) {
          fetch(
            'index.php?entity=achat&action=delete&id=' + id +
            '&param1=' + param1 + '&param2=' + param2,
            { method: 'GET' }
          )
          .then(r => r.json())
          .then(data => {
            if (data.success) {
              location.reload();
            } else {
              Swal.fire('Erreur', 'Une erreur est survenue lors de la suppression.', 'error');
            }
          })
          .catch(() => {
            Swal.fire('Erreur', 'Une erreur réseau est survenue.', 'error');
          });
        }
      });
    });
  });

  /* ── Close .modal-bg on backdrop click ── */
  document.querySelectorAll('.modal-bg').forEach(bg => {
    bg.addEventListener('click', function (e) {
      if (e.target === this) this.classList.remove('open');
    });
  });
</script>

</body>
</html>
