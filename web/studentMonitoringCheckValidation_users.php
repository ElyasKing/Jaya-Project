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

    $query = "SELECT Validation_NF FROM decisions";
    $statement = $db->query($query);
    $result = $statement->fetch();

    if($result[0] == "non"){
        $query = "UPDATE decisions SET Validation_NF = 'oui'";
    }else{
        $query = "UPDATE decisions SET Validation_NF = 'non'";
    }
    $db->query($query);
    
    $_SESSION['success'] = 1;
    header('Location: studentMonitoring_users.php');   
}
