<?php

include 'header.php';
$connectedUser = "Wiki Jaya";
$userProfile = ".";
include 'navbar.php';



?>

<!DOCTYPE html>
<html>
<head>
	<title>Index des pages</title>
  <style>
		body {
			background-color: #333;
			color: #fff;
		}
	</style>
</head>
<body>
	<h1 style="">Liste des pages disponibles</h1>
	<ul>
		<?php
		$dir = ".";
		$files = scandir($dir);
		foreach ($files as $file) {
			if (pathinfo($file, PATHINFO_EXTENSION) == "php") {
				echo "<li><a href=\"$file\">$file</a></li>";
			}
		}
		?>
	</ul>
</body>
</html>