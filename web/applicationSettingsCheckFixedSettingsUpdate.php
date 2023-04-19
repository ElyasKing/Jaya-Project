<?php
include("../application_config/db_class.php");
session_start();

$db = Database::connect();

$description = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['id'])) {
        foreach ($_POST['id'] as $key => $id) {
            $description = $_POST['description'][$key];

            $query = 'UPDATE parametres SET Description_Param ="' . $description . '" WHERE ID_param ="' . $id . '"';
            $result = $db->query($query);
        }
    }

    $_SESSION['success'] = 1;
    header('Location: applicationSettings.php');
}
