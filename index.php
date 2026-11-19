<?php
require 'config.php';
$avis = $pdo->query("SELECT a.note, a.description, u.prenom FROM avis a
                     JOIN utilisateur u ON u.utilisateur_id = a.utilisateur_id
                     WHERE a.statut = 'valide' ORDER BY a.avis_id DESC LIMIT 6")->fetchAll();
$titre = 'Accueil'; require 'header.php';
?>
<main class="container py-4">
  <section class="mb-5">
    <h1>Traiteur à Bordeaux depuis 25 ans</h1>
    <p class="lead">Julie et José préparent vos repas de fête, vos réceptions et vos repas du quotidien,
      avec des produits frais et un service de livraison.</p>
    <a class="btn btn-primary" href="menus.php">Découvrir nos menus</a>
  </section>

  <section class="mb-5" aria-labelledby="equipe">
    <h2 id="equipe">Notre savoir-faire</h2>
    <div class="row g-3">
      <div class="col-md-4"><div class="card h-100"><div class="card-body">
        <h3 class="h5">25 ans d'expérience</h3><p class="mb-0">Une équipe qui connaît les attentes de chaque événement.</p></div></div></div>
      <div class="col-md-4"><div class="card h-100"><div class="card-body">
        <h3 class="h5">Fait maison</h3><p class="mb-0">Des plats cuisinés à partir de produits de saison.</p></div></div></div>
      <div class="col-md-4"><div class="card h-100"><div class="card-body">
        <h3 class="h5">Tous les régimes</h3><p class="mb-0">Des menus classiques, végétariens et vegan, avec les allergènes indiqués.</p></div></div></div>
    </div>
  </section>

  <section aria-labelledby="titre-avis">
    <h2 id="titre-avis">Avis de nos clients</h2>
    <?php if (!$avis): ?>
      <p>Aucun avis validé pour le moment.</p>
    <?php else: ?>
      <div class="row g-3">
        <?php foreach ($avis as $a): ?>
          <div class="col-md-4"><div class="card h-100"><div class="card-body">
            <p class="mb-1" aria-label="Note : <?= (int)$a['note'] ?> sur 5"><?= str_repeat('★', (int)$a['note']) . str_repeat('☆', 5 - (int)$a['note']) ?></p>
            <p><?= htmlspecialchars($a['description'] ?? '', ENT_QUOTES, 'UTF-8') ?></p>
            <p class="small mb-0">- <?= htmlspecialchars($a['prenom'], ENT_QUOTES, 'UTF-8') ?></p>
          </div></div></div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </section>
</main>
<?php require 'footer.php'; ?>
