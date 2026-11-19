-- Vite & Gourmand - creation de la base (MySQL / MariaDB)
CREATE DATABASE IF NOT EXISTS vite_gourmand CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE vite_gourmand;

CREATE TABLE role (
  role_id INT AUTO_INCREMENT PRIMARY KEY,
  libelle VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE utilisateur (
  utilisateur_id INT AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(100) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  nom VARCHAR(50) NOT NULL,
  prenom VARCHAR(50) NOT NULL,
  telephone VARCHAR(20),
  ville VARCHAR(80),
  pays VARCHAR(50) DEFAULT 'France',
  adresse_postale VARCHAR(150),
  actif BOOLEAN NOT NULL DEFAULT TRUE,
  role_id INT NOT NULL,
  reset_token VARCHAR(100) NULL,
  reset_expire DATETIME NULL,
  FOREIGN KEY (role_id) REFERENCES role(role_id)
);

CREATE TABLE regime (regime_id INT AUTO_INCREMENT PRIMARY KEY, libelle VARCHAR(50) NOT NULL);
CREATE TABLE theme  (theme_id  INT AUTO_INCREMENT PRIMARY KEY, libelle VARCHAR(50) NOT NULL);
CREATE TABLE allergene (allergene_id INT AUTO_INCREMENT PRIMARY KEY, libelle VARCHAR(50) NOT NULL);

CREATE TABLE menu (
  menu_id INT AUTO_INCREMENT PRIMARY KEY,
  titre VARCHAR(100) NOT NULL,
  description TEXT,
  conditions_menu TEXT,
  nombre_personne_minimum INT NOT NULL,
  prix_par_personne DECIMAL(8,2) NOT NULL,   -- prix pour le nombre minimum = minimum x prix_par_personne
  quantite_restante INT NOT NULL DEFAULT 0,
  image VARCHAR(255),
  regime_id INT NOT NULL,
  theme_id INT NOT NULL,
  FOREIGN KEY (regime_id) REFERENCES regime(regime_id),
  FOREIGN KEY (theme_id) REFERENCES theme(theme_id)
);

CREATE TABLE plat (
  plat_id INT AUTO_INCREMENT PRIMARY KEY,
  titre_plat VARCHAR(100) NOT NULL,
  type_plat ENUM('entree','plat','dessert') NOT NULL,
  photo VARCHAR(255)
);

CREATE TABLE menu_plat (
  menu_id INT NOT NULL, plat_id INT NOT NULL,
  PRIMARY KEY (menu_id, plat_id),
  FOREIGN KEY (menu_id) REFERENCES menu(menu_id) ON DELETE CASCADE,
  FOREIGN KEY (plat_id) REFERENCES plat(plat_id) ON DELETE CASCADE
);

CREATE TABLE plat_allergene (
  plat_id INT NOT NULL, allergene_id INT NOT NULL,
  PRIMARY KEY (plat_id, allergene_id),
  FOREIGN KEY (plat_id) REFERENCES plat(plat_id) ON DELETE CASCADE,
  FOREIGN KEY (allergene_id) REFERENCES allergene(allergene_id) ON DELETE CASCADE
);

CREATE TABLE commande (
  numero_commande INT AUTO_INCREMENT PRIMARY KEY,
  date_commande DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  date_prestation DATE NOT NULL,
  heure_livraison VARCHAR(10) NOT NULL,
  adresse_prestation VARCHAR(150) NOT NULL,
  ville_prestation VARCHAR(80) NOT NULL,
  nombre_personne INT NOT NULL,
  prix_menu DECIMAL(8,2) NOT NULL,
  prix_livraison DECIMAL(8,2) NOT NULL DEFAULT 0,
  statut ENUM('en attente','accepte','en preparation','en cours de livraison','livre','en attente du retour de materiel','terminee','annulee') NOT NULL DEFAULT 'en attente',
  pret_materiel BOOLEAN NOT NULL DEFAULT FALSE,
  restitution_materiel BOOLEAN NOT NULL DEFAULT FALSE,
  motif_annulation VARCHAR(255) NULL,
  mode_contact VARCHAR(20) NULL,
  utilisateur_id INT NOT NULL,
  menu_id INT NOT NULL,
  FOREIGN KEY (utilisateur_id) REFERENCES utilisateur(utilisateur_id),
  FOREIGN KEY (menu_id) REFERENCES menu(menu_id)
);

CREATE TABLE commande_suivi (
  suivi_id INT AUTO_INCREMENT PRIMARY KEY,
  numero_commande INT NOT NULL,
  statut VARCHAR(50) NOT NULL,
  date_modification DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (numero_commande) REFERENCES commande(numero_commande) ON DELETE CASCADE
);

CREATE TABLE avis (
  avis_id INT AUTO_INCREMENT PRIMARY KEY,
  note TINYINT NOT NULL CHECK (note BETWEEN 1 AND 5),
  description TEXT,
  statut ENUM('en attente','valide','refuse') NOT NULL DEFAULT 'en attente',
  utilisateur_id INT NOT NULL,
  numero_commande INT NOT NULL UNIQUE,
  FOREIGN KEY (utilisateur_id) REFERENCES utilisateur(utilisateur_id),
  FOREIGN KEY (numero_commande) REFERENCES commande(numero_commande)
);

CREATE TABLE horaire (
  horaire_id INT AUTO_INCREMENT PRIMARY KEY,
  jour VARCHAR(10) NOT NULL,
  heure_ouverture VARCHAR(5) NOT NULL,
  heure_fermeture VARCHAR(5) NOT NULL
);

-- ================= DONNEES =================
INSERT INTO role (libelle) VALUES ('utilisateur'),('employe'),('administrateur');
INSERT INTO regime (libelle) VALUES ('classique'),('vegetarien'),('vegan');
INSERT INTO theme (libelle) VALUES ('Noel'),('Paques'),('classique'),('evenement');
INSERT INTO allergene (libelle) VALUES ('gluten'),('lait'),('oeufs'),('fruits a coque'),('poisson'),('crustaces');

INSERT INTO horaire (jour,heure_ouverture,heure_fermeture) VALUES
('Lundi','09:00','18:00'),('Mardi','09:00','18:00'),('Mercredi','09:00','18:00'),
('Jeudi','09:00','18:00'),('Vendredi','09:00','18:00'),('Samedi','10:00','16:00'),('Dimanche','Ferme','Ferme');

INSERT INTO menu (titre,description,conditions_menu,nombre_personne_minimum,prix_par_personne,quantite_restante,regime_id,theme_id) VALUES
('Menu de Noel','Un repas festif traditionnel.','Commande 7 jours avant la prestation. Conserver au frais (4 degres).',6,35.00,10,1,1),
('Menu de Paques','Un repas de printemps.','Commande 5 jours avant la prestation.',4,28.00,8,1,2),
('Menu Vegetarien','Menu 100% vegetal et de saison.','Commande 3 jours avant la prestation.',4,25.00,12,2,3),
('Menu Evenement','Pour mariages et receptions.','Commande 14 jours avant la prestation.',20,40.00,5,1,4);

INSERT INTO plat (titre_plat,type_plat) VALUES
('Foie gras maison','entree'),('Dinde aux marrons','plat'),('Buche de Noel','dessert'),
('Veloute de potimarron','entree'),('Risotto aux champignons','plat'),('Tarte aux pommes','dessert');
INSERT INTO menu_plat VALUES (1,1),(1,2),(1,3),(3,4),(3,5),(3,6),(2,4),(2,2),(2,6);
INSERT INTO plat_allergene VALUES (1,1),(3,1),(3,2),(3,3),(6,1),(6,3);

-- Compte administrateur : le mot de passe est a generer avec create_admin.php (jamais en clair)
INSERT INTO utilisateur (email,password,nom,prenom,telephone,ville,adresse_postale,role_id)
VALUES ('admin@vitegourmand.fr','A_REMPLACER_PAR_HASH','Admin','Jose','0600000000','Bordeaux','1 rue Exemple',3);
