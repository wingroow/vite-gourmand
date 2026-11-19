<?php
// Retourne en JSON les menus filtres. Requetes preparees (protection injection SQL).
require 'config.php';
header('Content-Type: application/json; charset=utf-8');

$sql = "SELECT m.menu_id, m.titre, m.description, m.nombre_personne_minimum,
               (m.prix_par_personne * m.nombre_personne_minimum) AS prix_minimum,
               t.libelle AS theme, r.libelle AS regime
        FROM menu m
        JOIN theme t ON t.theme_id = m.theme_id
        JOIN regime r ON r.regime_id = m.regime_id
        WHERE 1=1";
$params = [];

if (($_GET['prix_max'] ?? '') !== '') { $sql .= " AND m.prix_par_personne * m.nombre_personne_minimum <= :prix_max"; $params[':prix_max'] = (float) $_GET['prix_max']; }
if (($_GET['prix_min'] ?? '') !== '') { $sql .= " AND m.prix_par_personne * m.nombre_personne_minimum >= :prix_min"; $params[':prix_min'] = (float) $_GET['prix_min']; }
if (($_GET['theme'] ?? '') !== '')    { $sql .= " AND m.theme_id = :theme";   $params[':theme'] = (int) $_GET['theme']; }
if (($_GET['regime'] ?? '') !== '')   { $sql .= " AND m.regime_id = :regime"; $params[':regime'] = (int) $_GET['regime']; }
if (($_GET['personnes'] ?? '') !== '') { $sql .= " AND m.nombre_personne_minimum >= :personnes"; $params[':personnes'] = (int) $_GET['personnes']; }

$stmt = $pdo->prepare($sql . " ORDER BY m.titre");
$stmt->execute($params);
echo json_encode($stmt->fetchAll(), JSON_UNESCAPED_UNICODE);
