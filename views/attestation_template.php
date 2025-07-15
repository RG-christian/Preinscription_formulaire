<?php
// ATTENTION : ce fichier suppose que $data est bien défini et que les clés suivantes existent dans $data !
// Exemple attendu pour $data :
// [
//   'nom' => 'DUPONT',
//   'prenoms' => 'Jean Michel',
//   'datenaiss' => '2000-01-01',
//   'lieunaiss' => 'Libreville',
//   'bac' => 'D',
//   'anneebac' => '2024',
//   'niveau' => 'Licence 1',
//   'specialite' => 'Informatique',
//   'institut' => 'Science et Technologie',
//   'annee_academique' => '2024-2025'
// ]
$nom                = strtoupper($data['nom'] ?? 'NOM');
$prenoms            = ucwords($data['prenoms'] ?? 'Prénom');
$date_naissance     = $data['datenaiss'] ?? 'JJ/MM/AAAA';
$lieu_naissance     = $data['lieunaiss'] ?? 'Ville';
$serie_bac          = $data['bac'] ?? 'Non précisé';
$annee_bac          = $data['anneebac'] ?? 'Non précisé';
$niveau             = $data['niveau'] ?? 'Niveau';
$specialite         = $data['specialite'] ?? 'Spécialité';
$institut           = $data['institut'] ?? 'Institut';
$annee_academique   = $data['annee_academique'] ?? '2024-2025';
$date_jour          = date('d/m/Y');

// Chemin absolu conseillé si mPDF ne trouve pas les images !
// $logo = $_SERVER['DOCUMENT_ROOT'] . '/img/esgis1.png';
// $cachet = $_SERVER['DOCUMENT_ROOT'] . '/img/cachet.webp';
?>

<style>
  @page { margin: 10mm; }
  body { font-family: sans-serif; font-size: 10pt; margin: 0; padding: 0; line-height: 1.3; }
  .title-box { background-color: #eee; border: 1px solid #000; border-radius: 6px; padding: 5px; text-align: center; font-size: 12pt; font-weight: bold; margin: 10px 0; }
  .centered { text-align: center; }
  .section { margin: 6px 0; }
  .signature { text-align: right; margin-top: 10px; }
  .signature img { height: 50px; margin-bottom: 2px; }
  .table { border-collapse: collapse; width: 100%; font-size: 8.5pt; margin-top: 8px; }
  .table th, .table td { border: 1px solid #888; padding: 3px; text-align: center; }
  .red { color: red; }
  .logo { height: 25px; }
  h5 { font-size: 11pt; margin-top: 15px; margin-bottom: 6px; }
</style>

<div class="attestation">

  <!-- En-tête -->
  <div class="centered">
    <img src="../img/esgis1.png" class="logo" alt="Logo"><br>
    <strong>
      <span class="red">É</span>cole <span class="red">S</span>upérieure de <span class="red">G</span>estion <span class="red">I</span>nformatique et des <span class="red">S</span>ciences
    </strong><br>
    <small>République Gabonaise : +241 (0) 11742400 | 65454524 - 66172902 - BP 1359 Libreville – www.esgis.org – Email : esgis.gabon@esgis.org</small>
  </div>

  <!-- Titre -->
  <div class="title-box">ATTESTATION DE PRÉINSCRIPTION</div>

  <!-- Corps -->
  <p class="section">
    Je soussigné, Monsieur TENDAR KOFFI Adomayakpo, Directeur Exécutif chargé des ressources humaines de l’École Supérieure de Gestion d’Informatique et des Sciences (ESGIS),
  </p>
  <p class="section">atteste que :</p>

  <p><strong>Nom :</strong> <?= htmlspecialchars($nom) ?></p>
  <p><strong>Prénom(s) :</strong> <?= htmlspecialchars($prenoms) ?></p>
  <p><strong>Date de naissance :</strong> <?= htmlspecialchars($date_naissance) ?> à <?= htmlspecialchars($lieu_naissance) ?></p>
  <p><strong>Série du Bac :</strong> <?= htmlspecialchars($serie_bac) ?> (<?= htmlspecialchars($annee_bac) ?>)</p>

  <p class="section">
    Est préinscrit(e) en <strong><?= htmlspecialchars($niveau) ?></strong>, spécialité <strong><?= htmlspecialchars($specialite) ?></strong>, à l’institut <strong><?= htmlspecialchars($institut) ?></strong> pour l’année académique <strong><?= htmlspecialchars($annee_academique) ?></strong>.
  </p>

  <p>En foi de quoi cette attestation lui est délivrée pour servir et valoir ce que de droit.</p>

  <!-- Signature -->
  <div class="signature">
    <p>Fait à Libreville, le <?= $date_jour ?></p>
    <p><strong>DIRECTEUR EXÉCUTIF</strong></p>
    <img src="../img/cachet.webp" alt="Cachet"><br>
    <p><em>TENDAR KOFFI Adomayakpo</em></p>
  </div>

  <!-- Domaines -->
  <h5 class="centered">Nos domaines et spécialités</h5>
  <table class="table">
    <thead>
    <tr>
      <th>Domaine</th>
      <th>Spécialités</th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td rowspan="7" style="vertical-align: middle;">Sciences de Gestion</td>
      <td>Marketing - Communication (MC)</td>
    </tr>
    <tr><td>Logistique - Transport (LT)</td></tr>
    <tr><td>Gestion des Ressources Humaines (GRH)</td></tr>
    <tr><td>Qualité - Sécurité - Environnement (QSE)</td></tr>
    <tr><td>Banque - Finance - Assurance (BFA)</td></tr>
    <tr><td>Management International (MI)</td></tr>
    <tr><td>Comptabilité - Contrôle - Audit (CCA)</td></tr>

    <tr>
      <td rowspan="5" style="vertical-align: middle;">Sciences Informatiques</td>
      <td>Architecture Logicielle</td>
    </tr>
    <tr><td>Systèmes Réseaux Sécurité</td></tr>
    <tr><td>Réseaux de Télécommunications & Mobilité</td></tr>
    <tr><td>Info. Appliquée à la Banque & Finance</td></tr>
    <tr><td>Management & Conseil en SI</td></tr>

    <tr>
      <td>Sciences Juridiques</td>
      <td>Droit Privé, Droit Public</td>
    </tr>
    </tbody>
  </table>
</div>
