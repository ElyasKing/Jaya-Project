<?php
include("../application_config/db_class.php");
include("./home/navigation/header.php");
session_start();
?>

<!DOCTYPE html>
<html>

<head>
	<?php
	include("./home/navigation/navbar.php");
	?>
</head>

<body>
	<nav class="navbar navbar-dark navbar-expand-lg bg-body-tertiary">
		<div class="container-fluid">
			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarTogglerDemo01">
				<!-- <a class="navbar-brand" href="login.php"><img src="<?php echo $base_url; ?>/images/JAYA-LOGO2.png" alt="logo_jaya" width="180"></a> -->
				<ul class="navbar-nav me-auto mb-2 mb-lg-0">
				</ul>
			</div>
		</div>
	</nav>
	<br><br><br>
	<div class="container">
		<form method="post" action="loginCheckLogin.php">
			<div class="row d-flex justify-content-center">
				<div class="col-12 col-md-8 col-lg-6 col-xl-5">
					<div class="card shadow-2-strong css-login">
						<div class="card-body p-5 text-center">
							<img src="../images/login.svg" alt="login_icon" width="100"><br><br>
							<div class="input-group form-outline mb-4">
								<!-- <label class="form-label" for="email">Identifiant :</label> -->
								<span class="input-group-text"><i class="bi bi-person-fill"></i></span>
								<input type="email" id="email" class="form-control" name="email" placeholder="e-mail@mail.com">
							</div>
							<div class="input-group form-outline mb-4">
								<span class="input-group-text"><i class="bi bi-key-fill"></i></span>
								<input type="password" id="password" class="form-control" name="password" placeholder="mot de passe">
							</div>
							<input type="submit" class="btn me-md-3 bg" value="Se connecter">
							<!-- <hr class="my-4"> -->
							<br><br>
							<p class="form-label"><a href="reset_password.php">Mot de passe oublié ?</a></p>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
</body>

</html>

<script src="../js/toastr.min.js"></script>
<script>
	toastr.options = {
		"closeButton": true,
		"debug": false,
		"newestOnTop": false,
		"progressBar": true,
		"positionClass": "toast-top-center",
		"preventDuplicates": false,
		"onclick": null,
		"showDuration": "300",
		"hideDuration": "1000",
		"timeOut": "7000",
		"extendedTimeOut": "1000",
		"showEasing": "swing",
		"hideEasing": "linear",
		"showMethod": "fadeIn",
		"hideMethod": "fadeOut"
	}
</script>
<?php
if (isset($_SESSION['success'])) {
	$login_error = $_SESSION['success'];

	switch ($login_error) {
		case 1:
			echo '<script>toastr.error("Identifiant ou mot de passe incorrect !");</script>';
			break;
		case 2:
			echo '<script>toastr.warning("Vous n\'êtes pas habilité à accèder à cette application. Veuillez vous identifier.");</script>';
			break;
		default:
			// rien
	}
	$_SESSION['success'] = 0;
}
?>