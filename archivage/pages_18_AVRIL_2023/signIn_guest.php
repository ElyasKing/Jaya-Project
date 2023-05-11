<?php
include "../application_config/db_class.php";
$conn = Database::connect();
session_start();
?>

<!DOCTYPE html>
<html>
<head>
	<title>Login Invités</title>
	<link rel="stylesheet" type="text/css" href="../css/style.css" />
</head>
<body>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $fullname = $_POST["fullname"];
    $company = $_POST["company"];

    // Requête SQL pour récupérer les informations de l'utilisateur correspondant à l'email fourni
    $query =
        "SELECT * FROM invite WHERE Nom_Invite = '" . $fullname . "' AND Entreprise_Invite = '" . $company . "'";
    $result = $conn->query($query);
    $user = $result->fetch();

    if ($user) {
        header("Location: soutenance_page.php");
        exit();
    } else {
        // INSERT INTO
        header("Location: soutenance_page.php");
    }
}
?>

	<form method="post">
		<h2>Inscription</h2>
		<label for="fullname">Nom prénom :</label>
		<input type="text" id="fullname" name="fullname" required>
		<label for="company">Mot de passe :</label>
		<input type="text" id="company" name="company" required>
		<input type="submit" value="S'inscrire">
	</form>

</body>
</html>