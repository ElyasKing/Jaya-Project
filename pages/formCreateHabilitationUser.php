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

<body>

 <div class="container bg-light p-3">


  <h1>Créer un compte</h1>

<form action="createHabilitationsUser.php" method="post">
  <input type="hidden" class="form-control" name="id" value="<?= $id ?>">

  <div class="mb-3">
    <label for="nomUtilisateur" class="form-label">Utilisateur : </label>
    <input type="text" class="form-control" name="user" id="user">
  </div>

  <div class="mb-3">
    <label for="mdp" class="form-label">Mot de passe : </label>
    <input type="password" class="form-control" name="mdp" id="mdp">
  </div>

  <div class="mb-3">
    <label for="admin" class="form-label">Administrateur</label>
    <div class="form-check form-switch">
      <input class="form-check-input" type="checkbox" name="admin" id="admin">
    </div>
  </div>

  <div class="mb-3">
    <label for="responsableUE" class="form-label">Responsable d'UE</label>
    <div class="form-check form-switch">
      <input class="form-check-input" type="checkbox" name="responsableUE" id="responsableUE">
    </div>
  </div>

  <div class="mb-3">
    <label for="scolarite" class="form-label">Scolarité</label>
    <div class="form-check form-switch">
      <input class="form-check-input" type="checkbox" name="scolarite" id="scolarite">
    </div>
  </div>

  <div class="mb-3">
    <label for="tuteurUniversitaire" class="form-label">Tuteur universitaire</label>
    <div class="form-check form-switch">
      <input class="form-check-input" type="checkbox" name="tuteurUniversitaire" id="tuteurUniversitaire" >
    </div>
  </div>

  <div class="mb-3">
    <label for="etudiant" class="form-label">Étudiant</label>
    <div class="form-check form-switch">
      <input class="form-check-input" type="checkbox" name="etudiant" id="etudiant" >
    </div>
  </div>

  <button type="submit" class="btn btn-info" id="enregistrerBtn" disabled>Enregistrer</button>

</form>

</div>

</body>
</html>

  <script>
  const user = document.getElementById('user');
  const mdp = document.getElementById('mdp');
  const enregistrerBtn = document.getElementById('enregistrerBtn');

  function toggleEnregistrerBtn() {
    if (user.value && mdp.value) {
      enregistrerBtn.disabled = false;
    } else {
      enregistrerBtn.disabled = true;
    }
  }

  user.addEventListener('input', toggleEnregistrerBtn);
  mdp.addEventListener('input', toggleEnregistrerBtn);
</script>
