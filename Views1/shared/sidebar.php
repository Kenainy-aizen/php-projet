<?php
// $activeNav doit être défini avant d'inclure ce fichier
// Valeurs : 'acceuil' | 'medicament' | 'achat' | 'entree' | 'rupture' | 'recettes'
$_nav = $activeNav ?? '';

// Compte les ruptures de stock pour le badge
$_ruptureCount = 0;
try {
    if (isset($this) && method_exists($this->model, 'ruptureDeStock')) {
        $_ruptureCount = count($this->model->ruptureDeStock());
    }
} catch (Exception $_e) {}
?>
<aside class="sidebar">

  <!-- Logo / Brand -->
  <a href="index.php?entity=acceuil" class="sb-brand">
    <div class="sb-brand-icon">💊</div>
    <div>
      <div class="sb-brand-name">G-Pharm</div>
      <div class="sb-brand-sub">Gestion Pharmacie</div>
    </div>
  </a>

  <!-- Navigation -->
  <nav class="sb-nav">
    <div class="sb-section">Menu Principal</div>

    <a href="index.php?entity=acceuil" class="sb-link <?= $_nav==='acceuil' ? 'active' : '' ?>">
      <span class="sb-icon">🏠</span>
      <span>Tableau de Bord</span>
    </a>

    <a href="index.php?entity=medicament" class="sb-link <?= $_nav==='medicament' ? 'active' : '' ?>">
      <span class="sb-icon">💊</span>
      <span>Médicaments</span>
    </a>

    <a href="index.php?entity=achat" class="sb-link <?= $_nav==='achat' ? 'active' : '' ?>">
      <span class="sb-icon">🛒</span>
      <span>Ventes / Achats</span>
    </a>

    <a href="index.php?entity=entree" class="sb-link <?= $_nav==='entree' ? 'active' : '' ?>">
      <span class="sb-icon">📦</span>
      <span>Entrées Stock</span>
    </a>

    <div class="sb-section" style="margin-top:8px;">Rapports</div>

    <a href="index.php?entity=achat&action=create" class="sb-link <?= $_nav==='newachat' ? 'active' : '' ?>">
      <span class="sb-icon">➕</span>
      <span>Nouvelle Vente</span>
    </a>

    <a href="index.php?entity=achat&action=afficherHistogrammeRecettes" class="sb-link <?= $_nav==='recettes' ? 'active' : '' ?>">
      <span class="sb-icon">📊</span>
      <span>Recettes</span>
    </a>

    <a href="index.php?entity=medicament&action=ruptureDeStock" class="sb-link <?= $_nav==='rupture' ? 'active' : '' ?>">
      <span class="sb-icon">⚠️</span>
      <span>Ruptures de Stock</span>
      <?php if ($_ruptureCount > 0): ?>
        <span class="sb-badge"><?= $_ruptureCount ?></span>
      <?php endif; ?>
    </a>

  </nav>

  <!-- User footer -->
  <div class="sb-footer">
    <div class="sb-user">
      <div class="sb-avatar">A</div>
      <div>
        <div class="sb-uname">Administrateur</div>
        <div class="sb-urole">Pharmacien</div>
      </div>
    </div>
  </div>

</aside>
