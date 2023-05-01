<?php
include("../../../application_config/db_class.php");
include("../../../fonctions/functions.php");
session_start();

if(!isConnectedUser()){
    $_SESSION['success'] = 2;
    header("Location: login.php");
}

$db = Database::connect();

$newPassword = $newPasswordConfirmation = $id = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $newPassword = $_POST['pw1'];
    $newPasswordConfirmation = $_POST['pw2'];

    if($newPassword != $newPasswordConfirmation){
        $_SESSION['success'] = 11;
        header('Location: accountManager_users.php');
    }else{
        $pwd = $newPassword;
        $pwd_hash = password_hash($pwd, PASSWORD_BCRYPT);

        $query = 'UPDATE utilisateur SET MDP_Utilisateur="' . $pwd_hash . '" WHERE ID_Utilisateur = "' . $id . '"';
        $result = $db->query($query);

        $_SESSION['success'] = 1;
        header('Location: accountManager_users.php');
    }
}
