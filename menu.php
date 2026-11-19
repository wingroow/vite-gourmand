<?php
// Detail d'un menu
require 'config.php';
$id = (int) ($_GET['id'] ?? 0);

$stmt = $pdo->prepare("SELECT m.*, t.libelle AS theme, r.libelle AS regime
                       FROM menu m JOIN theme t ON t.theme_id = m.theme_id
                       JOIN regime r ON r.regime_id = m.regime_id WHERE m.menu_id = :id");
$stmt->execute([':id' => $id]);
$menu = $stmt->fetch();
if (!$menu) { http_response_code(404); exit('Menu introuvable.'); }

$plats = $pdo->prepare("SELECT p.plat_id, p.titre_plat, p.type_plat,
                               GROUP_CONCAT(a.libelle SEPARATOR ', ') AS allergenes
                        FROM menu_plat mp JOIN plat p ON p.plat_id = mp.plat_id
                        LEFT JOIN plat_allergene pa ON pa.plat_id = p.plat_id
                        LEFT JOIN allergene a ON a.allergene_id = pa.allergene_id
                        WHERE mp.menu_id = :id GROUP BY p.plat_id
                        ORDER BY FIELD(p.type_plat,'entree','plat','dessert')");
$plats->execute([':id' => $id]);
$labels = ['entree' => 'Entrée', 'plat' => 'Plat', 'dessert' => 'Dessert'];
$e = fn($v) => htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= $e($menu['titre']) ?> - Vite &amp; Gourmand</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<main class="container py-4">
  <a href="menus.php">&larr; Retour aux menus</a>
  <h1 class="mt-3"><?= $e($menu['titre']) ?></h1>
  <p><?= $e($menu['description']) ?></p>
  <p>Thème : <strong><?= $e($menu['theme']) ?></strong> | Régime : <strong><?= $e($menu['regime']) ?></strong></p>
  <p>À partir de <?= (int)$menu['nombre_personne_minimum'] ?> personnes :
     <strong><?= number_format($menu['prix_par_personne'] * $menu['nombre_personne_minimum'], 2, ',', ' ') ?> €</strong></p>
  <p>Stock : <?= (int)$menu['quantite_restante'] ?> commande(s) possible(s)</p>

  <div class="alert alert-warning" role="alert">
    <strong>Conditions de ce menu :</strong> <?= $e($menu['conditions_menu']) ?>
  </div>

  <h2 class="h4">Composition</h2>
  <ul>
    <?php foreach ($plats as $p): ?>
      <li><strong><?= $labels[$p['type_plat']] ?></strong> : <?= $e($p['titre_plat']) ?>
        <?php if ($p['allergenes']): ?><em>(allergènes : <?= $e($p['allergenes']) ?>)</em><?php endif; ?></li>
    <?php endforeach; ?>
  </ul>

  <a class="btn btn-primary" href="commande.php?menu_id=<?= (int)$menu['menu_id'] ?>">Commander</a>
</main>
</body>
</html>
