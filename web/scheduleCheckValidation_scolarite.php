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

    $query = "SELECT ValidationScolarite_Planning FROM decisions";
    $statement = $db->query($query);
    $result = $statement->fetch();

    if($result[0] == "non"){
        $query = "UPDATE decisions SET ValidationScolarite_Planning = 'oui'";
    }else{
        $query = "UPDATE decisions SET ValidationScolarite_Planning = 'non'";
    }
    $db->query($query);

    
    header('Location: schedule_scolarite.php');   
}
