<?php
include "../application_config/db_class.php";
$conn = Database::connect();
if (isset($_POST)) {

    $id = $_POST['id'];
    $remarque = $_POST['remarque'];
    $appreciation = $_POST['appreciation'];
    $orthographe = $_POST['orthographe'];
    $suivi = $_POST['suivi'];

    if (isset($_POST['poster']) == false) {
        $poster = "non";
    } else {
        $poster = "oui";
    }

    if (isset($_POST['rapport']) == false) {
        $rapport = "non";
    } else {
        $rapport = "oui";
    }

    $query = 'UPDATE notes_suivi SET NoteFinale_NF = "' . $suivi . '", Poster_NF = "' . $poster . '", 
    Remarque_NF = "' . $remarque . '", Rapport_NF= "' . $rapport . '", Appreciation_NF= "' . $appreciation . '" WHERE ID_Utilisateur = "' . $id . '"';
    $result = $conn->query($query);

    header('Location: /pages/suiviRendus.php');
}
