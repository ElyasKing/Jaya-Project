<?php
include("../application_config/db_class.php");
session_start();

$id = $settingName = $newPoints = $sumOfpoints = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $settingName = $_POST['settingName'];
    $newPoints = $_POST['newPoints'];
    $sumOfpoints = $_POST['sumOfpoints'];
    
    $db = Database::connect();

    $query = 'UPDATE parametres SET Nom_param = "' . $settingName . '", NbPoint_param = "' . $newPoints . '" WHERE ID_param = "' . $id . '"';
    $result = $db->query($query);

    $_SESSION['success'] = 2;
    header('Location: applicationSettings.php');   
}
