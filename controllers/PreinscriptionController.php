<?php
require_once __DIR__ . '/../models/Preinscription.php';
require_once __DIR__ . '/../utils/attestation_generator.php';

class PreinscriptionController
{
  /**
   * Enregistre une préinscription en base de données et génère un PDF
   *
   * @param array $data Données déjà **mappées** avec les noms de la BDD
   */

  public function enregistrer(array $data): bool
  {
    file_put_contents(__DIR__.'/../debug_controller.txt', "Entrée controller\n", FILE_APPEND);

    // Champs toujours obligatoires
    $champs_obligatoires = [
      'nom', 'prenoms', 'datenaiss', 'lieunaiss', 'sexe', 'nationalite',
      'telperso', 'domicile',
      'bac', 'anneebac', 'mention', 'etaborigin', 'pays_bac', 'oriente', 'boursier',
      'institut', 'mention_orientation', 'niveau', 'specialite', 'annee_academique'
    ];

    // Orientation : rendre le champ obligatoire QUE si "Oui"
    if (isset($data['oriente']) && ($data['oriente'] === 'Oui' || $data['oriente'] === 1 || $data['oriente'] === 'OUI')) {
      $champs_obligatoires[] = 'noriente';
    }

    // Boursier : rendre les champs obligatoires QUE si "Oui"
    if (isset($data['boursier']) && ($data['boursier'] === 'Oui' || $data['boursier'] === 1 || $data['boursier'] === 'OUI')) {
      $champs_obligatoires[] = 'nombourse';
      $champs_obligatoires[] = 'organisme';
      $champs_obligatoires[] = 'numbourse';
    }

    file_put_contents(__DIR__.'/../debug_controller.txt', "Liste champs obligatoires: " . implode(', ', $champs_obligatoires) . PHP_EOL, FILE_APPEND);
    file_put_contents(__DIR__.'/../debug_controller.txt', "Données reçues: " . print_r($data, true) . PHP_EOL, FILE_APPEND);

    foreach ($champs_obligatoires as $champ) {
      if (!isset($data[$champ]) || trim($data[$champ]) === '') {
        error_log("Champ requis vide ou non transmis: $champ (valeur='".($data[$champ] ?? 'NULL')."')");
        http_response_code(400);
        echo "<h3 style='color:red;text-align:center;'>❌ Champ requis manquant : <strong>{$champ}</strong></h3>";
        exit;
      }
    }

    $pdo = getDatabaseConnection();
    $sql = "SELECT COUNT(*) FROM preinscriptions WHERE nom = :nom AND prenoms = :prenoms AND datenaiss = :datenaiss";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
      ':nom' => $data['nom'],
      ':prenoms' => $data['prenoms'],
      ':datenaiss' => $data['datenaiss']
    ]);
    if ($stmt->fetchColumn() > 0) {
      http_response_code(409);
      echo "Une préinscription avec les mêmes nom, prénom et date de naissance existe déjà.";
      exit;
    }
    error_log("Préinscription - Données prêtes à l'enregistrement: " . print_r($data, true));

    if (!isset($data['prenoms'])) {
      echo "Erreur : le champ 'prenoms' est manquant !";

    }

    error_log("CONTROLLER DATA avant PDF : " . print_r($data, true));

    // Génération du PDF
    $pdfPath = genererAttestationPDF($data);
    if (!$pdfPath || !file_exists($pdfPath)) {
      error_log("PDF NON GÉNÉRÉ ou FICHIER INEXISTANT !");
      return false;
    }
    $data['pdf_path'] = $pdfPath;
    file_put_contents(__DIR__.'/debug_controller.txt', var_export($data, true) . PHP_EOL, FILE_APPEND);
    $preinscription = new Preinscription($data);
    return $preinscription->save();
  }
}
