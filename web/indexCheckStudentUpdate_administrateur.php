<?php
include "../application_config/db_class.php";
session_start();
$conn = Database::connect();

if (isset($_POST)) {

    $idEtudiant = $_POST['id'];
    $promo = $_POST['promo'];
    $entreprise = $_POST['entreprise'];
    $ville = $_POST['ville'];
    $huitclos = isset($_POST['huitClos']) ? "oui" : "non";

    // Mise à jour des informations liées à l'étudiant
    $query = 'UPDATE utilisateur SET Promo_Utilisateur = "' . $promo . '", HuitClos_Utilisateur= "' . $huitclos . '" WHERE ID_Utilisateur = "' . $idEtudiant . '"';
    $result = $conn->query($query);

    // Mise à jour des informations liées au tuteur entreprise
    $query = "DELETE FROM `est_apprenti` WHERE ID_Utilisateur ='" . $idEtudiant . "'";
    $result = $conn->query($query);

    foreach ($_POST['emailte'] as $emailte) {
        $query = "SELECT ID_Invite FROM `invite` WHERE Mail_Invite='" . $emailte . "'";
        $result = $conn->query($query)->fetch();
        $idTE = $result['ID_Invite'];

        $query = "INSERT INTO est_apprenti (ID_Utilisateur, ID_Invite) VALUES ('" . $idEtudiant . "', '" . $idTE . "')";
        $result = $conn->query($query);


        $query = "UPDATE `invite` SET `Entreprise_Invite` ='" . $entreprise . "', `Ville_Invite` ='" . $ville . "' WHERE `ID_Invite`='" . $idTE . "'";
        $result = $conn->query($query);
    }


    // Mise à jour des informations liées au tuteur universitaire

    $query = "SELECT ID_Utilisateur FROM `Utilisateur` WHERE Mail_Utilisateur='" . $_POST['emailTuteur'] . "'";
    $result = $conn->query($query)->fetch();
    $idTuteurUniversitaire = $result['ID_Utilisateur'];

    $query = "SELECT COUNT(ID_etudiant)  FROM `etudiant_tuteur` WHERE ID_etudiant='" . $idEtudiant . "'";
    $result = $conn->query($query)->fetch();
    $occurence = $result['COUNT(ID_etudiant)'];

    if ($occurence > 0) {
        $query = "UPDATE `etudiant_tuteur` SET `ID_tuteur`='" . $idTuteurUniversitaire . "' WHERE ID_Etudiant='" . $idEtudiant . "'";
        $result = $conn->query($query);
    } else {
        $query = "INSERT INTO `etudiant_tuteur`(`ID_etudiant`, `ID_tuteur`) VALUES ('" . $idEtudiant . "','" . $idTuteurUniversitaire . "')";
        $result = $conn->query($query);
    }
}

$_SESSION['success'] = 1;
header('Location: index.php');
