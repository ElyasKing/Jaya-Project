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

//On récupère la dernière note attribuée
$query = "SELECT NoteFinale_NS FROM `notes_soutenance` WHERE ID_NS='5'";
$lastNote = $conn->query($query)->fetchColumn();


//On récupère la dernière note attribuée
$query = "SELECT Commentaire_NS FROM `notes_soutenance` WHERE ID_NS='5'";
$lastCommentaire= $conn->query($query)->fetchColumn();

// Requête SQL pour récupérer les informations des paramètres
$query = "SELECT nom_param, nbpoint_param from parametres where nbpoint_param is not null";
$listparam = $conn->query($query)->fetchAll();

$conn = Database::disconnect();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Noter un étudiant</title>
    <link rel="stylesheet" type="text/css" href="./styles.css" />
</head>
<body>
    <form id="modif_note_etud_oral" method="post" action="check_modifiy_student_grades.php">
        <h2>Noter un étudiant</h2>
        <br>
         <!---ID de la note--->
        <label for="id_note">ID note:</label>
        <input type="text" id="id_note" name="id_note" value="5" disabled>
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
        <?php 
        foreach ($listparam as $param) { 
            //traitement qui va remplacer les " " par des "_" avant le passet dans tes inputs?>
            <label for="<?php echo $param['nom_param']; ?>"><?php echo $param['nom_param']; ?>:</label>
            <input type="number" id="<?php echo str_replace(" ","_",$param['nom_param']); ?>" name="<?php echo str_replace(" ","_",$param['nom_param']); ?>" min="0" max="<?php echo $param['nbpoint_param']; ?>" value="<?php echo $param['nbpoint_param']; ?>">
            <?php echo $param['nom_param']; ?>
            <br>
        <?php } ?>
        <br>
         <!---Note finale--->
        <label for="note_finale">Note finale :</label>
        <input type="number" id="note_finale" name="note_finale" min ="0" max="20" value="<?php echo($lastNote) ?>">
        <br>
        <!---commentaire--->
        <label for="commentaire">Commentaire :</label>
        <input type="text" id="commentaire" name="commentaire" value="<?php echo($lastCommentaire) ?>">
        <br>
        <br>
        <input type="submit" value="Confirmer">
</form>
</body>
</html>
