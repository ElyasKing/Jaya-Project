<?php
include "../application_config/db_class.php";
$conn = Database::connect();
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    var_dump($_POST);

    $total = 0;
    $listparam = $conn->query("SELECT nom_param, nbpoint_param from parametres")->fetchAll();
    foreach ($listparam as $param) {
        if (isset($_POST[$param['nom_param']]))
        $total = $total + $_POST[$param['nom_param']];
        echo $_POST[$param['nom_param']];
        echo $total;
    
    }
echo $total;


    $etud_ID = $conn->query("SELECT ID_Utilisateur from utilisateur where Nom_Utilisateur='".$_POST["liste-noms"]."'")->fetch()['ID_Utilisateur'];
    $commentaire = $_POST["commentaire"];

    //requete pour mettre à jour informations de l'utilisateur 
    $query = "INSERT INTO notes_soutenance (`NoteFinale_NS`, `Commentaire_NS`, `ID_Utilisateur`, `ID_Invite`) VALUES ( '".$total."', '".$commentaire."', '". $etud_ID."', '".$_SESSION['user_id']."')";
    $result = $conn->query($query);

    header("Location: login.php");
}
  
$conn = Database::disconnect();
?>




