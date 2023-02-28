<?php
include "../application_config/db_class.php";
$conn = Database::connect();
if(isset($_POST)) 
{

    var_dump($_POST);
    $id = $_GET['id'];
    $idTuteur = $_POST['idTuteur'];
    $nomEtudiant = $_POST['nomEtudiant'];
    $emailEtudiant = $_POST['emailEtudiant'];
    $promo = $_POST['promo'];
    $entreprise = $_POST['entreprise'];
    $ville=$_POST['ville'];
    $nomTuteur = $_POST['nomTuteur'];
    $emailTuteur =$_POST['emailTuteur'];

    if(isset($_POST['huitClos']) == false)
    {
        $huitclos = "non";
    } else {
        $huitclos = "oui";
    }
   

    $query = 'UPDATE utilisateur SET Nom_Utilisateur = "'.$nomEtudiant.'", Mail_Utilisateur = "'.$emailEtudiant.'", 
    Promo_Utilisateur = "'.$promo.'", HuitClos_Utilisateur= "'.$huitclos.'" WHERE ID_Utilisateur = "'.$id.'"';
    $result = $conn->query($query);

    $queryTuteur = 'UPDATE utilisateur SET Nom_Utilisateur = "'.$nomTuteur.'", Mail_Utilisateur = "'.$emailTuteur.'" WHERE ID_Utilisateur = "'.$idTuteur.'" ';
    $resultats = $conn->query($queryTuteur);


}

?>