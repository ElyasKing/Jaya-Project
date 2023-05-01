<?php
include("../../../application_config/db_class.php");
include("../../../fonctions/functions.php");
session_start();

if(!isConnectedUser()){
    $_SESSION['success'] = 2;
    header("Location: login.php");
}

$db = Database::connect();

$administrateur = $responsableUE = $scolarite = $tuteurUniversitaire = $etudiant = $id = $mdp = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $administrateur = isset($_POST['administrateur']) ? 'oui' : 'non';
    $responsableUE = isset($_POST['responsableUE']) ? 'oui' : 'non';
    $scolarite = isset($_POST['scolarite']) ? 'oui' : 'non';
    $tuteurUniversitaire = isset($_POST['tuteurUniversitaire']) ? 'oui' : 'non';
    $etudiant = isset($_POST['etudiant']) ? 'oui' : 'non';

    $mdp = isset($_POST['pw1']) ? $_POST['pw1'] : '';

    $mdp_hash = password_hash($mdp, PASSWORD_BCRYPT);

    if ($mdp <> "") {
        $query = 'UPDATE utilisateur SET MDP_Utilisateur="' . $mdp_hash . '" WHERE ID_Utilisateur = "' . $id . '"';
        $result = $db->query($query);
    }

    $query = 'UPDATE habilitations SET 
        Admin_Habilitations = "' . $administrateur . '", 
        ResponsableUE_Habilitations = "' . $responsableUE . '", 
        Scolarite_Habilitations = "' . $scolarite . '", 
        TuteurUniversitaire_Habilitations= "' . $tuteurUniversitaire .  '", 
        Etudiant_Habilitations= "' . $etudiant . '" 
    WHERE ID_Utilisateur = "' . $id . '"';
    
    $result = $db->query($query);

    $_SESSION['success'] = 1;

    header('Location: accountManager_administrateur.php');
}
