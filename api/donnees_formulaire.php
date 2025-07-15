<?php
require_once __DIR__ . '/../config/database.php';
header('Content-Type: application/json; charset=utf-8');
$pdo = getDatabaseConnection();

$result = [
  'anneesunivs' => [],
  'niveaux' => [],
  'facultes' => [],
  'departements' => [],
  'parcours' => []
];

// Années académiques
$idanuniv = $pdo->query("
    SELECT idanuniv FROM anneesunivs WHERE cloture = 0 ORDER BY anuniv DESC LIMIT 1
")->fetchColumn();

// Facultés (mentions)
$stmt = $pdo->prepare("
    SELECT anunivfacs.idanfac, anunivfacs.idanuniv, facultes.fac
    FROM anunivfacs
    JOIN facultes ON anunivfacs.idf = facultes.idf
    WHERE anunivfacs.idanuniv = ?
      AND facultes.archive = 0
      AND facultes.sup = 0
    ORDER BY facultes.fac ASC
");
$stmt->execute([$idanuniv]);
$result['facultes'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Départements (instituts)
$stmt = $pdo->prepare("
    SELECT anfacdepts.idanfac, anfacdepts.idanfacdept, departements.code, departements.departement
    FROM anfacdepts
    JOIN departements ON anfacdepts.iddep = departements.iddep
    WHERE anfacdepts.idanfac IN (
        SELECT idanfac FROM anunivfacs WHERE idanuniv = ?
    )
      AND departements.archive = 0 AND departements.sup = 0
    ORDER BY departements.departement ASC
");
$stmt->execute([$idanuniv]);
$result['departements'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Niveaux
$stmt = $pdo->prepare("
    SELECT anfacdptniv.idanfacdept, anfacdptniv.idanfdn, niveaux.niveau
    FROM anfacdptniv
    JOIN niveaux ON anfacdptniv.idnv = niveaux.idnv
    WHERE anfacdptniv.idanfacdept IN (
        SELECT idanfacdept FROM anfacdepts WHERE idanfac IN (
            SELECT idanfac FROM anunivfacs WHERE idanuniv = ?
        )
    )
      AND niveaux.archive = 0 AND niveaux.sup = 0
");
$stmt->execute([$idanuniv]);
$result['niveaux'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Parcours (spécialités)
$stmt = $pdo->prepare("
    SELECT anfacdptnvparcs.idafdnp, anfacdptnvparcs.idanfdn, parcours.libelle, parcours.idparc
    FROM anfacdptnvparcs
    JOIN parcours ON anfacdptnvparcs.idparc = parcours.idparc
    WHERE anfacdptnvparcs.idanfdn IN (
        SELECT idanfdn FROM anfacdptniv WHERE idanfacdept IN (
            SELECT idanfacdept FROM anfacdepts WHERE idanfac IN (
                SELECT idanfac FROM anunivfacs WHERE idanuniv = ?
            )
        )
    )
      AND parcours.archive = 0 AND parcours.sup = 0
    ORDER BY parcours.libelle ASC
");
$stmt->execute([$idanuniv]);
$result['parcours'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Envoie toutes les données d’un coup !
echo json_encode($result);
