<?php
include("../../../application_config/db_class.php");
include("../../../fonctions/functions.php");
session_start();

if(!isConnectedUser()){
    $_SESSION['success'] = 2;
    header("Location: login.php");
}

$id = $sessionDate = $sessionTime = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $sessionDate = $_POST['sessionDate'];
    $sessionTime = $_POST['sessionTime'];
    
    $db = Database::connect();

    $query = 'UPDATE planning SET HeureDebutSession_Planning = "' . $sessionTime . '", DateSession_Planning = "' . $sessionDate . '" WHERE ID_planning = "' . $id . '"';
    $result = $db->query($query);

    $_SESSION['success'] = 3;
    header('Location: schedule_scolarite.php');   
}
