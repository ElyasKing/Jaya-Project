<?php
include("../application_config/db_class.php");
include("../fonctions/functions.php");
session_start();

if(!isConnectedUser()){
    $_SESSION['success'] = 2;
    header("Location: login.php");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {    
    $db = Database::connect();

    $query = "UPDATE decisions SET ValidationScolarite_Planning = 'oui'";
    $result = $db->query($query);

    
    header('Location: schedule_scolarite.php');   
}
