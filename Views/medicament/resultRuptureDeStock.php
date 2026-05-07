<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ruptures de Stock — G-Pharm</title>
  <link rel="stylesheet" href="<?= BASE_URL ?>/lib/sweetalert2/sweetalert2.min.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/Views1/shared/global.css">
  <style>
    .rupture-icon { font-size: 18px; margin-right: 6px; }
    .rupture-count {
      display: inline-flex; align-items: center; justify-content: center;
      min-width: 24px; height: 22px; padding: 0 8px;
      border-radius: var(--r-full);
      background: var(--danger-light); color: var(--danger);
      font-size: 11px; font-weight: 700;
      border: 1px solid rgba(229,62,62,.25);
    }
    .alert-banner {
      background: var(--warning-light);
      border: 1px solid rgba(221,107,32,.3);
      border-left: 4px solid var(--warning);
      border-radius: var(--r-sm);
      padding: 14px 18px;
      display: flex; align-items: center; gap: 12px;
      margin-bottom: 20px;
      font-size: 13.5px; color: #9c4221;
    }
  </style>
</head>
<body>

<?php
$activeNav = "rupture";
include __DIR__ . "/../../Views1/shared/sidebar.php";
?>

<div class="main">

  <!-- TOPBAR -->
  <div class="topbar">
    <div class="topbar-left">
      <span class="page-title">⚠️ Ruptures de Stock</span>
      <span class="rupture-count"><?= count($result) ?></span>
    </div>
    <div class="topbar-right">
      <a href="index.php?entity=medicament" class="btn btn-outline btn-sm">← Retour Médicaments</a>
      <a href="index.php?entity=entree&action=create" class="btn btn-primary btn-sm">📦 Ajouter du Stock</a>
    </div>
  </div>

  <div class="page-wrap">

    <?php if (!empty($result)): ?>
    <div class="alert-banner">
      <span style="font-size:22px;">🚨</span>
      <div>
        <strong><?= count($result) ?> médicament<?= count($result) > 1
     ? "s"
     : "" ?></strong>
        en rupture ou stock critique (stock &lt; 5 unités).
        Pensez à réapprovisionner rapidement.
      </div>
    </div>
    <?php endif; ?>

    <div class="card">
      <div class="card-hd">
        <span class="card-title">⚠️ Médicaments à Stock Critique</span>
        <form class="search-box" action="index.php?entity=medicament&action=rechercher" method="POST">
          <span class="si">🔍</span>
          <input name="design" type="text" placeholder="Rechercher un médicament...">
          <button class="btn btn-primary btn-sm" type="submit" style="margin-right:-5px;border-radius:0 var(--r-xs) var(--r-xs) 0;align-self:stretch;padding-top:0;padding-bottom:0;">Rechercher</button>
        </form>
      </div>
      <div class="tbl-wrap">
        <table class="dtable">
          <thead>
            <tr>
              <th>Réf.</th>
              <th>Désignation</th>
              <th>Prix Unitaire</th>
              <th>Stock Actuel</th>
              <th>Statut</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($result)): ?>
            <tr>
              <td colspan="6">
                <div class="empty">
                  <div class="empty-ico">✅</div>
                  <div class="empty-txt">Aucune rupture de stock !</div>
                  <div class="empty-sub">Tous les médicaments ont un stock suffisant.</div>
                </div>
              </td>
            </tr>
            <?php else: ?>
            <?php foreach ($result as $med): ?>
            <tr>
              <td style="font-weight:600;color:var(--txt2);"><?= htmlspecialchars(
                  $med["numMedoc"],
              ) ?></td>
              <td style="font-weight:500;"><?= htmlspecialchars(
                  $med["Design"],
              ) ?></td>
              <td><?= number_format(
                  $med["prix_unitaire"],
                  0,
                  ",",
                  " ",
              ) ?> Ar</td>
              <td>
                <?php if ($med["stock"] == 0): ?>
                  <span class="stk stk-critical">🔴 0 unité</span>
                <?php elseif ($med["stock"] < 3): ?>
                  <span class="stk stk-critical">🔴 <?= $med[
                      "stock"
                  ] ?> unité<?= $med["stock"] > 1 ? "s" : "" ?></span>
                <?php else: ?>
                  <span class="stk stk-low">⚠ <?= $med["stock"] ?> unités</span>
                <?php endif; ?>
              </td>
              <td>
                <?php if ($med["stock"] == 0): ?>
                  <span style="color:var(--danger);font-weight:700;font-size:12px;">ÉPUISÉ</span>
                <?php else: ?>
                  <span style="color:var(--warning);font-weight:700;font-size:12px;">CRITIQUE</span>
                <?php endif; ?>
              </td>
              <td>
                <div class="action-group">
                  <a href="index.php?entity=entree" class="btn btn-primary btn-sm">📦 Réapprovisionner</a>
                </div>
              </td>
            </tr>
            <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

  </div>
</div>

</body>
</html>
