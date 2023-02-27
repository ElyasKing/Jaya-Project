<?php
include "../application_config/db_class.php";
$conn = Database::connect();
?>

<!DOCTYPE html>
<html>
<head>
	<title>Login</title>
	<link rel="stylesheet" type="text/css" href="../styles.css" />
</head>
<body>

<?php 
 if ($_SERVER["REQUEST_METHOD"] == "POST") {
     // Récupérer les données du formulaire
     $email = $_POST["email"];
     $password = $_POST["password"];

     // Requête SQL pour récupérer les informations de l'utilisateur correspondant à l'email fourni
     $query =
         "SELECT ID_UTILISATEUR, MDP_Utilisateur FROM utilisateur WHERE Mail_Utilisateur = '" .
         $email .
         "'";
     $result = $conn->query($query);
     $user = $result->fetch();

     if ($user) {
         // Vérifier si l'utilisateur existe
         // Vérifier si le mot de passe est correct
         if ($password == $user["MDP_Utilisateur"]) {
            session_start();
            $_SESSION["user_id"] = $user["ID_UTILISATEUR"]; // Ajout dans la session l'ID
             // Authentification réussie, rediriger l'utilisateur vers une page protégée
             header("Location: manage_account.php");
             exit();
         } else {
             // Mot de passe incorrect
             $error_message = "Mot de passe incorrect";
             echo $error_message;
         }
     } else {
         // Utilisateur non trouvé
         $error_message = "Aucun utilisateur trouvé avec cet email";
         echo $error_message;
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