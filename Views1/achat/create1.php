<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Nouvelle Vente — G-Pharm</title>
  <link rel="stylesheet" href="<?= BASE_URL ?>/lib/sweetalert2/sweetalert2.min.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/Views1/shared/global.css">

  <style>
    /* ── Breadcrumb ──────────────────────────────── */
    .breadcrumb {
      font-size: 12px;
      color: var(--txt-muted);
      margin-top: 2px;
      display: flex;
      align-items: center;
      gap: 6px;
      flex-wrap: wrap;
    }
    .breadcrumb-sep { opacity: .45; }

    /* ── Invoice number badge ────────────────────── */
    .inv-num-badge {
      display: inline-flex;
      align-items: center;
      gap: 4px;
      padding: 2px 10px;
      background: var(--primary-light);
      color: var(--primary-dark);
      border-radius: var(--r-full);
      font-size: 11.5px;
      font-weight: 700;
      letter-spacing: .3px;
      border: 1px solid rgba(39,174,96,.2);
    }

    /* ── Two-button row ──────────────────────────── */
    .btn-split {
      display: flex;
      gap: 8px;
    }
    .btn-split .btn { flex: 1; }

    /* ── Separator line in form ──────────────────── */
    .form-sep {
      border: none;
      border-top: 1px solid var(--border);
      margin: 18px 0 16px;
    }

    /* ── Invoice preview wrapper ─────────────────── */
    .invoice-preview-wrap {
      padding: 20px 22px 24px;
    }

    .invoice-preview-wrap h2 {
      font-size: 15px;
      font-weight: 700;
      color: var(--primary-dark);
      margin-bottom: 16px;
      padding-bottom: 12px;
      border-bottom: 2px solid var(--primary-light);
      display: flex;
      align-items: center;
      gap: 8px;
    }
    .invoice-preview-wrap h2::before {
      content: '🧾';
      font-size: 18px;
    }

    .invoice-preview-wrap table {
      width: 100%;
      border-collapse: collapse;
      font-size: 13px;
      border-radius: var(--r);
      overflow: hidden;
      box-shadow: var(--s0);
      border: 1px solid var(--border);
    }

    .invoice-preview-wrap table th {
      padding: 10px 15px;
      background: var(--surface2);
      text-align: left;
      font-size: 11px;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: .7px;
      color: var(--txt-muted);
      border-bottom: 2px solid var(--border);
      white-space: nowrap;
    }

    .invoice-preview-wrap table td {
      padding: 10px 15px;
      color: var(--txt);
      border-bottom: 1px solid var(--border);
      vertical-align: middle;
    }

    .invoice-preview-wrap table tr:last-child td {
      border-bottom: none;
      background: linear-gradient(to right, var(--surface2), var(--primary-light) 120%);
      font-weight: 600;
      color: var(--primary-dark);
    }

    .invoice-preview-wrap table tr:not(:last-child):hover td {
      background: var(--surface2);
      transition: background var(--ease);
    }

    /* ── Sticky topbar shadow refinement ─────────── */
    .topbar { box-shadow: 0 2px 8px rgba(0,0,0,.07); }
  </style>
</head>
<body>

<?php
$activeNav = "newachat";
include __DIR__ . "/../shared/sidebar.php";
?>

