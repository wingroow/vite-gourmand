# Vite & Gourmand

Application web de présentation de menus pour un traiteur bordelais (ECF Studi - Développeur Web et Web Mobile).

## Technologies
- Front : HTML5, Bootstrap 5, JavaScript (fetch)
- Back : PHP 8 avec PDO (requêtes préparées)
- Base relationnelle : MySQL / MariaDB

## Fonctionnalités réalisées
- Page d'accueil (présentation, savoir-faire, avis validés)
- Liste des menus avec filtres dynamiques sans rechargement (prix max, prix min, thème, régime, nombre de personnes)
- Détail d'un menu (plats, allergènes, conditions, stock)
- Inscription (mot de passe fort, hachage `password_hash`), connexion, déconnexion
- En-tête de navigation, pied de page avec horaires lus en base, mentions légales, CGV

## Non réalisé à ce jour
- Commande, espace utilisateur, espace employé, espace administrateur
- Statistiques en base NoSQL (MongoDB), page de contact, mot de passe oublié, envoi de mails

## Installation en local
1. Installer XAMPP (Apache + PHP + MySQL) et cloner le dépôt dans `htdocs` :
   `git clone https://github.com/wingroow/vite-gourmand.git`
2. Démarrer Apache et MySQL.
3. Dans phpMyAdmin, onglet « Importer », choisir `database.sql`.
4. Compte administrateur : choisir un mot de passe, générer son hachage avec `password_hash()` de PHP, puis remplacer `A_REMPLACER_PAR_HASH` dans la table `utilisateur`.
5. Vérifier les identifiants dans `config.php` (par défaut : utilisateur `root`, mot de passe vide).
6. Ouvrir `http://localhost/vite-gourmand/index.php`.

## Sécurité
- Requêtes préparées PDO (injections SQL)
- Échappement des sorties avec `htmlspecialchars` et côté JavaScript (XSS)
- Jeton CSRF sur les formulaires
- Mots de passe hachés, message de connexion générique, régénération de l'identifiant de session
- Cookie de session `HttpOnly` et `SameSite=Lax`

## Branches Git
- `main` : version stable
- `dev` : développement
- `feature/*` : une branche par fonctionnalité, fusionnée dans `dev` après test
