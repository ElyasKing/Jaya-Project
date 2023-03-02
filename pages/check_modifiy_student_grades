<?php
include "../application_config/db_class.php";
$conn = Database::connect();
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire 
    $etud_ID = $conn->query("SELECT ID_Utilisateur from utilisateur where Nom_Utilisateur='".$_POST["liste-noms"]."'")->fetchColumn();
    $commentaire = $_POST["commentaire"];

    $total = 0;
    $count = 0;
    $param_ID = array();
    $listparam = $conn->query("SELECT nom_param from parametres  where nbpoint_param is not null")->fetchAll();
    foreach ($listparam as $param) {
        if(intval($_POST[str_replace(" ","_",$param['nom_param'])]) != 0) {
            $total = $total + intval($_POST[str_replace(" ","_",$param['nom_param'])]);
            //On stock l'ID de chaque paramètre (note) où la note est supérieure à 0
            $param_ID[$count] = $conn->query("SELECT ID_param from parametres  where nom_param ='".$param['nom_param']."'")->fetchColumn();
            $count = $count + 1;
        
        }
    }
    
    //requete pour mettre à jour informations de l'utilisateur 
    $query = "INSERT INTO notes_soutenance (`NoteFinale_NS`, `Commentaire_NS`, `ID_Utilisateur`, `ID_Invite`) VALUES ('".$total."', '".$commentaire."', '".$etud_ID."', '".$_SESSION['user_id']."')";
    $result = $conn->query($query);

    //On récupère l'ID de la dernière note ajoutée
    $note_ID = $conn->query("SELECT ID_NS FROM `notes_soutenance` order by ID_NS DESC LIMIT 1")->fetchColumn();

    //On ajoute l'ID de la note ajoutée à tous ses paramètres
    foreach($param_ID as $id) {
        $query = "INSERT INTO `est_compose` (`ID_NO`, `ID_Parametre`) VALUES ('".$note_ID."', '".$id."');";
        $result = $conn->query($query);
    }
    

}
  
$conn = Database::disconnect();
?>