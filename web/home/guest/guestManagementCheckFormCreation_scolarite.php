<?php
include("../../../application_config/db_class.php");
include("../../../fonctions/functions.php");
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
    

    $db = Database::connect();

    $query = "INSERT INTO invite(Nom_Invite,Mail_Invite,Entreprise_Invite,Telephone_Invite,EstEnseignant_Invite,EstProfessionel_Invite) VALUES
    ('".$user. "','" . $mail. "','" . $entreprise . "','" . $tel. "','" . $enseignant . "','" . $professionnel."');";
    $result = $db->query($query);

    $_SESSION['success'] = 3;
    header('Location: guestManagement_scolarite.php');
}
