<?php
include "../application_config/db_class.php";
$conn = Database::connect();

$id = $_GET['id'];
$query = "DELETE FROM parametres WHERE ID_param = $id";
$result = $conn->query($query);

header('Location: /pages/parametreDynamique.php');
