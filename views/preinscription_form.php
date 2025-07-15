
<?php

require_once __DIR__ . '/../config/database.php';
$pdo = getDatabaseConnection();
// Génération automatique de l'année académique
$annee = date('Y');
$annee_academique = $annee . '-' . ($annee + 1);

/// Années académiques
// Par exemple, récupère la dernière année universitaire non clôturée
$stmt = $pdo->query("SELECT idanuniv FROM anneesunivs WHERE cloture = 0 ORDER BY anuniv DESC LIMIT 1");
$idanuniv = $stmt->fetchColumn();


// Facultés (mentions)
$sqlFacultes = "SELECT anunivfacs.idanfac, facultes.fac
                FROM anunivfacs
                JOIN facultes ON anunivfacs.idf = facultes.idf
                WHERE anunivfacs.idanuniv = :idanuniv
                  AND facultes.archive = 0
                  AND facultes.sup = 0
                ORDER BY facultes.fac ASC";
$stmt = $pdo->prepare($sqlFacultes);
$stmt->execute([':idanuniv' => $idanuniv]);
$facultes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 2. Mentions/départements
$sqlMentions = "SELECT anfacdepts.idanfac, anfacdepts.idanfacdept, departements.departement
                FROM anfacdepts
                JOIN departements ON anfacdepts.iddep = departements.iddep
                WHERE departements.archive = 0 AND departements.sup = 0
                ORDER BY departements.departement ASC";
$mentions = $pdo->query($sqlMentions)->fetchAll(PDO::FETCH_ASSOC);

// 3. Niveaux
$sqlNiveaux = "SELECT anfacdptniv.idanfacdept, anfacdptniv.idanfdn, niveaux.niveau
               FROM anfacdptniv
               JOIN niveaux ON anfacdptniv.idnv = niveaux.idnv
               WHERE niveaux.archive = 0 AND niveaux.sup = 0";
$niveaux = $pdo->query($sqlNiveaux)->fetchAll(PDO::FETCH_ASSOC);

// 4. Spécialités
$sqlParcours = "SELECT anfacdptnvparcs.idanfdn, anfacdptnvparcs.idafdnp, parcours.libelle
                FROM anfacdptnvparcs
                JOIN parcours ON anfacdptnvparcs.idparc = parcours.idparc
                WHERE parcours.archive = 0 AND parcours.sup = 0
                ORDER BY parcours.libelle ASC";
$parcours = $pdo->query($sqlParcours)->fetchAll(PDO::FETCH_ASSOC);



?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Préinscription</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
  <!-- Ajoute des nationalité  -->
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

  <link rel="stylesheet" href="../css/style.css"/>


