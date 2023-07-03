<?php
include "../../../../application_config/db_class.php";
include "../../../../fonctions/functions.php";

session_start();

if(!isConnectedUser()){
    $_SESSION['success'] = 2;
    header("Location: login.php");
}

$db = Database::connect();

$administrateur = $responsableUE = $scolarite = $tuteurUniversitaire = $etudiant = $mail = $user = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $administrateur = isset($_POST['administrateur']) ? 'oui' : 'non';
    $responsableUE = isset($_POST['responsableUE']) ? 'oui' : 'non';
    $scolarite = isset($_POST['scolarite']) ? 'oui' : 'non';
    $tuteurUniversitaire = isset($_POST['tuteurUniversitaire']) ? 'oui' : 'non';
    $etudiant = isset($_POST['etudiant']) ? 'oui' : 'non';
    $mail = $_POST['mail'];
    $user = $_POST['user'];
    $ndd =  $_SERVER['SERVER_NAME']."/web";
    //annee 
    $currentYear = date('Y');
    $nextYear = $currentYear + 1;
    $annee = $currentYear . '-' . $nextYear;

    // Génération d'un mot de passe aléatoire
    $mdp = generatePassword();

    $query = "SELECT count(*) FROM utilisateur WHERE Mail_Utilisateur LIKE '$mail'";
    $statement = $db->query($query);
    $countUser = $statement->fetch();

    $html = file_get_contents('../../../../fonctions/email_models/account_created.html');
    $search = array('{{email}}', '{{mdp}}', '{{identifiant}}', '{{ndd}}');
    $replace = array($mail, $mdp, $user, $ndd);
    $html = str_replace($search, $replace, $html);
    $subject = $user." vos identifiants sont là!";

    if ($countUser[0] > 0) {
        $_SESSION['success'] = 22;
        echo "KO";
        //header('Location: ../accountManager_administrateur.php');
    } else {
        // Insertion de l'utilisateur avec le mot de passe
        $query = "INSERT INTO utilisateur (Nom_Utilisateur, Mail_Utilisateur, MDP_Utilisateur, Annee_Utilisateur) VALUES ('$user', '$mail', '$mdp','$annee')";
        $db->query($query);
        $id_utilisateur = $db->lastInsertId();

        // Insertion des habilitations
        $query = "INSERT INTO 
        habilitations (
            ID_Utilisateur, 
            Admin_Habilitations, 
            ResponsableUE_Habilitations, 
            Scolarite_Habilitations, 
            TuteurUniversitaire_Habilitations, 
            Etudiant_Habilitations
        ) 
    VALUES ('$id_utilisateur', '$administrateur', '$responsableUE', '$scolarite', '$tuteurUniversitaire', '$etudiant')";
        $db->query($query);

        $_SESSION['success'] = 2;
        send_email($mail, $subject, $html);
        header('Location: ../accountManager_administrateur.php');
    }
}
