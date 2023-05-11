<?php
include("../application_config/db_class.php");
include("../fonctions/functions.php");
session_start();

if (!isConnectedUser()) {
    $_SESSION['success'] = 2;
    header("Location: login.php");
}

$db = Database::connect();

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // On update
    $query = "UPDATE `utilisateur` SET SoutenanceSupp_Utilisateur='oui' WHERE ID_Utilisateur = $id;";
    $statement = $db->prepare($query);
    $statement->execute();

    $db = Database::disconnect();

    $_SESSION['success'] = 3;
    // Redirige vers la page contenant la liste des utilisateurs
    header("Location: studentOralManagement_administrateur.php");
}
