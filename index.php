<?php
 include("application_config/db_class.php");
?>

<html>
 <head>
  <title>PHP-Test</title>
 </head>
 <body>
  <?php 
  
  $db = Database::connect();

    $query = "select * from utilisateur";
    $statement = $db->query($query);
    $test = $statement->fetch();

    var_dump($test);

 $db = Database::disconnect();

//ratio
  ?>
 </body>
</html>