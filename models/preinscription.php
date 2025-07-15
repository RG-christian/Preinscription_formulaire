<?php
// models/Preinscription.php

require_once __DIR__ . '/../config/database.php';

class Preinscription
{
  private PDO $db;
  private array $data;

  public function __construct(array $data = [])
  {
    $this->db = getdatabaseconnection();
    $this->data = $data;
  }

  /**
   * Enregistre la préinscription en base
   * @return int|null ID de l'insertion ou null en cas d'échec
   */


  public function save(): ?int
  {
    file_put_contents(__DIR__.'/../debug_model.txt', "Entrée modèle\n", FILE_APPEND);

    $sql = "INSERT INTO preinscriptions (
  nom, prenoms, datenaiss, lieunaiss, sexe, nationalite, matrimo,
  telperso, telephone_parent, domicile, email, photo,
  bac, anneebac, moybac, mention, etaborigin, pays_bac,
  oriente, noriente, boursier, nombourse, organisme, numbourse,
  institut, mention_orientation, niveau, specialite, annee_academique,
  numero, idafdnp, idanuniv
) VALUES (
  :nom, :prenoms, :datenaiss, :lieunaiss, :sexe, :nationalite, :matrimo,
  :telperso, :telephone_parent, :domicile, :email, :photo,
  :bac, :anneebac, :moybac, :mention, :etaborigin, :pays_bac,
  :oriente, :noriente, :boursier, :nombourse, :organisme, :numbourse,
  :institut, :mention_orientation, :niveau, :specialite, :annee_academique,
  :numero, :idafdnp, :idanuniv
)";

    $stmt = $this->db->prepare($sql);

    // Binding des nouveaux champs
    $stmt->bindValue(':numero', $this->data['numero'] ?? uniqid());
    $stmt->bindValue(':idafdnp', $this->data['idafdnp'] ?? 1);
    $stmt->bindValue(':idanuniv', $this->data['idanuniv'] ?? 1);
    $stmt->bindValue(':nom', $this->data['nom'] ?? null);
    $stmt->bindValue(':prenoms', $this->data['prenoms'] ?? null);
    $stmt->bindValue(':datenaiss', $this->data['datenaiss'] ?? null);
    $stmt->bindValue(':lieunaiss', $this->data['lieunaiss'] ?? null);
    $stmt->bindValue(':sexe', $this->data['sexe'] ?? null);
    $stmt->bindValue(':nationalite', $this->data['nationalite'] ?? null);
    $stmt->bindValue(':matrimo', $this->data['matrimo'] ?? null);
    $stmt->bindValue(':telperso', $this->data['telperso'] ?? null);
    $stmt->bindValue(':telephone_parent', $this->data['telephone_parent'] ?? null);
    $stmt->bindValue(':domicile', $this->data['domicile'] ?? null);
    $stmt->bindValue(':email', $this->data['email'] ?? null);
    $stmt->bindValue(':photo', $this->data['photo'] ?? null);

    $stmt->bindValue(':bac', $this->data['bac'] ?? null);
    $stmt->bindValue(':anneebac', $this->data['anneebac'] ?? null);
    $stmt->bindValue(':moybac', $this->data['moybac'] ?? null);
    $stmt->bindValue(':mention', $this->data['mention'] ?? null);
    $stmt->bindValue(':etaborigin', $this->data['etaborigin'] ?? null);
    $stmt->bindValue(':pays_bac', $this->data['pays_bac'] ?? null);

    $stmt->bindValue(':oriente', $this->data['oriente'] ?? null);
    $stmt->bindValue(':noriente', $this->data['noriente'] ?? null);
    $stmt->bindValue(':boursier', $this->data['boursier'] ?? null);
    $stmt->bindValue(':nombourse', $this->data['nombourse'] ?? null);
    $stmt->bindValue(':organisme', $this->data['organisme'] ?? null);
    $stmt->bindValue(':numbourse', $this->data['numbourse'] ?? null);

    $stmt->bindValue(':institut', $this->data['institut'] ?? null);
    $stmt->bindValue(':mention_orientation', $this->data['mention_orientation'] ?? null);
    $stmt->bindValue(':niveau', $this->data['niveau'] ?? null);
    $stmt->bindValue(':specialite', $this->data['specialite'] ?? null);
    $stmt->bindValue(':annee_academique', $this->data['annee_academique'] ?? null);

    file_put_contents(__DIR__.'/debug_model.txt', var_export($this->data, true) . PHP_EOL, FILE_APPEND);

    // Exécution
    if ($stmt->execute()) {
      return $this->db->lastInsertId();
    }
    error_log("Echec insert: " . implode(' | ', $stmt->errorInfo()));
    file_put_contents(__DIR__.'/debug_model_error.txt', implode(' | ', $stmt->errorInfo()) . PHP_EOL, FILE_APPEND);

    return null;
  }
}
