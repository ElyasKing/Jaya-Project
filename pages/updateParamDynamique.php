<?php
include "../application_config/db_class.php";
$conn = Database::connect();
if (isset($_POST)) {

    $id = $_POST['id'];
    $nom = $_POST['nom'];
    $point = $_POST['point'];

    $query = 'UPDATE parametres SET Nom_param = "' . $nom . '", NbPoint_param = "' . $point . '" WHERE ID_param = "' . $id . '"';
    $result = $conn->query($query);

    header('Location: /pages/parametreDynamique.php');
}
