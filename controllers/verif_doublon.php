<?php
require_once __DIR__ . '/../config/database.php';
$nom = $_POST['nom'] ?? '';
$prenom = $_POST['prenom'] ?? '';
$date_naissance = $_POST['date_naissance'] ?? '';
$pdo = getDatabaseConnection();
$stmt = $pdo->prepare("SELECT COUNT(*) FROM preinscriptions WHERE nom = ? AND prenoms = ? AND datenaiss = ?");
$stmt->execute([$nom, $prenom, $date_naissance]);
$count = $stmt->fetchColumn();

if ($count > 0) {
  echo "Une préinscription avec les mêmes nom, prénom et date de naissance existe déjà.";
} else {
  echo "OK";
}
