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

</head>

<body> <?php

        $id = $_GET['id'];
        $query = "SELECT H.Id_Utilisateur, U.Nom_Utilisateur, '****' AS MDP_Utilisateur, H.Admin_Habilitations, H.ResponsableUE_Habilitations, H.Scolarite_Habilitations, 
H.TuteurUniversitaire_Habilitations, H.Etudiant_Habilitations 
FROM habilitations H 
JOIN utilisateur U 
ON U.Id_Utilisateur = H.Id_Utilisateur
WHERE H.ID_Utilisateur = $id";

 $result = $conn->query($query);
 $user = $result->fetch();

$adminAutorisation = $user['Admin_Habilitations'] == 'oui';
$responsableUEAutorisation = $user['ResponsableUE_Habilitations'] == 'oui';
$scolariteAutorisation = $user['Scolarite_Habilitations'] == 'oui';
$tuteurUniversitaireAutorisation = $user['TuteurUniversitaire_Habilitations'] == 'oui';
$etudiantAutorisation = $user['Etudiant_Habilitations'] == 'oui';

?>

  <div class="container bg-light p-3">

    <h1>Modifier un compte</h1>

    <form action="updateHabilitations.php" method="post">

      <div class="mb-3">
        <label for="nomUtilisateur" class="form-label">Utilisateur : </label>
        <p class="form-control-plaintext"><?= $user['Nom_Utilisateur'] ?></p>
    </div>

      <div class="mb-3">
        <label for="mdpUtilisateur" class="form-label">Mot de passe : </label>
        <input type="email" class="form-control" name="mdpUtilisateur" value="<?= $user['MDP_Utilisateur'] ?>">
      </div>

    <div class="mb-3">
        <label for="admin" class="form-label">Administrateur</label>
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" name="admin" id="admin" <?= $adminAutorisation ? 'checked' : '' ?>>
        </div>
    </div>


       <div class="mb-3">
        <label for="responsableUE" class="form-label">Responsable d'UE</label>
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" name="responsableUE" id="responsableUE" <?= $responsableUEAutorisation ? 'checked' : '' ?>>
        </div>
    </div>


       <div class="mb-3">
            <label for="scolarite" class="form-label">Scolarité</label>
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" name="scolarite" id="scolarite" <?= $scolariteAutorisation ? 'checked' : '' ?>>
            </div>
        </div>


        <div class="mb-3">
            <label for="tuteurUniversitaire" class="form-label">Tuteur universitaire</label>
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" name="tuteurUniversitaire" id="tuteurUniversitaire" <?= $tuteurUniversitaireAutorisation ? 'checked' : '' ?>>
            </div>
        </div>



        <div class="mb-3">
            <label for="etudiant" class="form-label">Étudiant</label>
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" name="etudiant" id="etudiant" <?= $etudiantAutorisation ? 'checked' : '' ?> >
             </div>
</div>


      <button class="btn btn-info" type="submit">Modifier</button>

      <a href="index_admin.php" class="btn btn-primary">Retour</a>

    </form>

  </div>


</body>

</html>