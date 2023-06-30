<?php
include("../../../application_config/db_class.php");
include("../../../fonctions/functions.php");
session_start();

if (!isConnectedUser()) {
    $_SESSION['success'] = 2;
    header("Location: logout.php");
}

$id = $ckeckPoster = $remarquePoster = $ckeckRapport = $remarqueRapport = $orthographe = $noteSuivi = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
     //var_dump($_POST);

    $db = Database::connect();

    $id = $_POST['id'];
    
    $query = getStudentMonitoringById($id);
    $result = $db->query($query);
    $user = $result->fetch();

    
    $ckeckPoster = isset($_POST['ckeckPoster']) ? 'oui' : 'non';

    if(isset($_POST['remarquePoster'])){
        $remarquePoster = $_POST['remarquePoster']; 
    }else{
        $remarquePoster = ''; 
    }

    $ckeckRapport = isset($_POST['ckeckRapport']) ? 'oui' : 'non';
    if(isset($_POST['remarqueRapport'])){
        $remarqueRapport = $_POST['remarqueRapport']; 
    }else{
        $remarqueRapport = ''; 
    }

    $orthographe = $_POST['orthographe'];

    if($_POST['noteSuivi']!=''){
        $noteSuivi = $_POST['noteSuivi']; 
    }elseif($_POST['noteSuivi'] == '' && ($user['NoteFinaleTuteur_NF'] != '' && $user['NoteFinaleTuteur_NF'] != NULL && $user['NoteFinaleTuteur_NF'] >= 0)){
        $noteSuivi = $user['NoteFinaleTuteur_NF']; 
    }else{
        $noteSuivi = 'NULL';
    }

    $query = 'SELECT description_param FROM parametres
            WHERE ID_param = 9';
    $statement = $db->query($query);
    $coeffOral = $statement->fetch();

    $query = 'SELECT description_param FROM parametres
            WHERE ID_param = 10';
    $statement = $db->query($query);
    $coeffSuivi = $statement->fetch();

    $noteOralFinale = getStutdentGradeOral($id);

    $query="SELECT ID_NF FROM notes_suivi WHERE ID_Utilisateur =".$id;
    $statement = $db->query($query);
    $exists = $statement->fetch();

    if($noteSuivi != '' && $noteSuivi != NULL && $noteOralFinale != "DEF" && $noteOralFinale != NULL){
        $notePP = ((((float)$coeffOral['description_param'] * $noteOralFinale) + ((float)$coeffSuivi['description_param'] *  $noteSuivi)) / ((float)$coeffOral['description_param'] + (float)$coeffSuivi['description_param']));
        $notePP = round($notePP, 2);
    }else{
        $notePP = 'NULL';
    }

    if(empty($exists)){
        $query = "INSERT INTO notes_suivi(NoteFinaleTuteur_NF, noteFinaleUE_NF, Poster_NF, Remarque_NF, Rapport_NF, Appreciation_NF, Orthographe_NF, ID_Utilisateur) 
            VALUES ($noteSuivi,$notePP,'$ckeckPoster','$remarquePoster','$ckeckRapport','$remarqueRapport',$orthographe,'$id')";
    }else{
        $query = "UPDATE notes_suivi SET NoteFinaleTuteur_NF=$noteSuivi, noteFinaleUE_NF=$notePP, Poster_NF='$ckeckPoster', Remarque_NF='$remarquePoster', 
            Rapport_NF='$ckeckRapport', Appreciation_NF='$remarqueRapport', Orthographe_NF=$orthographe
            WHERE ID_Utilisateur=$id"; 
    }
     //var_dump($query); die;
    $db->query($query);

    

    $_SESSION['success'] = 1;
    header('Location: studentMonitoring_users.php');
}
