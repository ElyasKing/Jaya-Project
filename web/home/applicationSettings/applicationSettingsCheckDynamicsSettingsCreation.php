<?php
include("../../../application_config/db_class.php");
include("../../../fonctions/functions.php");
session_start();

if(!isConnectedUser()){
    $_SESSION['success'] = 2;
    header("Location: login.php");
}

$db = Database::connect();

$settingName = $newPoints = $sumOfpoints = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $settingName = $_POST['settingName'];
    $newPoints = $_POST['newPoints'];
    $sumOfpoints = $_POST['sumOfpoints'];

    $pointsPrediction = $sumOfpoints + $newPoints;

    if ($pointsPrediction > 20) {
        $_SESSION['success'] = 44;
        header('Location: applicationSettings.php');
    } else {
        $query = 'INSERT INTO parametres (Nom_param, NbPoint_param) 
            VALUES ("' . $settingName . '","' . $newPoints . '")';
        $result = $db->query($query);

        $_SESSION['success'] = 4;
        header('Location: applicationSettings.php');
    }
}
