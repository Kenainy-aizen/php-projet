<?php
// Pre-flight: $entree is injected by EntreeController::index()
// Columns: numEntree, numMedoc, stockEntree, dateEntree
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Entrées de Stock — G-Pharm</title>
  <link rel="stylesheet" href="<?= BASE_URL ?>/lib/sweetalert2/sweetalert2.min.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/Views1/shared/global.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/Views1/entree/read.css">
</head>
<body>

<?php
$activeNav = "entree";
include __DIR__ . "/../shared/sidebar.php";
?>

<div class="main">

  <!-- ═══════════════════════════ TOPBAR ═══════════════════════════ -->
  <div class="topbar">
    <div class="topbar-left">
      <span class="page-title">Entrées de Stock</span>
      <span class="count-badge"><?= count($entree) ?></span>
    </div>
    <div class="topbar-right">
      <button class="btn btn-primary" onclick="openModal('add')">
        ➕ Ajouter une Entrée
      </button>
    </div>
  </div>

  <!-- ═══════════════════════════ CONTENT ══════════════════════════ -->
  <div class="page-wrap">
    <div class="card">
      <div class="tbl-wrap scrollable">
        <table class="dtable">
          <thead>
            <tr>
              <th>N° Entrée</th>
              <th>Médicament</th>
              <th>Quantité Entrée</th>
              <th>Date Entrée</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>

          <?php if (empty($entree)): ?>
            <tr>
              <td colspan="5">
                <div class="empty">
                  <div class="empty-ico">📦</div>
                  <div class="empty-txt">Aucune entrée de stock enregistrée</div>
                  <div class="empty-sub">Cliquez sur "Ajouter une Entrée" pour commencer.</div>
                </div>
              </td>
            </tr>

          <?php else: ?>
            <?php foreach ($entree as $e):

                $id = $e["numEntree"];
                $medoc = htmlspecialchars($e["numMedoc"]);
                $qty = (int) $e["stockEntree"];
                $rawDate = $e["dateEntree"] ?? "";
                $dateFmt = $rawDate ? date("d/m/Y", strtotime($rawDate)) : "—";
                ?>
            <tr>
              <td><?= htmlspecialchars($id) ?></td>
              <td><?= $medoc ?></td>
              <td><span class="stk stk-ok qty-entry">+<?= $qty ?></span></td>
              <td><?= $dateFmt ?></td>
              <td>
                <div class="action-group">
                  <button class="btn btn-info btn-sm"
                    onclick="openModal('edit','<?= $id ?>','<?= $medoc ?>',<?= $qty ?>,'<?= $rawDate ?>')">
                    ✏️ Éditer
                  </button>
                  <button class="btn btn-danger btn-sm btnSupprimer"
                    data-id="<?= $id ?>">
                    🗑 Supprimer
                  </button>
                </div>
              </td>
            </tr>
            <?php
            endforeach; ?>

          <?php endif; ?>

          </tbody>
        </table>
      </div><!-- /.tbl-wrap -->
    </div><!-- /.card -->
  </div><!-- /.page-wrap -->

</div><!-- /.main -->

<!-- ══════════════════════════════════════════════════════════════════
     MODAL — Ajouter une Entrée
══════════════════════════════════════════════════════════════════ -->
<div id="modalAdd" class="modal-bg">
  <div class="modal-box">
    <div class="modal-hd">
      <span class="modal-hd-title">📦 Ajouter une Entrée</span>
      <button class="modal-x" type="button" onclick="closeModal('modalAdd')">&times;</button>
    </div>
    <form id="formAdd" action="index.php?entity=entree&action=create" method="POST">
      <div class="modal-bd">

        <div class="form-group">
          <label class="form-label req" for="add-numMedoc">N° Médicament</label>
          <input class="form-ctrl" type="text" id="add-numMedoc"
            name="numMedoc" required placeholder="ex: MED-001">
        </div>

        <div class="form-group">
          <label class="form-label req" for="add-stockEntree">Quantité entrée</label>
          <input class="form-ctrl" type="number" id="add-stockEntree"
            name="stockEntree" required min="1" placeholder="0">
        </div>

      </div><!-- /.modal-bd -->
      <div class="modal-ft">
        <button type="button" class="btn btn-outline"
          onclick="closeModal('modalAdd')">Annuler</button>
        <button type="submit" class="btn btn-primary">Enregistrer</button>
      </div>
    </form>
  </div><!-- /.modal-box -->
