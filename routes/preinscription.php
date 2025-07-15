<?php
/*file_put_contents(__DIR__.'/../debug_route.txt', 'Route lancée' . PHP_EOL, FILE_APPEND);
file_put_contents(__DIR__.'/debug_post.txt', var_export($_POST, true) . PHP_EOL, FILE_APPEND);
file_put_contents(__DIR__.'/debug_files.txt', var_export($_FILES, true) . PHP_EOL, FILE_APPEND);

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
error_log(">>> POST reçu à " . date('Y-m-d H:i:s'));
*/
require_once __DIR__ . '/../controllers/PreinscriptionController.php';
require_once __DIR__ . '/../utils/attestation_generator.php';
require_once __DIR__ . '/../config/database.php';

// --- Fonction de mapping (définie avant usage, meilleure portabilité) ---
function mapFormDataToSql($data): array {
  return [
    'nom'                  => $data['nom'],
    'prenoms'              => $data['prenom'],
    'datenaiss'            => $data['date_naissance'],
    'lieunaiss'            => $data['lieu_naissance'],
    'sexe'                 => ($data['genre'] === 'Masculin') ? 'M' : 'F',
    'nationalite'          => $data['nationalite'],
    'matrimo'              => $data['situation_matrimoniale'],
    'telperso'             => $data['telephone_etudiant'],
    'telephone_parent'     => $data['telephone_parent'],
    'domicile'             => $data['lieu_residence'],
    'email'                => $data['email'],
    'photo'                => $data['photo'],
    'bac'                  => $data['serie_bac'],
    'anneebac'             => $data['annee_bac'],
    'moybac' => ($data['moyenne_bac'] === '' || is_null($data['moyenne_bac'])) ? null : floatval(str_replace(',', '.', $data['moyenne_bac'])),
    'mention'              => $data['mention_bac'],
    'etaborigin'           => $data['etablissement_provenance'],
    'pays_bac'             => $data['pays_bac'],
    'oriente'              => ($data['oriente'] === 'Oui') ? 1 : 0,
    'noriente'             => $data['numero_orientation'] ?? '',
    'boursier'             => ($data['boursier'] === 'Oui') ? 'OUI' : 'NON',
    'nombourse'            => $data['denomination_bourse'] ?? '',
    'organisme'            => $data['organisme_donateur'] ?? '',
    'numbourse'            => $data['numero_bourse'] ?? '',
    'institut'             => $data['institut'],
    'mention_orientation'  => $data['mention'],
    'niveau'               => $data['niveau'],
    'specialite'           => $data['specialite'],
    'annee_academique'     => $data['annee_academique'],
  ];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  // 1. Gestion du fichier photo
  $photoFileName = null;
  if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
    $allowedExtensions = ['jpg', 'jpeg', 'png'];
    $fileTmpPath = $_FILES['photo']['tmp_name'];
    $fileName = $_FILES['photo']['name'];
    $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    if (in_array($fileExtension, $allowedExtensions)) {
      $uniqueName = uniqid('photo_') . '.' . $fileExtension;
      $uploadDir = __DIR__ . '/../img/photos/';
      if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
      $destPath = $uploadDir . $uniqueName;
      if (move_uploaded_file($fileTmpPath, $destPath)) {
        $photoFileName = $uniqueName;
      } else {
        file_put_contents(__DIR__.'/../debug_route.txt', "Erreur photo", FILE_APPEND);
        exit;
      }
    } else {
      file_put_contents(__DIR__.'/../debug_route.txt', "Format photo non autorisé", FILE_APPEND);
      exit;
    }
  }

  // 2. Récupération des données formulaire (noms HTML)
  function trim_null($value) {
    return trim((string)($value ?? ''));
  }

  $data = [
    'nom'                    => trim_null($_POST['nom'] ?? ''),
    'prenom'                 => trim_null($_POST['prenom'] ?? ''),
    'date_naissance'         => trim_null($_POST['date_naissance'] ?? ''),
    'lieu_naissance'         => trim_null($_POST['lieu_naissance'] ?? ''),
    'nationalite'            => trim_null($_POST['nationalite'] ?? ''),
    'situation_matrimoniale' => trim_null($_POST['situation_matrimoniale'] ?? ''),
    'genre'                  => trim_null($_POST['genre'] ?? ''),
    'telephone_etudiant'     => trim_null($_POST['telephone_etudiant'] ?? ''),
    'telephone_parent'       => trim_null($_POST['telephone_parent'] ?? ''),
    'email'                  => trim_null($_POST['email'] ?? ''),
    'lieu_residence'         => trim_null($_POST['lieu_residence'] ?? ''),
    'serie_bac'              => trim_null($_POST['serie_bac'] ?? ''),
    'annee_bac'              => trim_null($_POST['annee_bac'] ?? ''),
    'moyenne_bac'            => trim_null($_POST['moyenne_bac'] ?? ''),
    'mention_bac'            => trim_null($_POST['mention_bac'] ?? ''),
    'etablissement_provenance'=> trim_null($_POST['etablissement_provenance'] ?? ''),
    'pays_bac'               => trim_null($_POST['pays_bac'] ?? ''),
    'oriente'                => trim_null($_POST['oriente'] ?? 'Non'),
    'numero_orientation'     => trim_null($_POST['numero_orientation'] ?? ''),
    'boursier'               => trim_null($_POST['boursier'] ?? 'Non'),
    'denomination_bourse'    => trim_null($_POST['denomination_bourse'] ?? ''),
    'organisme_donateur'     => trim_null($_POST['organisme_donateur'] ?? ''),
    'numero_bourse'          => trim_null($_POST['numero_bourse'] ?? ''),
    'institut'               => trim_null($_POST['institut'] ?? ''),
    'mention'                => trim_null($_POST['mention'] ?? ''),
    'niveau'                 => trim_null($_POST['niveau'] ?? ''),
    'specialite'             => trim_null($_POST['specialite'] ?? ''),
    'annee_academique'       => trim_null($_POST['annee_academique'] ?? ''),
    'photo'                  => $photoFileName,
  ];
  file_put_contents(__DIR__.'/../debug_route.txt', "POST récupéré\n", FILE_APPEND);

  // 3. Mapping
  file_put_contents(__DIR__.'/../debug_route.txt', "Mapping fait\n", FILE_APPEND);
  $data_sql = mapFormDataToSql($data);
  $data_sql['numero']    = uniqid();
  $data_sql['idanuniv']  = 1;
  $data_sql['idafdnp']   = 1;

  error_log("ROUTE DATA : " . print_r($data_sql, true));
  file_put_contents(__DIR__.'/../debug_route.txt', "Avant controller\n", FILE_APPEND);

  // 4. Enregistrement en base
  $controller = new PreinscriptionController();
  $insertedId = $controller->enregistrer($data_sql);

  file_put_contents(__DIR__.'/../debug_route.txt', "Après controller\n", FILE_APPEND);

  // recuperation des vrai valeur des ID pour le formulaire avant generation du PDF
  $pdo = getDatabaseConnection();
  $idInstitut   = $data['institut']   ?? null;
  $idMention    = $data['mention']    ?? null;
  $idNiveau     = $data['niveau']     ?? null;
  $idSpecialite = $data['specialite'] ?? null;

  // Faculté (Institut)
  $stmt = $pdo->prepare("SELECT facultes.fac
                       FROM anunivfacs
                       JOIN facultes ON anunivfacs.idf = facultes.idf
                       WHERE anunivfacs.idanfac = ?");
  $stmt->execute([$idInstitut]);
  $libelleInstitut = $stmt->fetchColumn();

// Mention (Département)
  $stmt = $pdo->prepare("SELECT departements.departement
                       FROM anfacdepts
                       JOIN departements ON anfacdepts.iddep = departements.iddep
                       WHERE anfacdepts.idanfacdept = ?");
  $stmt->execute([$idMention]);
  $libelleMention = $stmt->fetchColumn();

// Niveau
  $stmt = $pdo->prepare("SELECT niveaux.niveau
                       FROM anfacdptniv
                       JOIN niveaux ON anfacdptniv.idnv = niveaux.idnv
                       WHERE anfacdptniv.idanfdn = ?");
  $stmt->execute([$idNiveau]);
  $libelleNiveau = $stmt->fetchColumn();

// Spécialité (Parcours)
  $stmt = $pdo->prepare("SELECT parcours.libelle
                       FROM anfacdptnvparcs
                       JOIN parcours ON anfacdptnvparcs.idparc = parcours.idparc
                       WHERE anfacdptnvparcs.idafdnp = ?");
  $stmt->execute([$idSpecialite]);
  $libelleSpecialite = $stmt->fetchColumn();


  $data['institut']   = $libelleInstitut;
  $data['mention']    = $libelleMention;
  $data['niveau']     = $libelleNiveau;
  $data['specialite'] = $libelleSpecialite;


  // 5. Génération du PDF
  if ($insertedId) {
    $pdfPath = genererAttestationPDF($data);
    if (is_string($pdfPath) && file_exists($pdfPath) && filesize($pdfPath) > 0) {
      if (ob_get_length()) ob_end_clean(); // Sécurité anti-header déjà envoyé
      header('Content-Type: application/pdf');
      header('Content-Disposition: attachment; filename="attestation_preinscription_ESGIS.pdf"');
      readfile($pdfPath);
    } /*else {
      file_put_contents(__DIR__.'/../debug_route.txt', "Erreur lors de la génération du PDF", FILE_APPEND);
      // Pas d'affichage avant le header !
    }*/
  } /*else {
    file_put_contents(__DIR__.'/../debug_route.txt', "Erreur lors de l'enregistrement", FILE_APPEND);
  }*/
} else {
  header('HTTP/1.1 405 Method Not Allowed');
  error_log('Arrivée dans le contrôleur');
}
exit;
