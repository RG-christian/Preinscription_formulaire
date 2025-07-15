<?php
// config/database.php

// Configuration des paramètres de connexion
const DB_HOST = 'localhost';
const DB_NAME = 'mx24excorde';
const DB_USER = 'root';              //  ton utilisateur
const DB_PASS = 'root';                  //ton mot de passe

const DB_PORT = 3307;

// Fonction de connexion PDO
function getDatabaseConnection()
{
  try {


    $dsn = 'mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME . ';charset=utf8mb4';

    $options = [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Gérer les erreurs proprement
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Retourne les résultats sous forme de tableau associatif
      PDO::ATTR_EMULATE_PREPARES => false                   // Sécurité : désactiver l'émulation des requêtes préparées
    ];

    return new PDO($dsn, DB_USER, DB_PASS, $options);
  } catch (PDOException $e) {
    die('Erreur de connexion à la base de donnéeys : ' . $e->getMessage());
  }
}




/*deja on se base sur ce type de données
la table préinscripton a un champs  l'année accademique qui sera generer automatiquement
 selon l'année actuel ensuite le champs institut qui aurra trois institue
dont 'Science et Technologie' ,'Sciences Economiques et de Gestion' , 'Sciences
Juridiques' , ensuite lle champs  mention selons les institue donc ca doit etre dynamique
donc on a : pour institue Science et Technologie on a la mention 'Informaitque'
pour Sciences Economiques et de Gestion on a comme mention 'sciences de Gestion'
et pour Sciences Juridiques on a 'Droit Privé', ensuite on a le champs niveau
qui afficher le niveau selon la mention selectionné donc on a :
pour la mention 'Science et Technologie' on a 6 niveau dont 'Licence 1',
'Licence 2 Tronc Commun', 'Licence 3 Génie Logiciel' ,
'Licence 3 Systèmes, Réseaux, Sécurité' , 'Master 1' ,'Master 2' .
  pour la mention  'Sciences Economiques et de Gestion' on a 14 niveau dont  :
'Licence 1 Tronc Commun 1 (LI TCI)', 'Licence 1 Tronc Commun 2 (LITC2)'
'Licence 1 Tronc Commun 3 (LITC3)' , 'Licence 1 Tronc Commun 4 (LITC4)'
'Licence 1 Tronc Commun 5 (LITC5)' , 'Licence 2 Tronc Commun 1 (L2TC1)'
'Master 1' , 'Master 2' , 'Licence 3' , 'Licence 2' ,
'Licence 2 Tronc Commun 2 (L2TC2)' 'Licence 2 Tronc Commun 3 (L2TC3)' ,
'Licence 2 Tronc Commun 4 (L2TC4)' ,  'Licence 1 Tronc Commun 6 (LI TC6)'
et pour la mention 'Droit Privé' on a 5 niveau dont  : 'Licence' , 'Licence 2'
,'Licence 3' , 'Master 1',  'Master 2' et enfin nous avons le champs  spécialité qui
s'affiche en fonction des niveaux donc on a : pour 'Science et Technologie',
et pour l'institue 'Science et Technologie' de niveau 'Licence 1',  'Licence 2
Tronc Commun', 'Master 1' ,'Master 2' la spécialité est 'Génie Informatique'
,pour  'Licence 3 Génie Logiciel' la spécialité est ' Génie Logiciel' et pour
'Licence 3 Systèmes, Réseaux, Sécurité' a comme spécialité 'Systèmes, Réseaux, Sécurité' .
pour Sciences Economiques et de Gestion pour mention 'sciences de Gestion' et de
niveau 'Licence 1 Tronc Commun 1 (LITCI)' , 'Licence 1 Tronc Commun 2 (LITC2)'
'Licence I Tronc Commun 3 (LITC3)' , 'Licence 1 Tronc Commun 4 (LITC4)'
'Licence I Tronc Commun 5 (LITC5)' , 'Licence 2 Tronc Commun 1 (L2TC1)'
'Licence 2 Tronc Commun 2 (L2TC2)' 'Licence 2 Tronc Commun 3 (L2TC3)'
'Licence 2 Tronc Commun 4 (L2TC4)' ,L'icence 1 Tronc Commun 6 (LI TC6)'
la spécialité c'est ' Tronc Commun' ,  pour  le niveau 'Lincence 3'
a pour spécialités 'Banque Finance Assurance' , 'Comptabilité Contrôle Audit'
'Gestion des Ressources Humaines'  , 'Logistique Transpon'
'Management Intemational' ,'Marketing Communication' , 'Qualité Sécurité Environnement'
et pour le niveau 'Master 1' ,'Master 2' nous avons comme spécialité
'Banque Ingénierie Financière' , 'Banque Finance Assurance'
'Comptabilité Contrôle Audit'  , 'Gestion des Ressources Humaines'
'Logistique Transport'  , 'Management International'
'Marketing Cornmunication' , 'Qualité Sécurité Environnement'

  et enfin pour l'institut Sciences Juridiques avec mention  'Droit Privé'
et de niveau 'Licence' , 'Licence 2' ,'Licence 3' , 'Master 1',
  on  a comme spécialité 'Droit des Affaires et Fiscalité'
voila commet estructuer la table préiscription
avez vous compris le systeme faite uen simmulation avant de commencer
l'implementation de la table '
*/
