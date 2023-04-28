<?php
include("../application_config/db_class.php");
include("../fonctions/functions.php");
session_start();

if (!isConnectedUser()) {
    $_SESSION['success'] = 2;
    header("Location: login.php");
}



if ($_SERVER["REQUEST_METHOD"] == "POST") {

 
    $professionnel = isset($_POST['est_professionnel']) ? 'oui' : 'non';
    $enseignant = isset($_POST['est_enseignant']) ? 'oui' : 'non';
    $mail = $_POST['email'];
    $user = $_POST['nom'];
    $entreprise = $_POST['entreprise'];
    $tel= $_POST['tel'];
    $ID = $_POST['id'];

    $db = Database::connect();

    $query = "UPDATE invite SET Nom_Invite='".$user."',Mail_Invite='".$mail."',Entreprise_Invite='".$entreprise."',Telephone_Invite='".$tel."',
    EstEnseignant_Invite='".$enseignant."',EstProfessionel_Invite='".$professionnel."' WHERE `ID_Invite`=".$ID.";";
    $result = $db->query($query);

    $_SESSION['success'] = 1;
    header('Location: guestManagement_scolarite.php');
}
