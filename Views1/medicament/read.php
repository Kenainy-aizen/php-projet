<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Médicaments — G-Pharm</title>
  <link rel="stylesheet" href="<?= BASE_URL ?>/lib/sweetalert2/sweetalert2.min.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/Views1/shared/global.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/Views1/medicament/read.css">
</head>
<body>

<?php
$activeNav = "medicament";
include __DIR__ . "/../shared/sidebar.php";
?>

<div class="main">

  <!-- TOPBAR -->
  <div class="topbar">
    <div class="topbar-left">
      <span class="page-title">Médicaments</span>
      <span class="count-badge"><?php echo count($medicament); ?></span>
    </div>
    <div class="topbar-right">
      <form class="search-box" action="index.php?entity=medicament&action=rechercher" method="POST">
        <span class="si">🔍</span>
        <input name="design" type="text" placeholder="Rechercher un médicament...">
        <button class="btn btn-primary btn-sm" type="submit">Rechercher</button>
      </form>
      <a href="index.php?entity=medicament&action=ruptureDeStock" class="btn btn-warning btn-sm">Ruptures de Stock</a>
      <button class="btn btn-primary btn-sm" onclick="openModal('add')">➕ Ajouter</button>
    </div>
  </div>

  <!-- PAGE CONTENT -->
  <div class="page-wrap">
    <div class="card">
      <div class="tbl-wrap scrollable">
        <table class="dtable">
          <thead>
            <tr>
              <th>N°</th>
              <th>Désignation</th>
              <th>Prix Unitaire</th>
              <th>Stock</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>

          <?php if (empty($medicament)): ?>
            <tr>
              <td colspan="5">
                <div class="empty">
                  <div class="empty-ico">💊</div>
                  <div class="empty-txt">Aucun médicament trouvé</div>
                  <div class="empty-sub">Ajoutez un médicament ou modifiez votre recherche.</div>
                </div>
              </td>
            </tr>
          <?php else: ?>
            <?php foreach ($medicament as $med):

                $eId = htmlspecialchars($med["numMedoc"]);
                $eDesign = htmlspecialchars($med["Design"]);
                $jsDesign = htmlspecialchars(addslashes($med["Design"]));
                $jsPrix = (int) $med["prix_unitaire"];
                $prixFmt =
                    number_format($med["prix_unitaire"], 0, ",", " ") . " Ar";
                $stock = (int) $med["stock"];
                ?>
            <tr>
              <td><?php echo $eId; ?></td>
              <td><?php echo $eDesign; ?></td>
              <td><?php echo $prixFmt; ?></td>
              <td>
                <?php if ($stock >= 10): ?>
                  <span class="stk stk-ok"><?php echo $stock; ?></span>
                <?php elseif ($stock >= 5): ?>
                  <span class="stk stk-low">⚠ <?php echo $stock; ?></span>
                <?php else: ?>
                  <span class="stk stk-critical">🔴 <?php echo $stock; ?></span>
                <?php endif; ?>
              </td>
              <td>
                <div class="action-group">
                  <button class="btn btn-info btn-sm"
                    onclick="openEditModal('<?php echo $eId; ?>','<?php echo $jsDesign; ?>',<?php echo $jsPrix; ?>)">
                    ✏️ Éditer
                  </button>
                  <button class="btn btn-danger btn-sm btnSupprimer"
                    data-id="<?php echo $eId; ?>">
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
      </div>
    </div>
  </div>

</div>

<!-- MODAL — Ajouter -->
<div id="modalAdd" class="modal-bg" onclick="if(event.target===this)closeModal('modalAdd')">
  <div class="modal-box">
    <div class="modal-hd">
      <span class="modal-hd-title">➕ Ajouter un médicament</span>
      <button class="modal-x" type="button" onclick="closeModal('modalAdd')">&times;</button>
    </div>
    <form id="formAdd">
      <div class="modal-bd">
        <div class="form-group">
          <label class="form-label req" for="add-design">Désignation</label>
          <input class="form-ctrl" type="text" id="add-design" name="design"
            required placeholder="Nom du médicament">
        </div>
        <div class="form-group">
          <label class="form-label req" for="add-prix">Prix Unitaire (Ar)</label>
          <input class="form-ctrl" type="number" id="add-prix" name="prix_unitaire"
            required min="0" step="1" placeholder="0">
        </div>
      </div>
      <div class="modal-ft">
        <button type="button" class="btn btn-outline" onclick="closeModal('modalAdd')">Annuler</button>
        <button type="submit" class="btn btn-primary">Enregistrer</button>
      </div>
    </form>
  </div>
