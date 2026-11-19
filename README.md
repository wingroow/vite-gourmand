# Vite & Gourmand

Application web de présentation et de commande de menus pour un traiteur bordelais (ECF Studi - Développeur Web et Web Mobile).

## Technologies
- Front : HTML5, Bootstrap 5, JavaScript (fetch)
- Back : PHP 8 avec PDO
- Base relationnelle : MySQL / MariaDB
- Base NoSQL : MongoDB (statistiques administrateur)

## Installation en local
1. Installer XAMPP ou Laragon (Apache + PHP + MySQL) et cloner le dépôt dans le dossier `htdocs` (XAMPP) ou `www` (Laragon) :
   `git clone https://github.com/wingroow/vite-gourmand.git`
2. Démarrer Apache et MySQL.
3. Importer la base : dans phpMyAdmin, onglet « Importer », choisir `database.sql`.
4. Générer le mot de passe de l'administrateur : `php create_admin.php`, puis copier le hash dans `database.sql` à la place de `A_REMPLACER_PAR_HASH` avant l'import.
5. Vérifier les identifiants de connexion dans `config.php` (par défaut : utilisateur `root`, mot de passe vide).
6. Ouvrir `http://localhost/vite-gourmand/menus.php`.

## Branches Git
- `main` : version stable
- `dev` : développement
- `feature/*` : une branche par fonctionnalité, fusionnée dans `dev` après test
