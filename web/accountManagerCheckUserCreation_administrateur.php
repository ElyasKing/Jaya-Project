<?php
include("../application_config/db_class.php");
include("../fonctions/functions.php");
session_start();

$conn = Database::connect();

$administrateur = $responsableUE = $scolarite = $tuteurUniversitaire = $etudiant = $mail = $user = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $administrateur = isset($_POST['administrateur']) ? 'oui' : 'non';
    $responsableUE = isset($_POST['responsableUE']) ? 'oui' : 'non';
    $scolarite = isset($_POST['scolarite']) ? 'oui' : 'non';
    $tuteurUniversitaire = isset($_POST['tuteurUniversitaire']) ? 'oui' : 'non';
    $etudiant = isset($_POST['etudiant']) ? 'oui' : 'non';
    $mail = $_POST['mail'];
    $user = $_POST['user'];

    // Génération d'un mot de passe aléatoire
    $mdp = generatePassword();

    //verif doublon ?
    $query = "SELECT count(*) FROM utilisateur WHERE Mail_Utilisateur LIKE '$mail'";
    $statement = $conn->query($query);
    $countUser = $statement->fetch();

    if ($countUser[0] > 0) {
        $_SESSION['success'] = 22;
        header('Location: accountManager_administrateur.php');
    } else {
        // Insertion de l'utilisateur avec le mot de passe
        $query = "INSERT INTO utilisateur (Nom_Utilisateur, Mail_Utilisateur, MDP_Utilisateur) VALUES ('$user', '$mail', '$mdp')";
        $conn->query($query);
        $id_utilisateur = $conn->lastInsertId();

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
        $conn->query($query);

        $_SESSION['success'] = 2;
        header('Location: accountManager_administrateur.php');
    }
}
