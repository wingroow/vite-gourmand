<?php
require 'config.php';

if (empty($_SESSION['csrf'])) { $_SESSION['csrf'] = bin2hex(random_bytes(32)); }
$erreurs = []; $ok = false;
$e = fn($v) => htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!hash_equals($_SESSION['csrf'], $_POST['csrf'] ?? '')) { exit('Requete invalide.'); }

    $nom = trim($_POST['nom'] ?? '');        $prenom = trim($_POST['prenom'] ?? '');
    $tel = trim($_POST['telephone'] ?? '');  $email = trim($_POST['email'] ?? '');
    $adresse = trim($_POST['adresse'] ?? ''); $ville = trim($_POST['ville'] ?? '');
    $mdp = $_POST['password'] ?? '';

    if ($nom === '' || $prenom === '' || $adresse === '' || $ville === '') $erreurs[] = 'Tous les champs sont obligatoires.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $erreurs[] = 'Adresse e-mail invalide.';
    if (!preg_match('/^[0-9 +().-]{8,20}$/', $tel)) $erreurs[] = 'Numéro de téléphone invalide.';
    if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).{10,}$/', $mdp))
        $erreurs[] = 'Le mot de passe doit faire 10 caractères minimum avec une majuscule, une minuscule, un chiffre et un caractère spécial.';

    if (!$erreurs) {
        $deja = $pdo->prepare("SELECT 1 FROM utilisateur WHERE email = :email");
        $deja->execute([':email' => $email]);
        if ($deja->fetch()) { $erreurs[] = 'Un compte existe déjà avec cette adresse e-mail.'; }
        else {
            $ins = $pdo->prepare("INSERT INTO utilisateur (email,password,nom,prenom,telephone,ville,adresse_postale,role_id)
                                  VALUES (:email,:pwd,:nom,:prenom,:tel,:ville,:adresse,
                                  (SELECT role_id FROM role WHERE libelle='utilisateur'))");
            $ins->execute([':email'=>$email, ':pwd'=>password_hash($mdp, PASSWORD_DEFAULT), ':nom'=>$nom,
                           ':prenom'=>$prenom, ':tel'=>$tel, ':ville'=>$ville, ':adresse'=>$adresse]);
            // Mail de bienvenue (fonctionne seulement si le serveur mail est configure)
            @mail($email, 'Bienvenue chez Vite & Gourmand', "Bonjour $prenom, votre compte a bien été créé.");
            $ok = true;
        }
    }
}
?>
<?php $titre = 'Inscription'; require 'header.php'; ?>
<main class="container py-4" style="max-width:600px">
  <h1>Créer un compte</h1>
  <?php if ($ok): ?>
    <div class="alert alert-success" role="status">Compte créé. <a href="connexion.php">Se connecter</a></div>
  <?php else: ?>
    <?php foreach ($erreurs as $err): ?><div class="alert alert-danger" role="alert"><?= $e($err) ?></div><?php endforeach; ?>
    <form method="post">
      <input type="hidden" name="csrf" value="<?= $e($_SESSION['csrf']) ?>">
      <div class="mb-3"><label class="form-label" for="nom">Nom</label><input class="form-control" id="nom" name="nom" autocomplete="family-name" required value="<?= $e($_POST['nom'] ?? '') ?>"></div>
      <div class="mb-3"><label class="form-label" for="prenom">Prénom</label><input class="form-control" id="prenom" name="prenom" autocomplete="given-name" required value="<?= $e($_POST['prenom'] ?? '') ?>"></div>
      <div class="mb-3"><label class="form-label" for="telephone">Téléphone (GSM)</label><input class="form-control" id="telephone" name="telephone" type="tel" autocomplete="tel" required value="<?= $e($_POST['telephone'] ?? '') ?>"></div>
      <div class="mb-3"><label class="form-label" for="email">E-mail</label><input class="form-control" id="email" name="email" type="email" autocomplete="email" required value="<?= $e($_POST['email'] ?? '') ?>"></div>
      <div class="mb-3"><label class="form-label" for="adresse">Adresse postale</label><input class="form-control" id="adresse" name="adresse" autocomplete="street-address" required value="<?= $e($_POST['adresse'] ?? '') ?>"></div>
      <div class="mb-3"><label class="form-label" for="ville">Ville</label><input class="form-control" id="ville" name="ville" autocomplete="address-level2" required value="<?= $e($_POST['ville'] ?? '') ?>"></div>
      <div class="mb-3"><label class="form-label" for="password">Mot de passe</label><input class="form-control" id="password" name="password" type="password" autocomplete="new-password" required aria-describedby="aide">
        <div id="aide" class="form-text">10 caractères minimum, avec majuscule, minuscule, chiffre et caractère spécial.</div></div>
      <button class="btn btn-primary w-100" type="submit">S'inscrire</button>
    </form>
  <?php endif; ?>
</main>
<?php require 'footer.php'; ?>
