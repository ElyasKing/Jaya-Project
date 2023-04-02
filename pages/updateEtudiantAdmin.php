<?php
include "../application_config/db_class.php";
$conn = Database::connect();
if (isset($_POST)) {

    $id = $_POST['id'];

    $nomEtudiant = $_POST['nomEtudiant'];
    $emailEtudiant = $_POST['emailEtudiant'];
    $promo = $_POST['promo'];
    $entreprise = $_POST['entreprise'];
    $ville = $_POST['ville'];


    if (is_array($_POST['idMA'])) {
        foreach ($_POST['idMA'] as $key => $idMa) {
            $nomMa = $_POST['nomma'][$key];
            $emailMa = $_POST['emailma'][$key];

            $queryMa = 'UPDATE invite SET Nom_Invite="' . $nomMa . '", Mail_Invite="' . $emailMa . '", Entreprise_Invite="' . $entreprise . '", Ville_Invite="' . $ville . '" WHERE ID_Invite="' . $idMa . '"';
            $resultats = $conn->query($queryMa);
        }
    } else {
        $idMa = $_POST['idMA'];

        $nomMa = $_POST['nomma'][0];
        $emailMa = $_POST['emailma'][0];

        $queryMa = 'UPDATE invite SET Nom_Invite="' . $nomMa . '", Mail_Invite="' . $emailMa . '", Entreprise_Invite="' . $entreprise . '", Ville_Invite="' . $ville . '" WHERE ID_Invite="' . $idMa . '"';
        $resultats = $conn->query($queryMa);
    }

    if (isset($_POST['huitClos']) == false) {
        $huitclos = "non";
    } else {
        $huitclos = "oui";
    }


    $query = 'UPDATE utilisateur SET Nom_Utilisateur = "' . $nomEtudiant . '", Mail_Utilisateur = "' . $emailEtudiant . '", 
    Promo_Utilisateur = "' . $promo . '", HuitClos_Utilisateur= "' . $huitclos . '" WHERE ID_Utilisateur = "' . $id . '"';
    $result = $conn->query($query);

    if ($_POST['idTuteur'] != null) {
        $idTuteur = $_POST['idTuteur'];
        $nomTuteur = $_POST['nomTuteur'];
        $emailTuteur = $_POST['emailTuteur'];
        $queryTuteur = 'UPDATE utilisateur SET Nom_Utilisateur = "' . $nomTuteur . '", Mail_Utilisateur = "' . $emailTuteur . '" WHERE ID_Utilisateur = "' . $idTuteur . '" ';
        $resultats = $conn->query($queryTuteur);
    }



    header('Location: index.php');
}
