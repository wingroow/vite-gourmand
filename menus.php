<?php
require 'config.php';
$themes  = $pdo->query("SELECT theme_id, libelle FROM theme ORDER BY libelle")->fetchAll();
$regimes = $pdo->query("SELECT regime_id, libelle FROM regime ORDER BY libelle")->fetchAll();
?>
<?php $titre = 'Nos menus'; require 'header.php'; ?>
<main class="container py-4">
  <h1>Nos menus</h1>

  <form id="filtres" class="row g-3 mb-4" aria-label="Filtres des menus">
    <div class="col-6 col-md-2"><label for="prix_max" class="form-label">Prix maximum (€)</label>
      <input type="number" min="0" class="form-control" id="prix_max" name="prix_max"></div>
    <div class="col-6 col-md-2"><label for="prix_min" class="form-label">Prix minimum (€)</label>
      <input type="number" min="0" class="form-control" id="prix_min" name="prix_min"></div>
    <div class="col-6 col-md-3"><label for="theme" class="form-label">Thème</label>
      <select class="form-select" id="theme" name="theme"><option value="">Tous</option>
        <?php foreach ($themes as $t): ?><option value="<?= (int)$t['theme_id'] ?>"><?= htmlspecialchars($t['libelle']) ?></option><?php endforeach; ?>
      </select></div>
    <div class="col-6 col-md-3"><label for="regime" class="form-label">Régime</label>
      <select class="form-select" id="regime" name="regime"><option value="">Tous</option>
        <?php foreach ($regimes as $r): ?><option value="<?= (int)$r['regime_id'] ?>"><?= htmlspecialchars($r['libelle']) ?></option><?php endforeach; ?>
      </select></div>
    <div class="col-12 col-md-2"><label for="personnes" class="form-label">Personnes (min.)</label>
      <input type="number" min="1" class="form-control" id="personnes" name="personnes"></div>
  </form>

  <div id="resultats" class="row g-3" aria-live="polite"></div>
</main>

<script>
// Recharge la liste des menus sans recharger la page
const form = document.getElementById('filtres');
const zone = document.getElementById('resultats');

function echapper(t) { const d = document.createElement('div'); d.textContent = t; return d.innerHTML; }

async function chargerMenus() {
  const params = new URLSearchParams(new FormData(form));
  const reponse = await fetch('api_menus.php?' + params.toString());
  const menus = await reponse.json();
  if (menus.length === 0) { zone.innerHTML = '<p>Aucun menu ne correspond à votre recherche.</p>'; return; }
  zone.innerHTML = menus.map(m => `
    <div class="col-md-6 col-lg-4">
      <article class="card h-100"><div class="card-body">
        <h2 class="h5 card-title">${echapper(m.titre)}</h2>
        <p class="card-text">${echapper(m.description || '')}</p>
        <p class="mb-1">À partir de ${m.nombre_personne_minimum} personnes : <strong>${Number(m.prix_minimum).toFixed(2)} €</strong></p>
        <a class="btn btn-primary" href="menu.php?id=${m.menu_id}">Voir le détail</a>
      </div></article>
    </div>`).join('');
}

form.addEventListener('input', chargerMenus);
chargerMenus();
</script>
<?php require 'footer.php'; ?>
