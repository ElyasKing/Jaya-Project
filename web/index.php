<?php
include("../application_config/db_class.php");
include("../fonctions/functions.php");
session_start();

if($_SERVER['REQUEST_METHOD'] == 'POST'){
  if(!empty($_POST['changeProfile'])){
    $_SESSION['active_profile'] = $_POST['changeProfile'];
  }
}
?>

<!DOCTYPE html>
<html>

<head>
  <?php
  include("header.php");

  ?>
</head>

<body>
  <div class="content">
    <div class="bar">
      <span class="sphere"></span>
    </div>
    <div id="content">
      <?php
      include("navbar.php");
      switch($_SESSION['active_profile']){
        case "ADMINISTRATEUR":  // Si profil detecté dans get_connectUser = administrateur
          include("index_administrateur.php");
          break;
        case "RESPONSABLE UE":
          include("index_responsableUE.php"); // Si profil detecté dans get_connectUser = administrateur
          break;
        case "SCOLARITE":
          include("index_scolarite.php"); // Si profil detecté dans get_connectUser = administrateur
          break;
        case "TUTEUR UNIVERSITAIRE":
          include("index_tuteurUniversitaire.php"); // Si profil detecté dans get_connectUser = administrateur
          break;
        case "ETUDIANT":
          include("index_etudiant.php"); // Si profil detecté dans get_connectUser = administrateur
          break;
        default:
          echo "Vous n'&ecirc;tes pas habilit&eacute; &agrave; acc&eacute;der &agrave; cette application.";
          exit;
      }

      ?>
    </div>
  </div>
</body>

</html>