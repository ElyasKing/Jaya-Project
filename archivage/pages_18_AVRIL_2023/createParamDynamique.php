<?php
include "../application_config/db_class.php";
$conn = Database::connect();
if (isset($_POST)) {

    $nom = $_POST['nom'];
    $point = $_POST['point'];
    $total = $_POST['total'];

    $somme = $total + $point;

    if ($somme > 20) {
        header('Location: /pages/parametreDynamique.php?status=fail');
    } else {
        $query = 'INSERT INTO parametres (Nom_param, NbPoint_param) VALUES ("' . $nom . '","' . $point . '")';
        $result = $conn->query($query);

        header('Location: parametreDynamique.php?status=success');
    }
}
