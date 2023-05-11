<?php
include("../../../application_config/db_class.php");
include("../../../fonctions/functions.php");
session_start();

if(!isConnectedUser()){
    $_SESSION['success'] = 2;

    header('Content-Type: application/json; charset=utf-8');
    echo returnDataWithMessage(false, 401, "KO");
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


    $query2 = getStudentEmail();
    $statement = $db->query($query2);
    $result = $statement->fetchAll(PDO::FETCH_COLUMN);

    $to_email = implode(', ', $result);
    $to_emails = $to_email;

    $_SESSION['success'] = 1;
    foreach ($result as $to_email) {
        send_email($to_email, "Note validées", "Les notes sont accessibles.");
    }

    header('Content-Type: application/json; charset=utf-8');
    echo returnDataWithMessage(true, 200, "OK");
}

?>