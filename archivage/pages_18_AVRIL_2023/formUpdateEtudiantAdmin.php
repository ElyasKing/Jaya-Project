<?php
session_start();
include "../application_config/db_class.php";
include 'header.php';
include 'navbar.php';
$conn = Database::connect();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Modification d'un étudiant</title>


  <style>
  .form-control {
    width: 100%;
    padding: 0.375rem 0.75rem;
    font-size: 1rem;
    line-height: 1.5;
    color: #495057;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
  }

  .form-label {
    font-weight: bold;
  }

  .form-align {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
  }

  .form-group {
    flex: 0 0 50%;
  }

  .form-ville {
    width: 100%;
  }

  .btn-info {
    color: #fff;
    background-color: #17a2b8;
    border-color: #17a2b8;
  }

  .btn-info:hover {
    background-color: #138496;
    border-color: #128293;
  }

  .form-check-input {
    position: relative;
    flex-shrink: 0;
    height: 1.5rem;
    width: 1.5rem;
    margin-top: 0.3rem;
    margin-bottom: 0.3rem;
    margin-right: 0.5rem;
    cursor: pointer;
    color: #17a2b8;
  }

  .form-check-input:checked {
    background-color: #17a2b8;
    border-color: #17a2b8;
  }

  .form-check-input:checked:before {
    position: absolute;
    top: 0.125rem;
    left: 0.5rem;
    display: block;
    width: 0.75rem;
    height: 0.375rem;
    pointer-events: none;
    content: "";
    background-color: #fff;
    border-radius: 0.1rem;
    transform: rotate(45deg);
    border: 1px solid #17a2b8;
    border-top: 0;
    border-left: 0;
  }
</style>
</head>

<body> <?php

        $id = $_GET['id'];
        $query = "SELECT utilisateur.ID_Utilisateur, utilisateur.nom_Utilisateur, utilisateur.Mail_Utilisateur, utilisateur.Promo_Utilisateur, utilisateur.HuisClos_Utilisateur,
invite.Entreprise_Invite, invite.Ville_Invite, invite.Nom_Invite, invite.Mail_Invite FROM utilisateur 
LEFT JOIN est_apprenti ON utilisateur.ID_Utilisateur = est_apprenti.ID_Utilisateur 
 LEFT JOIN invite ON est_apprenti.ID_Invite = invite.ID_Invite 
 LEFT JOIN habilitations ON utilisateur.ID_Utilisateur = habilitations.ID_Utilisateur 
WHERE utilisateur.ID_Utilisateur = $id";

        $result = $conn->query($query);
        $etudiant = $result->fetch();

        $queryMA = "SELECT invite.ID_Invite, invite.Nom_Invite, invite.Mail_Invite FROM invite 
			 JOIN est_apprenti ON invite.ID_Invite = est_apprenti.ID_Invite
			WHERE est_apprenti.Id_Utilisateur  LIKE  $id ";
        $resultatMA = $conn->query($queryMA);
        $mas = $resultatMA->fetchAll();

        $queryTuteur = "SELECT utilisateur.ID_Utilisateur, utilisateur.nom_Utilisateur, utilisateur.Mail_Utilisateur FROM utilisateur 
			JOIN etudiant_tuteur ON utilisateur.ID_Utilisateur = etudiant_tuteur.ID_Tuteur
			WHERE etudiant_tuteur.Id_etudiant  LIKE  $id ";
        $resultat = $conn->query($queryTuteur);
        $tuteur = $resultat->fetch();

        if ($tuteur == false) {
          $tuteurNom = "";
          $tuteurMail = "";
          $tuteurId = null;
        } else {
          $tuteurNom = $tuteur['nom_Utilisateur'];
          $tuteurMail = $tuteur['Mail_Utilisateur'];
          $tuteurId = $tuteur['ID_Utilisateur'];
        }

        ?>

 <div class="container bg-light p-3">
  <h1>Modifier les informations d'un étudiant</h1>
  <form action="updateEtudiantAdmin.php" method="post">
    <div class="row">
      <div class="col-md-6 mb-3">
        <label for="nomEtudiant" class="form-label">Nom de l'étudiant</label>
        <input type="text" class="form-control" name="nomEtudiant" value="<?= $etudiant['nom_Utilisateur'] ?>">
      </div>
      <div class="col-md-6 mb-3">
        <label for="emailEtudiant" class="form-label">Email de l'étudiant</label>
        <input type="email" class="form-control" name="emailEtudiant" value="<?= $etudiant['Mail_Utilisateur'] ?>">
      </div>
      <div class="col-md-6 mb-3">
        <label for="promo" class="form-label">Promo de l'étudiant</label>
        <input type="text" class="form-control" name="promo" value="<?= $etudiant['Promo_Utilisateur'] ?>">
      </div>
      <div class="col-md-6 mb-3">
        <label for="entreprise" class="form-label">Entreprise de l'étudiant</label>
        <input type="text" class="form-control" name="entreprise" value="<?= $etudiant['Entreprise_Invite'] ?>">
      </div>
      <div class="col-md-6 mb-3">
        <label for="ville" class="form-label">Ville entreprise</label>
        <input type="text" class="form-control" name="ville" value="<?= $etudiant['Ville_Invite'] ?>">
      </div>
      <?php
      foreach ($mas as $ma) {
      ?>
        <div class="col-md-6 mb-3">
          <input type="hidden" class="form-control" name="idMA" value="<?= $ma['ID_Invite'] ?>">
          <label for="nomMA<? $compteur ?>" class="form-label">Maitre d'apprentissage <? $compteur ?></label>
          <input type="text" class="form-control" name="nomma[]" value="<?= $ma['Nom_Invite'] ?>">
        </div>
        <div class="col-md-6 mb-3">
          <label for="emailMA<? $compteur ?>" class="form-label">Email maitre d'apprentissage <? $compteur ?></label>
          <input type="email" class="form-control" name="emailma[]" value="<?= $ma['Mail_Invite'] ?>">
        </div>
      <?php
      }
      ?>
      <div class="col-md-6 mb-3">
        <label for="nomTuteur" class="form-label">Nom du tuteur</label>
        <input type="text" class="form-control" name="nomTuteur" value="<?= $tuteurNom ?>">
      </div>
      <div class="col-md-6 mb-3">
        <label for="emailTuteur" class="form-label">Email du tuteur</label>
        <input type="email" class="form-control" name="emailTuteur" value="<?= $tuteurMail ?>">
      </div>
      <div class="col-md-12 mb-3">
        <label for="huitClos" class="form-label">Huit-Clos</label>
        <input type="checkbox" class="form-check-input" name="huitClos" value="<?= $etudiant['HuisClos_Utilisateur'] ?>">
      </div>
      <div class="col-md-12 mb-3">
        <button class="btn btn-info" type="submit">Modifier</button>
      </div>
    </div>
    <input type="hidden" class="form-control" name="id" value="<?= $etudiant['ID_Utilisateur'] ?>">
    <input type="hidden" class="form-control" name="idTuteur" value="<?= $tuteurId ?>">
  </form>
</div>


</body>

</html>