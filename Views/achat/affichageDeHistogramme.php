<?php
// $mois    : array of month labels (strings, e.g. "January 2025")
// $montants: array of revenue values (floats)
$chartLabelsJson = json_encode($mois, JSON_UNESCAPED_UNICODE);
$chartDataJson = json_encode(array_map("floatval", $montants));
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Recettes — G-Pharm</title>
  <link rel="stylesheet" href="<?= BASE_URL ?>/Views1/shared/global.css">
  <style>
    .recette-total {
      display: inline-flex; align-items: center; gap: 8px;
      padding: 6px 14px; border-radius: var(--r-full);
      background: var(--primary-light); color: var(--primary-dark);
      font-size: 13px; font-weight: 700;
      border: 1px solid rgba(39,174,96,.25);
    }
    .month-chip {
      display: inline-flex; align-items: center; gap: 4px;
      padding: 3px 9px; border-radius: var(--r-full);
      background: var(--surface2); color: var(--txt2);
      font-size: 12px; font-weight: 500;
      border: 1px solid var(--border);
    }
  </style>
</head>
<body>

<?php
$activeNav = "recettes";
include __DIR__ . "/../../Views1/shared/sidebar.php";
?>

<div class="main">

  <!-- TOPBAR -->
  <div class="topbar">
    <div class="topbar-left">
      <span class="page-title">📊 Recettes</span>
      <?php if (!empty($mois)): ?>
        <span class="text-muted text-sm"><?= count(
            $mois,
        ) ?> mois affichés</span>
      <?php endif; ?>
    </div>
    <div class="topbar-right">
      <a href="index.php?entity=achat" class="btn btn-outline btn-sm">← Retour Ventes</a>
      <a href="index.php?entity=acceuil" class="btn btn-primary btn-sm">🏠 Tableau de Bord</a>
    </div>
  </div>

  <div class="page-wrap">

    <?php if (!empty($montants)): ?>
    <!-- Summary stats -->
    <div class="stats-row" style="margin-bottom:22px;">
      <div class="stat-card c-green">
        <div class="stat-icon c-green">💰</div>
        <div class="stat-body">
          <div class="stat-val"><?= number_format(
              array_sum($montants),
              0,
              ",",
              " ",
          ) ?> Ar</div>
          <div class="stat-lbl">Recette Totale (période)</div>
        </div>
      </div>
      <div class="stat-card c-blue">
        <div class="stat-icon c-blue">📅</div>
        <div class="stat-body">
          <div class="stat-val"><?= number_format(
              array_sum($montants) / max(count($montants), 1),
              0,
              ",",
              " ",
          ) ?> Ar</div>
          <div class="stat-lbl">Moyenne Mensuelle</div>
        </div>
      </div>
      <div class="stat-card c-orange">
        <div class="stat-icon c-orange">📈</div>
        <div class="stat-body">
          <div class="stat-val"><?= number_format(
              max($montants),
              0,
              ",",
              " ",
          ) ?> Ar</div>
          <div class="stat-lbl">Meilleur Mois</div>
        </div>
      </div>
    </div>
    <?php endif; ?>

    <!-- Chart -->
    <div class="card mb-6">
      <div class="card-hd">
        <span class="card-title">📊 Recettes des 5 Derniers Mois</span>
        <?php if (!empty($mois)): ?>
          <div class="flex gap-2" style="flex-wrap:wrap;">
            <?php foreach ($mois as $m): ?>
              <span class="month-chip"><?= htmlspecialchars($m) ?></span>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>

      <?php if (empty($montants)): ?>
        <div class="empty" style="padding:52px 0;">
          <div class="empty-ico">📊</div>
          <div class="empty-txt">Aucune donnée de recettes disponible</div>
          <div class="empty-sub">Enregistrez des ventes pour voir les statistiques ici.</div>
        </div>
      <?php else: ?>
        <div class="chart-wrap" style="padding:22px 24px; height:380px;">
          <canvas id="recettesChart"></canvas>
        </div>
      <?php endif; ?>
    </div>

    <!-- Data table -->
    <?php if (!empty($mois)): ?>
    <div class="card">
      <div class="card-hd">
        <span class="card-title">📋 Détail par Mois</span>
      </div>
      <div class="tbl-wrap">
        <table class="dtable">
          <thead>
            <tr>
              <th>#</th>
              <th>Mois</th>
              <th>Recette</th>
              <th>% du Total</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $total = array_sum($montants);
            foreach ($mois as $i => $m):

                $montant = $montants[$i] ?? 0;
                $pct = $total > 0 ? round(($montant / $total) * 100, 1) : 0;
                ?>
            <tr>
              <td class="text-muted"><?= $i + 1 ?></td>
              <td style="font-weight:500;"><?= htmlspecialchars($m) ?></td>
              <td style="font-weight:700;color:var(--primary);"><?= number_format(
                  $montant,
                  0,
                  ",",
                  " ",
              ) ?> Ar</td>
              <td>
                <div style="display:flex;align-items:center;gap:10px;">
                  <div style="flex:1;height:8px;background:var(--border);border-radius:4px;overflow:hidden;">
                    <div style="width:<?= $pct ?>%;height:100%;background:var(--primary);border-radius:4px;"></div>
                  </div>
                  <span style="font-size:12px;color:var(--txt-muted);min-width:38px;"><?= $pct ?>%</span>
                </div>
              </td>
            </tr>
            <?php
            endforeach;
            ?>
          </tbody>
        </table>
      </div>
    </div>
    <?php endif; ?>

  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
(function() {
  var ctx = document.getElementById('recettesChart');
  if (!ctx) return;

  var labels = <?= $chartLabelsJson ?>;
  var data   = <?= $chartDataJson ?>;

  var colors = [
    'rgba(39,174,96,.75)', 'rgba(43,108,176,.75)',
    'rgba(221,107,32,.75)','rgba(107,70,193,.75)',
    'rgba(229,62,62,.75)'
  ];

  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: labels,
      datasets: [{
        label: 'Recettes (Ar)',
        data: data,
        backgroundColor: colors,
        borderColor: colors.map(c => c.replace('.75)', '1)')),
        borderWidth: 2,
        borderRadius: 8,
        borderSkipped: false
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: { display: false },
        tooltip: {
          callbacks: {
            label: function(c) {
              return ' ' + c.parsed.y.toLocaleString('fr-MG') + ' Ar';
            }
          }
        }
      },
      scales: {
        y: {
          beginAtZero: true,
          grid: { color: 'rgba(0,0,0,.05)' },
          ticks: {
            callback: function(v) {
              if(v >= 1000000) return (v/1000000).toFixed(1) + 'M Ar';
              if(v >= 1000)    return (v/1000).toFixed(0) + 'k Ar';
              return v + ' Ar';
            }
          }
        },
        x: { grid: { display: false } }
      }
    }
  });
})();
</script>

</body>
</html>
