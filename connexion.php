<?php
require 'config.php';

if (empty($_SESSION['csrf'])) { $_SESSION['csrf'] = bin2hex(random_bytes(32)); }
$erreur = '';
$e = fn($v) => htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!hash_equals($_SESSION['csrf'], $_POST['csrf'] ?? '')) { exit('Requete invalide.'); }

    $stmt = $pdo->prepare("SELECT u.utilisateur_id, u.password, u.actif, r.libelle AS role
                           FROM utilisateur u JOIN role r ON r.role_id = u.role_id WHERE u.email = :email");
    $stmt->execute([':email' => trim($_POST['email'] ?? '')]);
    $user = $stmt->fetch();

    // Message volontairement generique : ne revele pas si l'e-mail existe
    if ($user && $user['actif'] && password_verify($_POST['password'] ?? '', $user['password'])) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['utilisateur_id'];
        $_SESSION['role'] = $user['role'];
        header('Location: menus.php');
        exit;
    }
    $erreur = 'Identifiants incorrects ou compte désactivé.';
}
?>
<?php $titre = 'Connexion'; require 'header.php'; ?>
<main class="container py-4" style="max-width:500px">
  <h1>Connexion</h1>
  <?php if ($erreur): ?><div class="alert alert-danger" role="alert"><?= $e($erreur) ?></div><?php endif; ?>
  <form method="post">
    <input type="hidden" name="csrf" value="<?= $e($_SESSION['csrf']) ?>">
    <div class="mb-3"><label class="form-label" for="email">E-mail</label><input class="form-control" id="email" name="email" type="email" required></div>
    <div class="mb-3"><label class="form-label" for="password">Mot de passe</label><input class="form-control" id="password" name="password" type="password" required></div>
    <button class="btn btn-primary" type="submit">Se connecter</button>
  </form>
  <p class="mt-3"><a href="inscription.php">Créer un compte</a></p>
</main>
<?php require 'footer.php'; ?>
