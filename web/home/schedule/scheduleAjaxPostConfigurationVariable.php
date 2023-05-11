<?php
session_start();

if(isset($_POST['activeSchedule'])){
   $_SESSION['activeSchedule'] = $_POST['activeSchedule'];

    var_dump($_SESSION);
}