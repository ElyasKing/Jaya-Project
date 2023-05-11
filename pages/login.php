<?php
include("../application_config/db_class.php");
include("./navigation/header.php");



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

<nav class="navbar navbar-dark navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
      <a class="navbar-brand" href="index.php"><img src="<?php echo $base_url; ?>images/JAYA-LOGO2.png" alt="logo_jaya" width="180"></a>
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
</nav>
<head>
	<title>Login</title>
	<link rel="stylesheet" type="text/css" href="../css/style.css" />
</head>
<body>
<div class="container bg-light p-3">
	<form method="post" action="check_login.php">
		<h2>Connexion</h2>
		<label for="email">Email :</label>
		<input type="text" id="email" name="email" required>
		<label for="password">Mot de passe :</label>
		<input type="password" id="password" name="password" required>
		<!-- <form method="post" action="#"> -->
			<!-- <label>Mot de passe oublié</label> -->
		<!-- </form> -->
		<input type="submit" value="Se connecter">
	</form>
</div>

</body>
</html>