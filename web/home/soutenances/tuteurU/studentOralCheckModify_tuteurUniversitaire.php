<?php
include "../../../../application_config/db_class.php";
include("../../../../fonctions/functions.php");
session_start();

if($_SESSION['active_profile'] <> "INVITE") {
    if (!isConnectedUser()) {
        $_SESSION['success'] = 2;
        header("Location: logout.php");
    }
}

$db = Database::connect();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Récupérer les données du formulaire 
    $commentaire = $_POST["commentaire"];
    $note_finale = $_POST["note_finale"];
    $ID_NS = $_POST["id_NS"];


    //le champ note finale est renseigné
    if ($note_finale <> "") {
        $query = "UPDATE `notes_soutenance` SET `NoteFinale_NS` = ?, `Commentaire_NS` = ?  WHERE ID_NS = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$note_finale, $commentaire, $ID_NS]);
    }
    //Le champ note finale n'est pas renseigné alors on fait un calcul de tous les paramètres et on vérifie si la somme de ceux ci fait 20 
    else {
        $total = 0;
        $count = 0;
        $param_ID = array();
        $totalnoteparam = $db->query("SELECT sum(nbpoint_param) from parametres where nbpoint_param is not null")->fetchColumn();
        $listparam = $db->query("SELECT nom_param from parametres  where nbpoint_param is not null")->fetchAll();
        foreach ($listparam as $param) {
            if ($_POST[str_replace(" ", "_", $param['nom_param'])] != 0) {
                $total = $total + $_POST[str_replace(" ", "_", $param['nom_param'])];
                //On stock l'ID de chaque paramètre (note) où la note est supérieure à 0
                $param_ID[$count] = $db->query("SELECT ID_param from parametres  where nom_param ='" . $param['nom_param'] . "'")->fetchColumn();
                $count = $count + 1;
            }
        }

        //si la note des paramètres n'est pas égale à 20 alors on la recalcul à l'aide d'un produit en croix
        if ($totalnoteparam <> 20) {
            $total = (($total * 20) / $totalnoteparam);
        }


        //requete pour mettre à jour informations de l'utilisateur 
        $query = "UPDATE `notes_soutenance` SET `NoteFinale_NS` = ?, `Commentaire_NS` = ? WHERE ID_NS = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$total, $commentaire, $ID_NS]);


        //requete pour supprimer les informations liées à la note dans est_compose
        $query = "Delete from  `est_compose` WHERE ID_NO ='" . $ID_NS . "';";
        $result = $db->query($query);


        //On ajoute l'ID de la note ajoutée à tous ses paramètres
        foreach ($param_ID as $id) {
            $query = "INSERT INTO `est_compose` (`ID_NO`, `ID_Parametre`) VALUES ('" . $ID_NS . "', '" . $id . "');";
            $result = $db->query($query);
        }
    }
}

$db = Database::disconnect();

$_SESSION['success'] = 2;

if($_SESSION['active_profile'] == "INVITE") {
    header("Location:" . $_SESSION['session_url']);
}

header("Location: tuteurUniversitaire.php");