</div>

<!-- ══════════════════════════════════════════════════════════════════
     MODAL — Modifier l'Entrée
══════════════════════════════════════════════════════════════════ -->
<div id="modalEdit" class="modal-bg">
  <div class="modal-box">
    <div class="modal-hd">
      <span class="modal-hd-title">✏️ Modifier l'Entrée</span>
      <button class="modal-x" type="button" onclick="closeModal('modalEdit')">&times;</button>
    </div>
    <form id="formEdit" method="POST">
      <div class="modal-bd">

        <div class="form-group">
          <label class="form-label req" for="editNumMedoc">N° Médicament</label>
          <input class="form-ctrl" type="text"
            id="editNumMedoc" name="numMedoc">
        </div>

        <div class="form-group">
          <label class="form-label req" for="editStockEntree">Quantité entrée</label>
          <input class="form-ctrl" type="number"
            id="editStockEntree" name="stockEntree">
        </div>

        <div class="form-group">
          <label class="form-label" for="editDateEntree">Date d'entrée</label>
          <input class="form-ctrl" type="date"
            id="editDateEntree" name="dateEntree">
        </div>

      </div><!-- /.modal-bd -->
      <div class="modal-ft">
        <button type="button" class="btn btn-outline"
          onclick="closeModal('modalEdit')">Annuler</button>
        <button type="submit" class="btn btn-primary">Enregistrer</button>
      </div>
    </form>
  </div><!-- /.modal-box -->
</div>

<script src="<?= BASE_URL ?>/lib/sweetalert2/sweetalert2.all.min.js"></script>
<script>
/* ── Modal helpers ─────────────────────────────── */
function openModal(type, numEntree, numMedoc, stockEntree, dateEntree) {
  if (type === 'add') {
    document.getElementById('modalAdd').classList.add('open');
  } else {
    document.getElementById('editNumMedoc').value    = numMedoc    || '';
    document.getElementById('editStockEntree').value = stockEntree || '';
    document.getElementById('editDateEntree').value  = dateEntree  || '';
    document.getElementById('formEdit').action =
      'index.php?entity=entree&action=update&id=' + numEntree;
    document.getElementById('modalEdit').classList.add('open');
  }
}

function closeModal(id) {
  document.getElementById(id).classList.remove('open');
}

/* Close on backdrop click */
document.querySelectorAll('.modal-bg').forEach(function (bg) {
  bg.addEventListener('click', function (e) {
    if (e.target === bg) bg.classList.remove('open');
  });
});

/* ── Delete confirmation ───────────────────────── */
document.querySelectorAll('.btnSupprimer').forEach(function (btn) {
  btn.addEventListener('click', function () {
    var id = this.dataset.id;
    Swal.fire({
      title: 'Supprimer cette entrée ?',
      text: 'Cette action va restaurer le stock correspondant.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#e53e3e',
      cancelButtonText: 'Annuler',
      confirmButtonText: 'Oui, supprimer'
    }).then(function (r) {
      if (r.isConfirmed) {
        fetch('index.php?entity=entree&action=delete&id=' + id)
          .then(function (res) { return res.json(); })
          .then(function (data) {
            if (data.success) {
              Swal.fire({
                icon: 'success',
                title: 'Supprimé',
                text: 'Entrée supprimée et stock restauré.',
                timer: 1500,
                showConfirmButton: false
              }).then(function () { location.reload(); });
            } else {
              Swal.fire('Erreur', 'Impossible de supprimer cette entrée.', 'error');
            }
          });
      }
    });
  });
});
</script>

</body>
</html>
