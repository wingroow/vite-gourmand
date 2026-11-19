<?php
// Pied de page commun : horaires lus en base
$horaires = $pdo->query("SELECT jour, heure_ouverture, heure_fermeture FROM horaire ORDER BY horaire_id")->fetchAll();
$e = $e ?? fn($v) => htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8');
?>
</div>
<footer class="vg-footer mt-5 py-4">
  <div class="container d-md-flex justify-content-between">
    <div>
      <h2 class="h6">Horaires</h2>
      <ul class="list-unstyled small mb-3 mb-md-0">
        <?php foreach ($horaires as $h): ?>
          <li><?= $e($h['jour']) ?> :
            <?= $h['heure_ouverture'] === 'Ferme' ? 'fermé' : $e($h['heure_ouverture'] . ' - ' . $h['heure_fermeture']) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
    <div class="small">
      <p class="mb-1">Vite &amp; Gourmand - Traiteur à Bordeaux</p>
      <a href="mentions-legales.php">Mentions légales</a> |
      <a href="cgv.php">Conditions générales de vente</a>
    </div>
  </div>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
