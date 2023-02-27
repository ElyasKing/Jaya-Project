<?php
include "../application_config/db_class.php";
$conn = Database::connect();
if(isset($_SESSION['flag']) && $_SESSION['flag']==1){
    $login_error=$_SESSION['flag'];

    if($login_error == 1){
        echo "<script>  alert(\"erreur\"); </script>";
    }
    $_SESSION['flag'] = 0;
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Login</title>
	<link rel="stylesheet" type="text/css" href="./styles.css" />
</head>
<body>

	<form method="post" action="check_login.php">
		<h2>Connexion</h2>
		<label for="email">Email :</label>
		<input type="text" id="email" name="email" required>
		<label for="password">Mot de passe :</label>
		<input type="password" id="password" name="password" required>
		<input type="submit" value="Se connecter">
	</form>

</body>
</html>