</head>
<body>
<div class="container-fluid d-flex justify-content-center align-items-center p-0" style="min-height: 100vh;">

  <div class="row container-custom w-100">

    <div class="col-md-6 image-left d-none d-md-block">
      <div class="image-slide active" style="background-image: url('../img/preinscription_1.webp');"></div>
      <div class="image-slide" style="background-image: url('../img/preinscription_2.webp');"></div>
      <div class="image-overlay"></div>
    </div>


    <!-- FORMULAIRE -->
    <div class="col-md-6 form-section">
      <img src="../img/avatar.svg" alt="Avatar" class="form-avatar rounded-circle">
      <h2 class="form-title">Préinscription</h2>

      <!-- Barre de progression (4 étapes) -->
      <div class="step-progress mb-3">
        <div class="progress-bar-step d-flex align-items-center justify-content-between">
          <div class="circle" id="circle-1">1</div>
          <div class="line"></div>
          <div class="circle" id="circle-2">2</div>
          <div class="line"></div>
          <div class="circle" id="circle-3">3</div>
          <div class="line"></div>
          <div class="circle" id="circle-4">4</div>
        </div>
        <div class="steps-labels d-flex justify-content-between mt-2">
          <span id="label-1">Étape 1</span>
          <span id="label-2">Étape 2</span>
          <span id="label-3">Étape 3</span>
          <span id="label-4">Étape 4</span>
        </div>
      </div>

      <form  id="form-preinscription" action="/routes/preinscription.php"  enctype="multipart/form-data" method="POST" autocomplete="off" >
        <!-- ETAPE 1 : Etat Civil -->
        <div class="step-form step-1 active-step">

        <h4 class="step-title text-primary text-center mb-4">Information Personnelle</h4>
          <p class="mb-3 small text-muted">Les champs marqués d’une <span class="text-danger">*</span> sont obligatoires.</p>

          <div class="row g-4">
            <div class="col-md-6">
              <label class="form-label">Nom :<span class="text-danger">*</span></label>
              <input type="text" class="form-control" name="nom" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Prénom :<span class="text-danger">*</span></label>
              <input type="text" class="form-control" name="prenom" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Sexe/Genre :<span class="text-danger">*</span></label>
              <select class="form-select" name="genre" required>
                <option value="">-- Sélectionner le genre --</option>
                <option value="Masculin">Masculin</option>
                <option value="Féminin">Féminin</option>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label">Date de naissance :<span class="text-danger">*</span></label>
              <input type="date" class="form-control" name="date_naissance" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Lieu de naissance :<span class="text-danger">*</span></label>
              <input type="text" class="form-control" name="lieu_naissance" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Nationalité :<span class="text-danger">*</span></label>
              <select name="nationalite" id="nationalite" class="form-select" required>
                <option value="">Nationalité</option>
                <!-- Les options seront ajoutées en JS -->
              </select>

            </div>
            <div class="col-md-6">
              <label class="form-label">Situation matrimoniale :</label>
              <select name="situation_matrimoniale" class="form-select">
                <option value="">Situation maritale</option>
                <option value="Célibataire">Célibataire</option>
                <option value="Fiancé(e)">Fiancé(e)</option>
                <option value="Marié(e)">Marié(e)</option>
                <option value="Divorcé(e)">Divorcé(e)</option>
                <option value="Veuf(ve)">Veuf(ve)</option>
                <option value="Séparé(e)">Séparé(e)</option>
                <option value="Pacsé(e)">Pacsé(e)</option>
                <option value="Concubin(e)">Concubin(e)</option>
              </select>
            </div>

          </div>
          <div class="d-flex justify-content-center mt-5">
            <button type="button" class="btn btn-submit" id="next-btn-1">Suivant</button>
            <div id="step1-error" class="text-danger fw-bold mt-3" style="display:none;"></div>
          </div>
        </div>

        <!-- ETAPE 2 : Contacts -->
        <div class="step-form step-2">
          <h4 class="step-title text-primary text-center mb-4">Contacts</h4>
          <p class="mb-3 small text-muted">Les champs marqués d’une <span class="text-danger">*</span> sont obligatoires.</p>

          <div class="row g-4">
            <div class="col-md-6">
              <label class="form-label">Téléphone WhatsApp (perso) :<span class="text-danger">*</span></label>
              <input type="tel" class="form-control" name="telephone_etudiant" placeholder="+241XXXXXXXX" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Téléphone parent/tuteur :</label>
              <input type="tel" class="form-control" name="telephone_parent" placeholder="+241XXXXXXXX">
            </div>
            <div class="col-md-6">
              <label class="form-label">Adresse e-mail :</label>
              <input type="email" class="form-control" name="email" placeholder="ex: etudiant@email.com">
            </div>
            <div class="col-md-6">
              <label class="form-label">Lieu de résidence :<span class="text-danger">*</span></label>
              <input type="text" class="form-control" name="lieu_residence" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Photo d'identité :</label>
              <input type="file" class="form-control" name="photo" accept=".jpg,.jpeg,.png">
            </div>
          </div>
          <div class="d-flex justify-content-between mt-5">
            <button type="button" class="btn btn-back" id="prev-btn-2">Précédent</button>
            <button type="button" class="btn btn-submit" id="next-btn-2">Suivant</button>
            <div id="step2-error" class="text-danger fw-bold mt-3" style="display:none;"></div>
          </div>
        </div>

        <!-- ETAPE 3 : Bac et Parcours -->
        <div class="step-form step-3">
          <h4 class="step-title text-primary text-center mb-4">Bac & Parcours</h4>
          <p class="mb-3 small text-muted">Les champs marqués d’une <span class="text-danger">*</span> sont obligatoires.</p>

          <div class="row g-4">
            <div class="col-md-6">
              <label class="form-label">Série du Bac :<span class="text-danger">*</span></label>
              <select name="serie_bac" class="form-select" required>
                <option value="">--Choisir une série--</option>
                <option value="A1">A1</option><option value="A2">A2</option>
                <option value="B">B</option><option value="C">C</option><option value="D">D</option>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label">Année du Bac :<span class="text-danger">*</span></label>
              <select class="form-select" name="annee_bac" id="annee_bac" required>

              </select>
            </div>

            <div class="col-md-6">
              <label class="form-label">Numéro table Bac :</label>
              <input type="text" class="form-control" name="numero_table_bac">
            </div>
            <div class="col-md-6">
              <label class="form-label">Mention obtenue au Bac :<span class="text-danger">*</span></label>
              <select class="form-select" name="mention_bac" required>
                <option value="">-- Choisir la mention --</option>
                <option value="Passable">Passable</option>
                <option value="Assez bien">Assez bien</option>
                <option value="Bien">Bien</option>
                <option value="Très bien">Très bien</option>
              </select>
            </div>

            <div class="col-md-6">
              <label class="form-label">Établissement de provenance :<span class="text-danger">*</span></label>
              <input type="text" class="form-control" name="etablissement_provenance" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Pays d'obtention du Bac :<span class="text-danger">*</span></label>
              <input type="text" class="form-control" name="pays_bac" required>
            </div>
            <!-- ... (tes autres champs) ... -->

              <!-- Orientation -->
              <div class="col-md-6">
                <label class="form-label">Êtes-vous orienté(e)<span class="text-danger">*</span></label>
                <select class="form-select" name="oriente" id="oriente" required>
                  <option value="">-- Sélectionner --</option>
                  <option value="Oui">Oui</option>
                  <option value="Non">Non</option>
                </select>
              </div>
              <div class="col-md-6 d-none" id="num-orientation-container">
                <label class="form-label">Numéro d'orientation :<span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="numero_orientation">
              </div>

              <!-- Boursier -->
              <div class="col-md-6">
                <label class="form-label">Êtes-vous boursier(ère) ?<span class="text-danger">*</span></label>
                <select class="form-select" name="boursier" id="boursier" required>
                  <option value="">-- Sélectionner --</option>
                  <option value="Oui">Oui</option>
                  <option value="Non">Non</option>
                </select>
              </div>
              <div class="col-md-6 d-none" id="bourse-fields">
                <label class="form-label">Dénomination bourse :<span class="text-danger">*</span></label>
                <input type="text" class="form-control mb-2" name="denomination_bourse">
                <label class="form-label">Organisme donateur :<span class="text-danger">*</span></label>
                <input type="text" class="form-control mb-2" name="organisme_donateur">
                <label class="form-label">Numéro bourse :<span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="numero_bourse">
              </div>



            <div class="col-md-6">
              <label class="form-label">Moyenne Bac :</label>
              <input type="number" class="form-control" name="moyenne_bac" step="0.01">
            </div>

          </div>

          <div class="d-flex justify-content-between mt-5">
            <button type="button" class="btn btn-back" id="prev-btn-3">Précédent</button>
            <button type="button" class="btn btn-submit" id="next-btn-3">Suivant</button>
            <div id="step3-error" class="text-danger fw-bold mt-3" style="display:none;"></div>
          </div>
          </div>


        <div class="step-form step-4">
          <h4 class="step-title text-primary text-center mb-4">Choix d’orientation</h4>
          <p class="mb-3 small text-muted">Les champs marqués d’une <span class="text-danger">*</span> sont obligatoires.</p>
          <div class="row g-4">
            <!-- INSTITUT/DOMAINE/Faculté -->
            <div class="col-md-6">
              <label class="form-label" for="institut-orientation">Institut :<span class="text-danger">*</span></label>
              <select class="form-select" name="institut" id="institut-orientation" required>
                <option value="">-- Choisir un institut --</option>
                <?php foreach ($facultes as $fac): ?>
                  <option value="<?= $fac['idanfac'] ?>"><?= htmlspecialchars($fac['fac']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <!-- MENTION -->
            <div class="col-md-6">
              <label class="form-label" for="mention-orientation">Mention :<span class="text-danger">*</span></label>
              <select name="mention" id="mention-orientation" class="form-select" required disabled>
                <option value="">-- Choisir une mention --</option>
                <?php foreach ($mentions as $mention): ?>
                  <option value="<?= $mention['idanfacdept'] ?>" class="parent-fac-<?= $mention['idanfac'] ?>">
                    <?= htmlspecialchars($mention['departement']) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            <!-- NIVEAU -->
            <div class="col-md-6">
              <label class="form-label" for="niveau-orientation"><span class="text-danger">*</span>Niveau :</label>
              <select class="form-select" name="niveau" id="niveau-orientation" required disabled>
                <option value="">-- Choisir un niveau --</option>
                <?php foreach ($niveaux as $niv): ?>
                  <option value="<?= $niv['idanfdn'] ?>" class="parent-mention-<?= $niv['idanfacdept'] ?>">
                    <?= htmlspecialchars($niv['niveau']) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            <!-- SPECIALITE -->
            <div class="col-md-6">
              <label class="form-label" for="specialite-orientation">Spécialité :<span class="text-danger">*</span></label>
              <select class="form-select" name="specialite" id="specialite-orientation" required disabled>
                <option value="">-- Choisir une spécialité --</option>
                <?php foreach ($parcours as $parc): ?>
                  <option value="<?= $parc['idafdnp'] ?>" class="parent-niv-<?= $parc['idanfdn'] ?>">
                    <?= htmlspecialchars($parc['libelle']) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            <!-- ANNEE ACADEMIQUE -->
            <div class="col-md-6">
              <label class="form-label" for="annee-academique">Année académique :</label>
              <input type="text" class="form-control" name="annee_academique" id="annee-academique" value="<?= htmlspecialchars($annee_academique ?? '') ?>" readonly>
            </div>
          </div>

          <div class="d-flex justify-content-between mt-5">
            <button type="button" class="btn btn-back" id="prev-btn-4">Précédent</button>
            <button type="submit" class="btn btn-submit d-flex justify-content-center align-items-center gap-2" id="submit-btn">
              <span id="submit-text">Valider et Télécharger Mon Attestation</span>
              <div class="spinner-border spinner-border-sm d-none" id="submit-spinner" role="status"></div>
            </button>
          </div>
          <div id="step4-error" class="text-danger fw-bold mt-3" style="display:none;"></div>
          <div class="col-12 mt-4">
            <div id="message-erreur" class="text-danger fw-bold mb-3 text-center" style="display: none;"></div>
            <p class="text-success mt-3 fw-semibold d-none" id="submit-success"></p>
          </div>
        </div>


      </form>

    </div>
  </div>
</div>

<script src="../public/assets/js/preinscription.js"></script>


<script>

</script>

<!-- Bootstrap Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Script logique -->

</body>
</html>

