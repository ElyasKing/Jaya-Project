<?php
include ('../bdd-env.php');
$conn = mysqli_connect("localhost", "root", "elias123", "jaya");
?>

<!DOCTYPE html>
<html>
<head>
	<title>Login</title>
	<link rel="stylesheet" type="text/css" href="./styles.css" />
</head>
<body>

	<?php
		// Vérifier si le formulaire a été soumis sinon affiche une erreur
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			// Récupérer les données du formulaire
			$email = $_POST["email"];
			$password = $_POST["password"];

			// Requête SQL pour récupérer les informations de l'utilisateur correspondant à l'email fourni
			$query = "SELECT id, password FROM utilisateur WHERE email = '" . mysqli_real_escape_string($conn, $email) . "'";
			$result = mysqli_query($conn, $query);

			// Vérifier si l'utilisateur existe et si le mot de passe est correct
			if (mysqli_num_rows($result) == 1) {
				$user = mysqli_fetch_assoc($result);
				if (password_verify($password, $user["password"])) {
					// L'utilisateur est authentifié - rediriger vers la page d'accueil
					header("Location: index.php");
					exit();
				}
			}

		}
	?>

	<form method="post">
		<h2>Connexion</h2>
		<label for="email">Email :</label>
		<input type="text" id="email" name="email" required>
		<label for="password">Mot de passe :</label>
		<input type="password" id="password" name="password" required>
		<input type="submit" value="Se connecter">
	</form>
</body>
</html>