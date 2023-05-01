<?php
session_start();
include "../application_config/db_class.php";
include 'header.php';
include 'home/navigation/navbar.php';
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

    .container {
      margin-top : 5rem;
    }
    
    .switch {
  max-width: calc(100% + 1rem);
}

.switch > div {
  margin-right: 1rem;
}

@media (max-width: 991px) {
  .switch {
    flex-wrap: wrap;
    justify-content: space-between;
  }

  .switch > div {
    margin-right: 0;
    margin-bottom: 1rem;
    flex-basis: calc(33.33% - 1rem);
    max-width: calc(33.33% - 1rem);
  }
}

  </style>

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
    <label for="mail" class="form-label">Email : </label>
    <input type="text" class="form-control" name="mail" id="mail">
  </div>

   <div class="switch d-flex flex-wrap">
  <div class="mb-3 flex-grow-1">
    <label for="admin" class="form-label">Administrateur</label>
    <div class="form-check form-switch">
      <input class="form-check-input" type="checkbox" name="admin" id="admin">
    </div>
  </div>

  <div class="mb-3 flex-grow-1">
    <label for="responsableUE" class="form-label">Responsable d'UE</label>
    <div class="form-check form-switch">
      <input class="form-check-input" type="checkbox" name="responsableUE" id="responsableUE">
    </div>
  </div>

  <div class="mb-3 flex-grow-1">
    <label for="scolarite" class="form-label">Scolarité</label>
    <div class="form-check form-switch">
      <input class="form-check-input" type="checkbox" name="scolarite" id="scolarite">
    </div>
  </div>

  <div class="mb-3 flex-grow-1">
    <label for="tuteurUniversitaire" class="form-label">Tuteur universitaire</label>
    <div class="form-check form-switch">
      <input class="form-check-input" type="checkbox" name="tuteurUniversitaire" id="tuteurUniversitaire" >
    </div>
  </div>

  <div class="mb-3 flex-grow-1">
    <label for="etudiant" class="form-label">Étudiant</label>
    <div class="form-check form-switch">
      <input class="form-check-input" type="checkbox" name="etudiant" id="etudiant" >
    </div>
  </div>
</div>

  <button type="submit" class="btn btn-info" id="enregistrerBtn" disabled>Enregistrer</button>

</form>

</div>

</body>
</html>

  <script>
  const user = document.getElementById('user');
  const mail = document.getElementById('mail');
  const enregistrerBtn = document.getElementById('enregistrerBtn');

  function toggleEnregistrerBtn() {
    if (user.value && mail.value) {
      enregistrerBtn.disabled = false;
    } else {
      enregistrerBtn.disabled = true;
    }
  }

  user.addEventListener('input', toggleEnregistrerBtn);
  mail.addEventListener('input', toggleEnregistrerBtn);
</script>
