<?php
session_start();

if (!isConnectedUser()) {
    $_SESSION['success'] = 2;
    header("Location: logout.php");
}

if(isset($_POST['activeSchedule'])){
   $_SESSION['activeSchedule'] = $_POST['activeSchedule'];

    var_dump($_SESSION);
}