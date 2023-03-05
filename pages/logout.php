<?php
// à la deconnexion vider la session active (reset de $_SESSION)
session_start();

session_unset();

header("Location: login.php");
?>