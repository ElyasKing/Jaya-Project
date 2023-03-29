<?php
session_start();
include "../application_config/db_class.php";
include 'header.php';
//include 'navbar.php';


$conn = Database::connect();


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notes de soutenances</title>
</head>

<body>

    <form action="saisieNoteSoutenanceTuteur.php" method="post">
        <p>Evaluateur : <php echo $_SESSION['user_name'] ?>
        </p>
        <label for="Etudiant" class="form-label">Selectionner l'étudiant</label>
        <textarea type="text" class="form-control" name="nom"></textarea>
        <div class="mt-3">
            <label for="point" class="form-label">Nombre de points</label>
            <input type="number" min="0.1" step="0.01" class="form-control" name="point">
        </div>
    </form>
</body>

</html>