<main class="main">

  <!-- ════════════════════════════════════════════
       TOPBAR
  ══════════════════════════════════════════════ -->
  <div class="topbar">
    <div class="topbar-left">
      <div>
        <div class="page-title">Nouvelle Vente</div>
        <div class="breadcrumb">
          <span>Ventes</span>
          <span class="breadcrumb-sep">›</span>
          <span>Nouvelle Facture</span>
          <?php if (isset($_SESSION["numAchat"])): ?>
            <span class="breadcrumb-sep">·</span>
            <span class="inv-num-badge">
              🔖 <?= htmlspecialchars($_SESSION["numAchat"]) ?>
            </span>
          <?php endif; ?>
        </div>
      </div>
    </div>
    <div class="topbar-right">
      <a href="index.php?entity=achat" class="btn btn-outline btn-sm">← Retour aux Ventes</a>
    </div>
  </div>

  <!-- ════════════════════════════════════════════
       PAGE WRAP
  ══════════════════════════════════════════════ -->
  <div class="page-wrap">
    <div class="row">

      <!-- ┌─────────────────────────────────────────
           │  LEFT — Form card (35%)
           └───────────────────────────────────────── -->
      <div class="col col-4">
        <div class="card">

          <!-- Card header -->
          <div class="card-hd">
            <div class="card-title">💊 Formulaire d'Achat</div>
          </div>

          <!-- Card body -->
          <div class="card-body p">

            <?php if (isset($_SESSION["error_message"])): ?>
              <div class="alert alert-danger">
                ⚠️ <?= htmlspecialchars($_SESSION["error_message"]) ?>
              </div>
            <?php endif; ?>

            <form id="formAchat" method="post" action="index.php?entity=achat&action=create">

              <!-- Nom du client -->
              <div class="form-group">
                <label class="form-label req" for="nomClient">Nom du Client</label>
                <input
                  class="form-ctrl"
                  type="text"
                  id="nomClient"
                  name="nomClient"
                  required
                  autocomplete="off"
                  placeholder="ex : Jean Dupont">
              </div>

              <!-- Date d'achat -->
              <div class="form-group">
                <label class="form-label req" for="dateAchat">Date d'Achat</label>
                <input
                  class="form-ctrl"
                  type="date"
                  id="dateAchat"
                  name="dateAchat"
                  required
                  value="<?= date("Y-m-d") ?>">
              </div>

              <!-- Numéro médicament -->
              <div class="form-group">
                <label class="form-label" for="numMedoc">Numéro du Médicament</label>
                <input
                  class="form-ctrl"
                  type="text"
                  id="numMedoc"
                  name="numMedoc"
                  autocomplete="off"
                  placeholder="ex : MED-001">
              </div>

              <!-- Quantité -->
              <div class="form-group">
                <label class="form-label" for="nbr">Quantité</label>
                <input
                  class="form-ctrl"
                  type="number"
                  id="nbr"
                  name="nbr"
                  min="1"
                  placeholder="1">
              </div>

              <hr class="form-sep">

              <!-- Valider -->
              <div class="form-group">
                <button type="submit" name="valider" class="btn btn-primary w-full">
                  ✓ Valider l'article
                </button>
              </div>

              <?php if (isset($_SESSION["numAchat"])): ?>
              <!-- Générer PDF (visible seulement si facture en cours) -->
              <div class="form-group">
                <button type="submit" name="genererPdf" class="btn btn-info w-full">
                  📄 Générer PDF
                </button>
              </div>
              <?php endif; ?>

              <!-- Nouvelle Facture + Annuler -->
              <div class="btn-split">
                <button type="submit" name="nouvelleFacture" class="btn btn-outline">
                  🔄 Nouvelle Facture
                </button>
                <button type="submit" name="annuler" class="btn btn-danger">
                  ✗ Annuler
                </button>
              </div>

            </form>
          </div><!-- /card-body -->

        </div><!-- /card -->
      </div><!-- /col-4 -->


      <!-- ┌─────────────────────────────────────────
           │  RIGHT — Invoice preview card (65%)
           └───────────────────────────────────────── -->
      <div class="col col-8">
        <div class="card">

          <!-- Card header -->
          <div class="card-hd">
            <div class="card-title">🧾 Aperçu de la Facture</div>
            <?php if (isset($_SESSION["numAchat"])): ?>
              <span class="inv-num-badge">
                <?= htmlspecialchars($_SESSION["numAchat"]) ?>
              </span>
            <?php endif; ?>
          </div>

          <!-- Card body -->
          <div class="card-body">
            <?php if (isset($_SESSION["numAchat"])): ?>
              <div class="invoice-preview-wrap">
                <?= $this->model->afficherFacture($_SESSION["numAchat"]) ?>
              </div>
            <?php else: ?>
              <div class="empty">
                <div class="empty-ico">🧾</div>
                <div class="empty-txt">Aucune facture en cours</div>
                <div class="empty-sub">Commencez par remplir le formulaire et valider un article</div>
              </div>
            <?php endif; ?>
          </div><!-- /card-body -->

        </div><!-- /card -->
      </div><!-- /col-8 -->

    </div><!-- /row -->
  </div><!-- /page-wrap -->

</main>

<!-- ════════════════════════════════════════════
     SCRIPTS
══════════════════════════════════════════════ -->
<script src="<?= BASE_URL ?>/lib/sweetalert2/sweetalert2.all.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const nomEl  = document.getElementById('nomClient');
  const dateEl = document.getElementById('dateAchat');

  /* Restore sticky fields from sessionStorage */
  const saved_nom  = sessionStorage.getItem('nomClient');
  const saved_date = sessionStorage.getItem('dateAchat');
  if (saved_nom)  nomEl.value  = saved_nom;
  if (saved_date) dateEl.value = saved_date;

  /* Persist on submit — only when validating an item */
  document.getElementById('formAchat').addEventListener('submit', function (e) {
    const btn = e.submitter?.name;
    if (btn === 'valider') {
      sessionStorage.setItem('nomClient', nomEl.value);
      sessionStorage.setItem('dateAchat', dateEl.value);
    } else {
      sessionStorage.removeItem('nomClient');
      sessionStorage.removeItem('dateAchat');
    }
  });
});
</script>

<?php if (isset($_SESSION["error_message"])): ?>
<script>
  Swal.fire({
    icon: 'error',
    title: 'Stock insuffisant',
    html: <?= json_encode(
        htmlspecialchars($_SESSION["error_message"]) .
            "<br>Stock disponible&nbsp;: <strong>" .
            intval($_SESSION["stock_initial"]) .
            " unité(s)</strong>",
    ) ?>,
    confirmButtonColor: '#e53e3e'
  });
</script>
<?php
unset($_SESSION["error_message"]);
unset($_SESSION["stock_initial"]);
endif; ?>

</body>
</html>
