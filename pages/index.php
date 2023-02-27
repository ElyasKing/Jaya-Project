<?php
include("../application_config/db_class.php");
include("../application_config/get_connectUser.php");
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
      // *************** TEST ****************
      // $db = Database::connect();

      //   $query = "select * from essai";
      //   $statement = $db->query($query);
      //   $test = $statement->fetch();

      //   echo "index ok <br><br>";

      //   var_dump($test);

      // $db = Database::disconnect();
      // *************** TEST ****************


      // switch($_SESSION['profilUser']){
      //   case "Administrateur":  // Si profil detecté dans get_connectUser = administrateur
      //     include("administrateur/indexAdmin.php");
      //     break;
      //   case "Scolarite":
      //     include("scolarite/indexScolarite.php"); // Si profil detecté dans get_connectUser = administrateur
      //     break;
      //   default:
      //     include("default.php");
      // }

      include("indexAdmin.php");
      ?>
    </div>
  </div>
</body>

</html>
