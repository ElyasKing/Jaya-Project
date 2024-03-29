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
    $etud_ID = $db->query("SELECT ID_Utilisateur from utilisateur where Nom_Utilisateur='".$_POST["liste-noms"]."'")->fetchColumn();
    $commentaire = $_POST["commentaire"];
    $note_finale=$_POST["note_finale"];

    //le champ note finale est renseigné
    if($note_finale<>""){
        if($_SESSION['active_profile'] <> "INVITE") {
            $query = "INSERT INTO notes_soutenance (`NoteFinale_NS`, `Commentaire_NS`, `ID_UtilisateurEvalue`, `ID_UtilisateurEvaluateur`) VALUES (?, ?, ?, ?)";
        }else{
            $query = "INSERT INTO notes_soutenance (`NoteFinale_NS`, `Commentaire_NS`, `ID_UtilisateurEvalue`, `ID_InviteEvaluateur`) VALUES (?, ?, ?, ?)";
        }
        $stmt = $db->prepare($query);
        $stmt->execute([$note_finale, $commentaire, $etud_ID, $_SESSION['user_id']]);

    }
    //Le champ note finale n'est pas renseigné alors on fait un calcul de tous les paramètres et on vérifie si la somme de ceux ci fait 20 
    else{
    $total = 0;
    $count = 0;
    $param_ID = array();
    $totalnoteparam = $db->query("SELECT sum(nbpoint_param) from parametres where nbpoint_param is not null")->fetchColumn();
    $listparam = $db->query("SELECT nom_param from parametres  where nbpoint_param is not null")->fetchAll();
    foreach ($listparam as $param) {
        if(intval($_POST[str_replace(" ","_",$param['nom_param'])]) != 0) {
            $total = $total + intval($_POST[str_replace(" ","_",$param['nom_param'])]);
            //On stock l'ID de chaque paramètre (note) où la note est supérieure à 0
            $param_ID[$count] = $db->query("SELECT ID_param from parametres  where nom_param ='".$param['nom_param']."'")->fetchColumn();
            $count=$count+1; 
        }
    }
   
    //si la note des paramètres n'est pas égale à 20 alors on la recalcul à l'aide d'un produit en croix
    if($totalnoteparam<>20){
         $total=(($total*20)/$totalnoteparam);
     }

        if($_SESSION['active_profile'] <> "INVITE") {
            $query = "INSERT INTO notes_soutenance (`NoteFinale_NS`, `Commentaire_NS`, `ID_UtilisateurEvalue`, `ID_UtilisateurEvaluateur`) VALUES (?, ?, ?, ?)";
        }else{
            $query = "INSERT INTO notes_soutenance (`NoteFinale_NS`, `Commentaire_NS`, `ID_UtilisateurEvalue`, `ID_InviteEvaluateur`) VALUES (?, ?, ?, ?)";
        }
        $stmt = $db->prepare($query);
        $stmt->execute([$total, $commentaire, $etud_ID, $_SESSION['user_id']]);

    //On récupère l'ID de la dernière note ajoutée
    $note_ID = $db->query("SELECT ID_NS FROM `notes_soutenance` order by ID_NS DESC LIMIT 1")->fetchColumn();

    //On ajoute l'ID de la note ajoutée à tous ses paramètres
    foreach($param_ID as $id) {
        $query = "INSERT INTO `est_compose` (`ID_NO`, `ID_Parametre`) VALUES ('".$note_ID."', '".$id."');";
        $result = $db->query($query);
    }
} 

}
  
$db = Database::disconnect();

$_SESSION['success'] = 1;

if($_SESSION['active_profile'] == "INVITE") {
    header("Location:" . $_SESSION['session_url']);
}else

if($_SESSION['active_profile'] == "ADMINISTRATEUR"){
    header("Location: ../admin/administrateur.php");
}else{
    header("Location: tuteurUniversitaire.php");
}


