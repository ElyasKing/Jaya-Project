<?php
include("../application_config/db_class.php");
include("../fonctions/functions.php");
session_start();

if(!isConnectedUser()){
    $_SESSION['success'] = 2;
    header("Location: login.php");
}

$db = Database::connect();

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Supprime la note de la table notes_soutenance
    $query = "DELETE FROM notes_soutenance WHERE Id_NS = $id";
    $statement = $db->prepare($query);
    $statement->execute();

    $db = Database::disconnect();

    $_SESSION['success'] = 2;
    // Redirige vers la page contenant la liste des utilisateurs
    header("Location: studentOralManagement_administrateur.php");
}
