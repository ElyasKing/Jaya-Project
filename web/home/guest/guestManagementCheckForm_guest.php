<?php
include("../../../application_config/db_class.php");
include("../../../fonctions/functions.php");
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $professionnel = isset($_POST['est_professionnel']) ? 'oui' : 'non';
    $enseignant = isset($_POST['est_enseignant']) ? 'oui' : 'non';
    $mail = $_POST['email'];
    $user = $_POST['nom'];
    $entreprise = $_POST['entreprise'];
    $villeEntreprise = $_POST['villeEntreprise'];
    $tel = $_POST['tel'];

    $db = Database::connect();

    $query = "SELECT * FROM `invite` WHERE Nom_Invite = '". $user ."' AND Mail_Invite = '". $mail ."' AND Entreprise_Invite = '". str_replace("'", "''",$entreprise) ."' AND Ville_Invite = '". str_replace("'", "''",$villeEntreprise) ."' AND Telephone_Invite = '". $tel ."'";
    $statement = $db->query($query);
    $result = $statement->fetch();

    if(empty($result[0])){

        $query = "INSERT INTO invite(Nom_Invite, Mail_Invite, Entreprise_Invite, Ville_Invite, Telephone_Invite, EstEnseignant_Invite, EstProfessionel_Invite) VALUES
        ('" . $user . "','" . $mail . "','" . str_replace("'", "''", $entreprise) . "','" . str_replace("'", "''", $villeEntreprise) . "', '" . $tel . "','" . $enseignant . "','" . $professionnel . "');";
        $result = $db->query($query);

        // Récupération de l'ID de la personne créée
        $newId = $db->lastInsertId();

        $url = '../soutenances/tuteurU/tuteurUniversitaire.php?id=' . $newId . '&nom=' . $user . '&entreprise=' . $entreprise;
        header('Location: ' . $url);
        exit();
    }else{
        header('Location: NewGuestForm.php');
        $_SESSION['success'] = 1;
    }
}
