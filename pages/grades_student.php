<?php
include "../application_config/db_class.php";
$conn = Database::connect();
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    var_dump($_POST);

    $total = 0;
    $listparam = $conn->query("SELECT nom_param, nbpoint_param from parametres")->fetchAll();
    foreach ($listparam as $param) {
        if (isset($_POST[$param['nom_param']]))
        $total = $total + $_POST[$param['nom_param']];
        echo $_POST[$param['nom_param']];
        echo $total;
    
    }
echo $total;


    $etud_ID = $conn->query("SELECT ID_Utilisateur from utilisateur where Nom_Utilisateur='".$_POST["liste-noms"]."'")->fetch()['ID_Utilisateur'];
    $commentaire = $_POST["commentaire"];

    //requete pour mettre à jour informations de l'utilisateur 
    $query = "INSERT INTO notes_soutenance (`NoteFinale_NS`, `Commentaire_NS`, `ID_Utilisateur`, `ID_Invite`) VALUES ( '".$total."', '".$commentaire."', '". $etud_ID."', '".$_SESSION['user_id']."')";
    $result = $conn->query($query);

    header("Location: grades_student.php");
    exit(); 
}

// Requête SQL pour récupérer les informations de l'utilisateur correspondant à l'email fourni
$query = "SELECT * FROM utilisateur WHERE ID_Utilisateur = ".$_SESSION['user_id'];
$result = $conn->query($query);
$user = $result->fetch();

// Requête SQL pour récupérer les informations de tous les étudiants sauf celui connecté
$query2 = "SELECT Nom_Utilisateur FROM utilisateur WHERE ID_Utilisateur !=  ".$_SESSION['user_id'];
$result2 = $conn->query($query2);
$listEtud = $result2->fetchAll();

// Requête SQL pour récupérer les informations des paramètres
$listparam = $conn->query("SELECT nom_param, nbpoint_param from parametres")->fetchAll();

if ($user) {
?>
<!DOCTYPE html>
<html>
<head>
    <title>Noter un étudiant</title>
    <link rel="stylesheet" type="text/css" href="./styles.css" />
</head>
<body>
    <form method="post">
        <h2>Noter un étudiant</h2>
        <br>
        <label for="nom">Nom :</label>
        <input type="text" id="nom" name="nom" value="<?php print($user['Nom_Utilisateur']) ?>" disabled>
        <br>
        <label for="nom_etud">Nom de l'étudiant :</label>
        <select name="liste-noms">
            <?php foreach ($listEtud as $row) { ?>
                <option value="<?php echo $row['Nom_Utilisateur']; ?>"><?php echo $row['Nom_Utilisateur']; ?></option>
            <?php } ?>
        </select>
        <br>
        <?php foreach ($listparam as $param) { ?>
            <label for="<?php echo $param['nom_param']; ?>"><?php echo $param['nom_param']; ?>:</label>
            <input type="number" id="<?php echo $param['nom_param']; ?>" name="<?php echo $param['nom_param']; ?>" min="0" max="<?php echo $param['nbpoint_param']; ?>" value="<?php echo $param['nbpoint_param']; ?>">
            <br>
        <?php } ?>
        <br>
        <label for="commentaire">Commentaire :</label>
        <input type="text" id="commentaire" name="commentaire">
        <br>
        <br>
        <input type="submit" value="Confirmer">
</form>

         <?php
     } else {
         // Utilisateur non trouvé
         $error_message = "Aucun utilisateur trouvé";
         echo $error_message;
     }
?>





</body>
</html>