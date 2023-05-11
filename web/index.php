<?php
include("../application_config/db_class.php");
include("../fonctions/functions.php");
session_start();

if(!isConnectedUser()){
    $_SESSION['success'] = 2;
    header("Location: login.php");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (!empty($_POST['changeProfile'])) {
    $_SESSION['active_profile'] = $_POST['changeProfile'];
  }
}
?>

<!DOCTYPE html>
<html>

<head>
  <?php
  include("home/navigation/header.php");
  ?>
</head>

<body>
  <div class="content">
    <div class="bar">
      <span class="sphere"></span>
    </div>
    <div id="content">
      <?php
      include("home/navigation/navbar.php");
      switch ($_SESSION['active_profile']) {
        case "ADMINISTRATEUR":  // Si profil detecté dans get_connectUser = administrateur
          include("./home/index/index_administrateur.php");
          break;
        case "RESPONSABLE UE":
          include("./home/index/index_responsableUE.php"); // Si profil detecté dans get_connectUser = administrateur
          break;
        case "SCOLARITE":
          include("./home/index/index_scolarite.php"); // Si profil detecté dans get_connectUser = administrateur
          break;
        case "TUTEUR UNIVERSITAIRE":
          include("./home/index/index_tuteurUniversitaire.php"); // Si profil detecté dans get_connectUser = administrateur
          break;
        case "ETUDIANT":
          include("./home/index/index_etudiant.php"); // Si profil detecté dans get_connectUser = administrateur
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

<script>sessionStorage.setItem('btnConfigureHidden', 'true');</script>