<?php
include("../application_config/db_class.php");
include("../fonctions/functions.php");
session_start();

if(!isConnectedUser()){
    $_SESSION['success'] = 2;
    header("Location: login.php");
}

// Elias

$db = Database::connect();

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Supprime la note de la table invite
    $query = "DELETE FROM invite WHERE Id_Invite = $id";
    $statement = $db->prepare($query);
    $statement->execute();

    $db = Database::disconnect();

    $_SESSION['success'] = 2;
    // Redirige vers la page contenant la liste des utilisateurs
    header("Location: guestManagement_scolarite.php");
}