</div>

<!-- MODAL — Modifier -->
<div id="modalEdit" class="modal-bg" onclick="if(event.target===this)closeModal('modalEdit')">
  <div class="modal-box">
    <div class="modal-hd">
      <span class="modal-hd-title">✏️ Modifier un médicament</span>
      <button class="modal-x" type="button" onclick="closeModal('modalEdit')">&times;</button>
    </div>
    <form id="formEdit" method="POST">
      <div class="modal-bd">
        <div class="form-group">
          <label class="form-label req" for="design1">Désignation</label>
          <input class="form-ctrl" type="text" id="design1" name="design1" required>
        </div>
        <div class="form-group">
          <label class="form-label req" for="prix_unitaire1">Prix Unitaire (Ar)</label>
          <input class="form-ctrl" type="number" id="prix_unitaire1" name="prix_unitaire1"
            required min="0" step="1">
        </div>
      </div>
      <div class="modal-ft">
        <button type="button" class="btn btn-outline" onclick="closeModal('modalEdit')">Annuler</button>
        <button type="submit" class="btn btn-primary">Enregistrer</button>
      </div>
    </form>
  </div>
</div>

<script src="<?= BASE_URL ?>/lib/sweetalert2/sweetalert2.all.min.js"></script>
<script>
  function openModal(id) {
    document.getElementById('modal' + id.charAt(0).toUpperCase() + id.slice(1)).classList.add('open');
  }

  function closeModal(id) {
    document.getElementById(id).classList.remove('open');
  }

  function openEditModal(numMedoc, design, prix) {
    document.getElementById('design1').value = design;
    document.getElementById('prix_unitaire1').value = prix;
    document.getElementById('formEdit').action = 'index.php?entity=medicament&action=update&id=' + numMedoc;
    document.getElementById('modalEdit').classList.add('open');
  }

  document.getElementById('formAdd').addEventListener('submit', async function(e) {
    e.preventDefault();
    const fd = new FormData(this);
    const r = await fetch('index.php?entity=medicament&action=create', {method: 'POST', body: fd});
    const data = await r.json();
    if (data.success) {
      Swal.fire({icon: 'success', title: 'Ajouté !', text: data.message}).then(() => location.reload());
    } else {
      Swal.fire({icon: 'error', title: 'Erreur', text: data.message});
    }
  });

  document.querySelectorAll('.btnSupprimer').forEach(function(btn) {
    btn.addEventListener('click', function() {
      const id = this.dataset.id;
      Swal.fire({
        title: 'Êtes-vous sûr ?',
        text: 'Vous ne pourrez pas annuler cette action !',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e53e3e',
        cancelButtonColor: '#718096',
        confirmButtonText: 'Oui, supprimer !',
        cancelButtonText: 'Annuler'
      }).then(function(result) {
        if (result.isConfirmed) {
          fetch('index.php?entity=medicament&action=delete&id=' + id, {method: 'GET'})
            .then(function(r) { return r.json(); })
            .then(function(data) {
              if (data.success) {
                Swal.fire({icon: 'success', title: 'Supprimé !', text: 'Le médicament a été supprimé.'})
                  .then(function() { location.reload(); });
              } else {
                Swal.fire({icon: 'error', title: 'Erreur', text: 'Impossible de supprimer ce médicament.'});
              }
            })
            .catch(function() {
              Swal.fire({icon: 'error', title: 'Erreur', text: 'Une erreur est survenue lors de la suppression.'});
            });
        }
      });
    });
  });
</script>

</body>
</html>
