<?php
include("../../../application_config/db_class.php");
include("../../../fonctions/functions.php");
session_start();

if(!isConnectedUser()){
    $_SESSION['success'] = 2;
    header("Location: login.php");
}

$id = $session = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id_student'];
    $scheduleId = $_POST['session'];
    
    $db = Database::connect();

    $query = 'UPDATE utilisateur SET ID_Planning = "' . $scheduleId . '" WHERE ID_utilisateur = "' . $id . '"';
    $result = $db->query($query);

    $query = 'SELECT DISTINCT(p.ID_Planning), u.ID_Planning FROM planning p LEFT JOIN utilisateur u ON p.ID_Planning = u.ID_Planning;';
    $statement = $db->query($query);
    while ($row = $statement->fetch()) {
        if($row[1] == NULL){
            $query = "DELETE FROM planning WHERE ID_Planning =".$row[0];
            $statement = $db->query($query);
        }
    }

    $_SESSION['success'] = 2;
    header('Location: schedule_administrateur.php');
}
