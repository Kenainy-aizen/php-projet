<?php
$ruptures = $this->model->ruptureDeStock();
$top5 = $this->model->getTop5MedicamentsVendus();
$recetteTotal = $this->model->recetteTotal();
$nbMedicaments = $this->model->countMedicaments();
$nbFactures = $this->model->countAchatsDistincts();
$nbRuptures = count($ruptures);

$recettes5mois = $this->model->getRecettes5DerniersMois();
$chartLabels = [];
$chartData = [];
foreach (array_reverse($recettes5mois) as $r) {
    $d = DateTime::createFromFormat("Y-m", $r["mois"]);
    $chartLabels[] = $d ? $d->format("M Y") : $r["mois"];
    $chartData[] = (float) $r["recette"];
}
$chartLabelsJson = json_encode($chartLabels, JSON_UNESCAPED_UNICODE);
$chartDataJson = json_encode($chartData);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord – G-Pharm</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/lib/sweetalert2/sweetalert2.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/Views1/shared/global.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/Views1/acceuil/cssAcceuil.css">
</head>
<body>

<?php
$activeNav = "acceuil";
include __DIR__ . "/../shared/sidebar.php";
?>

<div class="main">

    <!-- Topbar -->
    <div class="topbar">
        <div class="topbar-left">
            <span class="page-title">Tableau de Bord</span>
        </div>
        <div class="topbar-right">
            <span class="text-muted text-sm"><?= date("l d F Y") ?></span>
        </div>
    </div>

    <!-- Page content -->
    <div class="page-wrap">

        <!-- ── Stat cards ── -->
        <div class="stats-row">

            <!-- Médicaments -->
            <div class="stat-card c-green">
                <div class="stat-icon c-green">💊</div>
                <div class="stat-body">
                    <div class="stat-val"><?= htmlspecialchars(
                        $nbMedicaments,
                    ) ?></div>
                    <div class="stat-lbl">Médicaments</div>
                </div>
            </div>

            <!-- Total Factures -->
            <div class="stat-card c-blue">
                <div class="stat-icon c-blue">🧾</div>
                <div class="stat-body">
                    <div class="stat-val"><?= htmlspecialchars(
                        $nbFactures,
                    ) ?></div>
                    <div class="stat-lbl">Total Factures</div>
                </div>
            </div>

            <!-- Ruptures -->
            <div class="stat-card c-red">
                <div class="stat-icon c-red">⚠️</div>
                <div class="stat-body">
                    <div class="stat-val"><?= $nbRuptures ?></div>
                    <div class="stat-lbl">
                        Ruptures de Stock
                        <?php if ($nbRuptures > 0): ?>
                            <span style="color:var(--danger);font-weight:700;"> !</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Recette totale -->
            <div class="stat-card c-orange">
                <div class="stat-icon c-orange">💰</div>
                <div class="stat-body">
                    <div class="stat-val" style="font-size:15px;">
                        <?= number_format(
                            (float) $recetteTotal,
                            0,
                            ",",
                            "&nbsp;",
                        ) ?> Ar
                    </div>
                    <div class="stat-lbl">Recette Totale</div>
                </div>
            </div>

        </div><!-- /.stats-row -->

        <!-- ── Two-column row ── -->
        <div class="row" style="margin-bottom:22px;">

            <!-- Chart – 8 cols -->
            <div class="col col-8">
                <div class="card">
                    <div class="card-hd">
                        <span class="card-title">📊 Recettes des 5 derniers mois</span>
                    </div>
                    <div class="chart-wrap" style="padding:18px 20px;">
                        <canvas id="recettesChart" height="110"></canvas>
                    </div>
                </div>
            </div>

            <!-- Top 5 – 4 cols -->
            <div class="col col-4">
                <div class="card" style="height:100%;">
                    <div class="card-hd">
                        <span class="card-title">🏆 Top 5 Médicaments Vendus</span>
                    </div>
                    <div class="tbl-wrap">
                        <?php if (!empty($top5)): ?>
                        <table class="dtable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Médicament</th>
                                    <th>Qté vendue</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($top5 as $i => $med): ?>
                                <tr>
                                    <td class="text-muted font-bold"><?= $i +
                                        1 ?></td>
                                    <td><?= htmlspecialchars(
                                        $med["Design"],
                                    ) ?></td>
                                    <td class="font-bold"><?= htmlspecialchars(
                                        $med["total_vendu"],
                                    ) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <?php else: ?>
                        <div class="empty" style="padding:28px 0;">
                            <div class="empty-ico">📭</div>
                            <div class="empty-txt">Aucune vente enregistrée</div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        </div><!-- /.row -->

        <!-- ── Ruptures de stock ── -->
        <?php if (!empty($ruptures)): ?>
        <div class="card">
            <div class="card-hd flex items-center justify-between">
                <span class="card-title" style="color:var(--danger);">⚠️ Ruptures de Stock (<?= $nbRuptures ?>)</span>
                <a href="index.php?entity=medicament&action=ruptureDeStock" class="btn btn-sm btn-danger">Voir tout</a>
            </div>
            <div style="padding:0 18px 18px;">
                <?php foreach ($ruptures as $item): ?>
                <div class="alert alert-danger flex items-center justify-between gap-3" style="margin-top:10px;">
                    <div class="flex items-center gap-3">
                        <span style="font-size:20px;">💊</span>
                        <div>
                            <div class="font-bold"><?= htmlspecialchars(
                                $item["Design"],
                            ) ?></div>
                            <div class="text-sm text-muted">Réf. <?= htmlspecialchars(
                                $item["numMedoc"],
                            ) ?></div>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="font-bold" style="color:var(--danger);">Stock : <?= htmlspecialchars(
                            $item["stock"],
                        ) ?></div>
                        <div class="text-sm text-muted"><?= number_format(
                            (float) $item["prix_unitaire"],
                            0,
                            ",",
                            "&nbsp;",
                        ) ?> Ar / unité</div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

    </div><!-- /.page-wrap -->
</div><!-- /.main -->

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
(function () {
    var ctx = document.getElementById('recettesChart');
    if (!ctx) return;

    var labels = <?= $chartLabelsJson ?>;
    var data   = <?= $chartDataJson ?>;

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Recettes (Ar)',
                data: data,
                backgroundColor: [
                    'rgba(39,174,96,.75)',
                    'rgba(43,108,176,.75)',
                    'rgba(221,107,32,.75)',
                    'rgba(107,70,193,.75)',
                    'rgba(229,62,62,.75)'
                ],
                borderColor: [
                    'rgba(39,174,96,1)',
                    'rgba(43,108,176,1)',
                    'rgba(221,107,32,1)',
                    'rgba(107,70,193,1)',
                    'rgba(229,62,62,1)'
                ],
                borderWidth: 2,
                borderRadius: 6,
                borderSkipped: false
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function (ctx) {
                            var val = ctx.parsed.y;
                            return ' ' + val.toLocaleString('fr-MG') + ' Ar';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0,0,0,.06)' },
                    ticks: {
                        callback: function (val) {
                            if (val >= 1000000) return (val / 1000000).toFixed(1) + 'M Ar';
                            if (val >= 1000)    return (val / 1000).toFixed(0) + 'k Ar';
                            return val + ' Ar';
                        }
                    }
                },
                x: {
                    grid: { display: false }
                }
            }
        }
    });
})();
</script>

</body>
</html>
