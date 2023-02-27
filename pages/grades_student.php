<?php
include "../application_config/db_class.php";
$conn = Database::connect();
session_start();


// Requête SQL pour récupérer les informations de l'utilisateur correspondant à l'email fourni
$query = "SELECT * FROM utilisateur WHERE ID_Utilisateur = ".$_SESSION['user_id'];
$user = $conn->query($query)->fetch();

// Requête SQL pour récupérer les informations de tous les étudiants sauf celui connecté
$query = "SELECT Nom_Utilisateur FROM utilisateur WHERE ID_Utilisateur !=  ".$_SESSION['user_id'];
$listEtud = $conn->query($query)->fetchAll();

// Requête SQL pour récupérer les informations des paramètres
$query = "SELECT nom_param, nbpoint_param from parametres where nbpoint_param is not null";
$listparam = $conn->query($query)->fetchAll();

$conn = Database::disconnect();

$count = 0;
?>
<!DOCTYPE html>
<html>
<head>
    <title>Noter un étudiant</title>
    <link rel="stylesheet" type="text/css" href="./styles.css" />
</head>
<body>
    <form id="note_etud_oral" method="post" action="check_grades_student.php">
        <h2>Noter un étudiant</h2>
        <br>
        <!---personne attribuée à la session--->
        <label for="nom">Nom :</label>
        <input type="text" id="nom" name="nom" value="<?php echo($user['Nom_Utilisateur']) ?>" disabled>
        <br>
        <!---liste déroulante des étudiants--->
        <label for="nom_etud">Nom de l'étudiant :</label>
        <select name="liste-noms">
            <?php foreach ($listEtud as $row) { ?>
                <option value="<?php echo $row['Nom_Utilisateur']; ?>"><?php echo $row['Nom_Utilisateur']; ?></option>
            <?php } ?>
        </select>
        <br>
        <!---liste des notes affectées à la soutenance--->
        <?php foreach ($listparam as $param) { 
            $count = $count + 1; ?>
            <label for="<?php echo $param['nom_param']; ?>"><?php echo $param['nom_param']; ?>:</label>
            <input type="number" id=<?php echo $count; ?> name="<?php echo $param['nom_param']; ?>" min="0" max="<?php echo $param['nbpoint_param']; ?>" value="<?php echo $param['nbpoint_param']; ?>">
            <br>
        <?php } ?>
        <br>
        <!---commentaire--->
        <label for="commentaire">Commentaire :</label>
        <input type="text" id="commentaire" name="commentaire">
        <br>
        <br>
        <input type="submit" value="Confirmer">
</form>
</body>
</html>