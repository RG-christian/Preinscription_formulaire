// public/assets/js/preinscription.js

const nationalites = [
  "Afghane", "Albanaise", "Algérienne", "Allemande","Anglaise", "Américaine", "Andorrane", "Angolaise", "Antiguaise-et-Barbudienne",
  "Argentine", "Arménienne", "Australienne", "Autrichienne", "Azerbaïdjanaise", "Bahaméenne", "Bahreïnienne", "Bangladaise",
  "Barbadienne", "Belge", "Bélizienne", "Béninoise", "Bhoutanaise", "Biélorusse", "Birmane", "Bolivienne", "Boslniène",
  "Botswanaise", "Brésilienne", "Britannique", "Brunéienne", "Bulgare", "Burkinabè", "Burundaise", "Cambodgienne", "Camerounaise",
  "Canadienne", "Cap-Verdienne", "Centrafricaine", "Chilienne", "Chinoise", "Chypriote", "Colombienne", "Comorienne", "Congolaise (Congo-Brazzaville)",
  "Congolaise (Congo-Kinshasa)", "Costaricaine", "Croate", "Cubaine", "Danoise", "Djiboutienne", "Dominicaine", "Dominiquaise",
  "Écossaise","Égyptienne", "Émirienne", "Équato-Guinéenne", "Équatorienne", "Érythréenne", "Espagnole", "Estonienne", "Éthiopienne",
  "Fidjienne", "Finlandaise", "Française", "Gabonaise","Galloise", "Gambienne", "Georgienne", "Ghanéenne", "Grecque", "Grenadienne",
  "Guatémaltèque", "Guinéenne", "Guinéenne (Guinée-Bissau)", "Guinéenne (Guinée équatoriale)", "Guyanienne", "Haïtienne",
  "Hondurienne", "Hongroise", "Indienne", "Indonésienne", "Irakienne", "Iranienne", "Irlandaise", "Islandais", "Israélienne",
  "Italienne", "Ivoirienne", "Jamaïcaine", "Japonaise", "Jordanienne", "Kazakhstanaise", "Kenyane", "Kirghize", "Kiribatienne",
  "Koweïtienne", "Laotienne", "Lesothienne", "Lettone", "Libanaise", "Libérienne", "Libyenne", "Liechtensteinoise", "Lituanienne",
  "Luxembourgeoise", "Macédonienne", "Malaisienne", "Malawite", "Maldivienne", "Malienne", "Maltaise", "Marocaine", "Marshallaise",
  "Mauricienne", "Mauritanienne", "Mexicaine", "Micronésienne", "Moldave", "Monégasque", "Mongole", "Monténégrine", "Mozambicaine",
  "Namibienne", "Nauruane", "Népalaise", "Néerlandaise", "Néo-Zélandaise", "Nicaraguayenne", "Nigérienne", "Nigériane",
  "Norvégienne", "Omanaise", "Ougandaise", "Ouzbèke", "Pakistanaise", "Palaosienne", "Palestinienne", "Panaméenne", "Papouasienne",
  "Paraguayenne", "Péruvienne", "Philippine", "Polonaise", "Portugaise", "Qatarienne", "Roumaine", "Russe", "Rwandaise",
  "Saint-Lucienne", "Saint-Marinaise", "Saint-Vincentaise-et-Grenadine", "Salomonaise", "Salvadorienne", "Samoane", "Santoméenne",
  "Saoudienne", "Sénégalaise", "Serbe", "Seychelloise", "Sierra-Léonaise", "Singapourienne", "Slovaque", "Slovène", "Somalienne",
  "Soudanaise", "Sri-Lankaise", "Sud-Africaine", "Sud-Soudanaise", "Suédoise", "Suisse", "Surinamaise", "Swazie", "Syrienne",
  "Tadjike", "Tanzanienne", "Tchadienne", "Tchèque", "Thaïlandaise", "Timoraise", "Togolaise", "Tonguienne", "Trinidadienne",
  "Tunisienne", "Turkmène", "Turque", "Tuvaluane", "Ukrainienne", "Uruguayenne", "Vanuatuane", "Vénézuélienne", "Vietnamienne",
  "Yéménite", "Zambienne", "Zimbabwéenne"
];


