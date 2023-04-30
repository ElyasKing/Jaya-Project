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
    Remarque_NF = "' . $remarque . '", Rapport_NF= "' . $rapport . '", Appreciation_NF= "' . $appreciation . '", Orthographe_NF= "' . $orthographe . '" 
    WHERE ID_Utilisateur = "' . $id . '"';
    $result = $conn->query($query);
    $etudiant = $result->fetch();

    if ($etudiant == false) {
        $query = $query = 'INSERT INTO notes_suivi (NoteFinale_NF, Poster_NF, Remarque_NF, Rapport_NF, Appreciation_NF, Orthographe_NF, ID_Utilisateur) 
        VALUES ("' . $suivi . '","' . $poster . '","' . $remarque . '","' . $rapport . '","' . $appreciation . '","' . $orthographe . '","' . $id . '")';
        $result = $conn->query($query);
    }

    header('Location: /pages/suiviRendus.php');
}
