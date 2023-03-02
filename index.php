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
      <h1>Liste des pages disponibles - cliquez sur login.php</h1>
      <ul>
        <?php
        $dir = "./pages";
        $files = scandir($dir);
        foreach ($files as $file) {
          if (pathinfo($file, PATHINFO_EXTENSION) == "php") {
            echo "<li><a href=./pages/$file>$file</a></li>";
          }
        }
        ?>

      </ul>
    </div>
  </div>
</body>

</html>