<?php
include "../application_config/db_class.php";
$conn = Database::connect();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Modification d'un étudiant</title>
</head>

<body <?php

$id = $_GET['id'];
$query = "SELECT utilisateur.ID_Utilisateur, utilisateur.nom_Utilisateur, utilisateur.Mail_Utilisateur, utilisateur.Promo_Utilisateur, utilisateur.HuitClos_Utilisateur,
invite.Entreprise_Invite, invite.Ville_Invite, invite.Nom_Invite, invite.Mail_Invite FROM utilisateur 
JOIN est_apprenti ON utilisateur.ID_Utilisateur = est_apprenti.ID_Utilisateur 
 JOIN invite ON est_apprenti.ID_Invite = invite.ID_Invite 
 JOIN habilitations ON utilisateur.ID_Utilisateur = habilitations.ID_Utilisateur 
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

      ?> <h1>Modifier les informations d'un étudiant</h1>

  <div class="container bg-light p-3">

    <form action="updateEtudiant.php?id=<?php echo $id?>" method="post">
      
      <input type="hidden" class="form-control" name="id" value="<?= $etudiant['ID_Utilisateur'] ?>">
      <input type="hidden" class="form-control" name="idTuteur" value="<?= $tuteur['ID_Utilisateur'] ?>">

      <div class="mb-3">
        <label for="nomEtudiant" class="form-label">Nom de l'étudiant</label>
        <input type="text" class="form-control" name="nomEtudiant" value="<?= $etudiant['nom_Utilisateur'] ?>">
      </div>
      <div class="mb-3">
        <label for="emailEtudiant" class="form-label">Email de l'étudiant</label>
        <input type="text" class="form-control" name="emailEtudiant" value="<?= $etudiant['Mail_Utilisateur'] ?>">
      </div>
      <div class="mb-3">
        <label for="promo" class="form-label">Promo de l'étudiant</label>
        <input type="text" class="form-control" name="promo" value="<?= $etudiant['Promo_Utilisateur'] ?>">
      </div>
      <div class="mb-3">
        <label for="entreprise" class="form-label">Entreprise de l'étudiant</label>
        <input type="text" class="form-control" name="entreprise" value="<?= $etudiant['Entreprise_Invite'] ?>">
      </div>
      <div class="mb-3">
        <label for="ville" class="form-label">Ville</label>
        <input type="text" class="form-control" name="ville" value="<?= $etudiant['Ville_Invite'] ?>">
      </div>
      <?php
      foreach ($mas as $ma) {
       
      ?>
        <div class="mb-3">
          <label for="nomMA<? $compteur ?>" class="form-label">Maitre d'apprentissage <? $compteur ?></label>
          <input type="text" class="form-control" name="nomma-<?php echo $ma['ID_Invite']?>" value="<?= $ma['Nom_Invite'] ?>">
        </div>
        <div class="mb-3">
          <label for="emailMA<? $compteur ?>" class="form-label">Email maitre d'apprentissage <? $compteur ?></label>
          <input type="text" class="form-control" name="emailma-<?php echo $ma['ID_Invite'] ?>" value="<?= $ma['Mail_Invite'] ?>">
        </div>

      <?php
       
      }
?>

      <div class="mb-3">
        <label for="nomTuteur" class="form-label">Nom du tuteur</label>
        <input type="text" class="form-control" name="nomTuteur" value="<?= $tuteur['nom_Utilisateur'] ?>">
      </div>
      <div class="mb-3">
        <label for="emailTuteur" class="form-label">Email du tuteur</label>
        <input type="text" class="form-control" name="emailTuteur" value="<?= $etudiant['Mail_Utilisateur'] ?>">
      </div>
      <div class="mb-3">
        <label for="huitClos" class="form-label">Huit-Clos</label>
        <input type="checkbox" class="form-control" name="huitClos" value="<?= $etudiant['HuitClos_Utilisateur'] ?>">
      </div>

      <button class="btn btn-info" type="submit">Modifier</button>

    </form>

  </div>

</body>

</html>