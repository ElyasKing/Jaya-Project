<?php
include "../application_config/db_class.php";
$conn = Database::connect();
if (isset($_POST)) {

    $id = $_POST['id'];

    $mdpUtilisateur = $_POST['mdpUtilisateur'];
    $admin = $_POST['admin'];
    $respue= $_POST['responsableUE'];
    $scolarite = $_POST['scolarite'];
    $tu = $_POST['tuteurUniversitaire'];
    $etud = $_POST['etudiant'];


         $query1 = 'UPDATE utilisateur SET MDP_Utilisateur="'.$mdpUtilisateur. '" WHERE ID_Utilisateur = "' . $id . '"';
         $result = $conn->query($query1);
    

        $query2 = 'UPDATE habilitations SET Admin_Habilitations = "' . $admin . '", ResponsableUE_Habilitations = "' . $respue . '", 
        Scolarite_Habilitations = "' . $scolarite . '", TuteurUniversitaire_Habilitations= "' . $tu .  '", Etudiant_Habilitations= "' . $etud . '" WHERE ID_Utilisateur = "' . $id . '"';
        $result = $conn->query($query2);

  
echo($admin);

   // header('Location: habilitations.php');
}
