<?php

include "../application_config/db_class.php";
$conn = Database::connect();

if (isset($_POST)) {

    if (isset($_POST['id'])) {
        foreach ($_POST['id'] as $key => $id) {
            $description = $_POST['description'][$key];

            $query = 'UPDATE parametres SET Description_Param ="' . $description . '" WHERE ID_param ="' . $id . '"';
            $resultats = $conn->query($query);
        }
    }

    header('Location: /pages/parametreFixe.php?status=success');
}
