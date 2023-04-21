<?php
include("../application_config/db_class.php");
include("header.php");



if (isset($_SESSION['flag']) && $_SESSION['flag'] == 1) {
	$login_error = $_SESSION['flag'];

	if ($login_error == 1) {
		echo "<script>  alert(\"erreur\"); </script>";
	}
	$_SESSION['flag'] = 0;
}
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Mot de passe oublié</title>
    <link rel="stylesheet" type="text/css" href="./styles.css" />
  </head>
  <body>
    <div class="container bg-light p-3">
      <form method="post" action="reset_password_action.php">
        <h2>Mot de passe oublié</h2>
        <label for="email">Entrez votre adresse e-mail :</label>
        <input type="email" id="email" name="email" required>
        <input type="submit" value="Réinitialiser le mot de passe">
      </form>
    </div>
  </body>
</html>