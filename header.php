<?php
// En-tete commun : a inclure apres config.php, avec $titre defini
$e = $e ?? fn($v) => htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8');
$connecte = !empty($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="theme-color" content="#7B1E3A">
  <title><?= $e($titre ?? 'Accueil') ?> - Vite &amp; Gourmand</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="style.css" rel="stylesheet">
</head>
<body class="d-flex flex-column min-vh-100">
<a class="visually-hidden-focusable" href="#contenu">Aller au contenu</a>
<header class="vg-header">
  <nav class="navbar navbar-expand-md navbar-dark container" aria-label="Navigation principale">
    <a class="navbar-brand vg-logo" href="index.php">Vite &amp; Gourmand</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav" aria-controls="nav" aria-expanded="false" aria-label="Ouvrir le menu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="nav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="index.php">Accueil</a></li>
        <li class="nav-item"><a class="nav-link" href="menus.php">Menus</a></li>
        <?php if ($connecte): ?>
          <li class="nav-item"><a class="nav-link" href="deconnexion.php">Déconnexion</a></li>
        <?php else: ?>
          <li class="nav-item"><a class="nav-link" href="connexion.php">Connexion</a></li>
          <li class="nav-item"><a class="nav-link" href="inscription.php">Inscription</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </nav>
</header>
<div id="contenu" class="flex-grow-1">