window.addEventListener("DOMContentLoaded", () => {
  // Faculté → Mention
  // Faculté (Institut) → Mention
  document.getElementById('institut-orientation').addEventListener('change', function() {
    const val = this.value;
    const mention = document.getElementById('mention-orientation');
    Array.from(mention.options).forEach(opt => {
      opt.style.display = (opt.value === "" || opt.classList.contains('parent-fac-' + val)) ? '' : 'none';
    });
    mention.disabled = (val === "");
    mention.value = '';
    mention.dispatchEvent(new Event('change'));
  });

// Mention → Niveau
  document.getElementById('mention-orientation').addEventListener('change', function() {
    const val = this.value;
    const niveau = document.getElementById('niveau-orientation');
    Array.from(niveau.options).forEach(opt => {
      opt.style.display = (opt.value === "" || opt.classList.contains('parent-mention-' + val)) ? '' : 'none';
    });
    niveau.disabled = (val === "");
    niveau.value = '';
    niveau.dispatchEvent(new Event('change'));
  });

// Niveau → Spécialité
  document.getElementById('niveau-orientation').addEventListener('change', function() {
    const val = this.value;
    const specialite = document.getElementById('specialite-orientation');
    Array.from(specialite.options).forEach(opt => {
      opt.style.display = (opt.value === "" || opt.classList.contains('parent-niv-' + val)) ? '' : 'none';
    });
    specialite.disabled = (val === "");
    specialite.value = '';
  });



  //selection des années bac
  const selectAnnee = document.getElementById('annee_bac');
  if (selectAnnee) {
    const anneeActuelle = new Date().getFullYear();
    for (let annee = anneeActuelle; annee >= 1990; annee--) {
      const option = document.createElement('option');
      option.value = annee.toString();
      option.textContent = annee.toString();
      selectAnnee.appendChild(option);
    }
  }

  // ==== 3. Champs conditionnels : Orientation & Boursier ====
  // 1. Gestion orientation
  const orienteSelect = document.getElementById('oriente');
  const orientationContainer = document.getElementById('num-orientation-container');
  const numeroOrientationInput = orientationContainer.querySelector('input');

  orienteSelect.addEventListener('change', function () {
    if (this.value === "Oui") {
      orientationContainer.classList.remove('d-none');
      numeroOrientationInput.required = true;
      numeroOrientationInput.disabled = false;
    } else {
      orientationContainer.classList.add('d-none');
      numeroOrientationInput.value = "";
      numeroOrientationInput.required = false;
      numeroOrientationInput.disabled = true;
    }
  });

// 2. Gestion bourse
  const boursierSelect = document.getElementById('boursier');
  const bourseFields = document.getElementById('bourse-fields');
  const bourseInputs = bourseFields.querySelectorAll('input');

  boursierSelect.addEventListener('change', function () {
    if (this.value === "Oui") {
      bourseFields.classList.remove('d-none');
      bourseInputs.forEach(inp => {
        inp.required = true;
        inp.disabled = false;
      });
    } else {
      bourseFields.classList.add('d-none');
      bourseInputs.forEach(inp => {
        inp.value = "";
        inp.required = false;
        inp.disabled = true;
      });
    }
  });

// Au chargement, applique l’état initial
  orienteSelect.dispatchEvent(new Event('change'));
  boursierSelect.dispatchEvent(new Event('change'));

// selecteur de l'etape 1
  const selectNationalite = document.getElementById('nationalite');

  nationalites.forEach(function(nat) {
    const option = document.createElement('option');
    option.value = nat;
    option.textContent = nat;
    selectNationalite.appendChild(option);
  });



  // ==== 4. Gestion des étapes & Navigation ====
  let currentStep = 1;
  const totalSteps = 4;
  const stepForms = Array.from(document.querySelectorAll('.step-form'));
  console.log("Étapes détectées :", stepForms.map(e => e.className));



  const circles = [
    document.getElementById('circle-1'),
    document.getElementById('circle-2'),
    document.getElementById('circle-3'),
    document.getElementById('circle-4')
  ];
  const lines = document.querySelectorAll('.progress-bar-step .line');


  function updateStepDisplay() {
    stepForms.forEach((el, i) => el.classList.toggle('active-step', i === (currentStep - 1)));

    // ✅ Force le scroll automatique vers l'étape visible
    const activeStep = document.querySelector('.step-form.active-step');
    if (activeStep) {
      setTimeout(() => {
        activeStep.scrollIntoView({behavior: "smooth", block: "start"});
      }, 100);
    }

    circles.forEach((c, i) => {
      c.classList.toggle('active', i === (currentStep - 1));
      c.classList.toggle('done', i < (currentStep - 1));
    });
    lines.forEach((l, i) => {
      l.classList.toggle('active', i < (currentStep - 1));
    });


    console.log("Étape active affichée :", currentStep);
    const step = document.querySelector(`.step-${currentStep}`);
    step && step.scrollIntoView({ behavior: "smooth", block: "start" });

  }


  function validateCurrentStep(stepIndex) {
    const step = document.querySelector('.step-' + stepIndex);
    let valid = true;
    step.querySelectorAll('.is-invalid').forEach(e => e.classList.remove('is-invalid'));
    step.querySelectorAll('[required]').forEach(field => {
      if (field.offsetParent === null) {
        console.log("Champ masqué ignoré :", field.name);
        return;
      }
      if (!field.value.trim()) {
        console.log("Champ vide bloquant :", field.name);
        field.classList.add('is-invalid');
        valid = false;
      }
      if (field.type === "email" && field.value && !/\S+@\S+\.\S+/.test(field.value)) {
        field.classList.add('is-invalid');
        valid = false;
      }
      if (field.type === "tel" && field.value && !/^\+?\d{9,}$/.test(field.value)) {
        field.classList.add('is-invalid');
        valid = false;
      }
    });
    return valid;
  }

  // --- Gestion des erreurs d’étape ---
  function showStepError(stepNum, message) {
    const errorDiv = document.getElementById(`step${stepNum}-error`);
    if (errorDiv) {
      errorDiv.textContent = message;
      errorDiv.style.display = "block";
    }
  }
  function hideStepError(stepNum) {
    const errorDiv = document.getElementById(`step${stepNum}-error`);
    if (errorDiv) {
      errorDiv.textContent = "";
      errorDiv.style.display = "none";
    }
  }

  // Masquer l’erreur dès qu’on modifie un champ
  document.querySelectorAll('.step-form input, .step-form select').forEach(field => {
    field.addEventListener('input', () => {
      for (let idx = 1; idx <= totalSteps; idx++) {
        if (field.closest('.step-' + idx)) hideStepError(idx);
      }
    });
  });

  // ==== 5. Navigation boutons SUIVANT / PRÉCÉDENT ====
  // === Navigation ===

  document.getElementById('next-btn-1').onclick = async function () {
    hideStepError(1);
    if (!validateCurrentStep(1)) {
      showStepError(1, "Veuillez remplir tous les champs obligatoires correctement.");
      return;
    }
    document.getElementById('step1-error').style.display = "none";
    currentStep = 2;
    updateStepDisplay();
    // Exemple: vérifier le doublon côté serveur ici
    try {
      const nom = document.querySelector('[name="nom"]').value.trim();
      const prenom = document.querySelector('[name="prenom"]').value.trim();
      const dateNaissance = document.querySelector('[name="date_naissance"]').value.trim();

      const res = await fetch("/controllers/verif_doublon.php", {
        method: "POST",
        body: new URLSearchParams({ nom, prenom, date_naissance: dateNaissance }),
        headers: { "Content-Type": "application/x-www-form-urlencoded" }
      });
      const text = await res.text();

      if (res.ok && text === "OK") {
        currentStep = 2;
        updateStepDisplay();
      } else {
        showStepError(1, text);
      }
    } catch (e) {
      showStepError(1, "Erreur serveur : impossible de vérifier.");
    }
  };

  document.getElementById('next-btn-2').onclick = function () {
    if (!validateCurrentStep(2)) {
      document.getElementById('step2-error').textContent = "Veuillez remplir tous les champs obligatoires.";
      document.getElementById('step2-error').style.display = "block";
      return;
    }
    document.getElementById('step2-error').style.display = "none";
    currentStep = 3;
    updateStepDisplay();
  };

  document.getElementById('next-btn-3').onclick = function () {
    const errorContainer = document.getElementById('step3-error');

    if (!validateCurrentStep(3)) {
      errorContainer.textContent = "Veuillez remplir tous les champs obligatoires.";
      errorContainer.style.display = "block";
      return;
    }

    errorContainer.style.display = "none";
    currentStep = 4;
    updateStepDisplay();

    // Debug
    console.log("Étape 3 validée, passage à l'étape 4");
    const step4 = document.querySelector('.step-4');
    if (step4) {
      step4.scrollIntoView({ behavior: 'smooth', block: 'start' });
      console.log("Step 4 contenu : ", step4.innerHTML);
    } else {
      console.error("⚠️ Élément .step-4 introuvable !");
    }
  };
  if (!document.querySelector('.step-4')) {
    console.error("❌ L'étape 4 est absente du DOM ou mal structurée.");
  }



// precedent
  document.getElementById('prev-btn-2').onclick = () => { currentStep = 1; updateStepDisplay(); };
  document.getElementById('prev-btn-3').onclick = () => { currentStep = 2; updateStepDisplay(); };
  document.getElementById('prev-btn-4').onclick = () => { currentStep = 3; updateStepDisplay(); };

  // Afficher l’étape initiale au lancement
  updateStepDisplay();

  const slides = document.querySelectorAll('.image-slide');
  let current = 0;

  setInterval(() => {
    slides[current].classList.remove('active');
    current = (current + 1) % slides.length;
    slides[current].classList.add('active');
  }, 10000);





  const form = document.getElementById("form-preinscription");

  form.addEventListener("submit", async function (e) {
    e.preventDefault();

    // === Validation ===
    const requiredFields = form.querySelectorAll("[required]");
    let valid = true;
    let firstInvalidField = null;

    requiredFields.forEach((field) => {
      field.classList.remove("is-invalid");
      if (!field.value.trim()) {
        valid = false;
        field.classList.add("is-invalid");
        if (!firstInvalidField) {
          firstInvalidField = field;
        }
      }
    });

    if (!valid) {
      if (firstInvalidField) {
        firstInvalidField.focus();
      }
      return;
    }

    // === Traitement (fetch, affichage, etc.) ===
    const formData = new FormData(form);

    const submitBtn = document.getElementById("submit-btn");
    const btnText = document.getElementById("submit-text");
    const spinner = document.getElementById("submit-spinner");

    const messageBox = document.getElementById("message-erreur");
    const successBox = document.getElementById("submit-success");

    // UI init
    submitBtn.disabled = true;
    btnText.textContent = "Génération du fichier...";
    spinner.classList.remove("d-none");

    messageBox.style.display = "none";
    messageBox.textContent = "";
    successBox.classList.add("d-none");
    successBox.textContent = "";

    try {
      const response = await fetch("/routes/preinscription.php", {
        method: "POST",
        body: formData,
      });

      if (!response.ok) {
        const errorText = await response.text();
        throw new Error(errorText);
      }

      const blob = await response.blob();
      const url = window.URL.createObjectURL(blob);

      const a = document.createElement("a");
      a.href = url;
      a.download = "attestation_preinscription.pdf";
      document.body.appendChild(a);
      a.click();
      a.remove();
      window.URL.revokeObjectURL(url);

      successBox.textContent = "✅ Votre attestation a été téléchargée avec succès.";
      successBox.classList.remove("d-none");

      setTimeout(() => {
        window.location.reload();
      }, 5000);

    } catch (error) {
      messageBox.textContent = "❌ " + error.message;
      messageBox.style.display = "block";
    } finally {
      submitBtn.disabled = false;
      btnText.textContent = "Envoyer la demande";
      spinner.classList.add("d-none");

      setTimeout(() => {
        messageBox.style.display = "none";
        successBox.classList.add("d-none");
      }, 6000);
    }
  });




});





