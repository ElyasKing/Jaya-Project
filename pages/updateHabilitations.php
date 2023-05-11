<?php
include "../application_config/db_class.php";
$conn = Database::connect();
if (isset($_POST)) {

  
    $id = $_POST['id'];
   $admin = isset($_POST['admin']) ? 'oui' : 'non';
   $respue = isset($_POST['responsableUE']) ? 'oui' : 'non';
   $scolarite = isset($_POST['scolarite']) ? 'oui' : 'non';
   $tu = isset($_POST['tuteurUniversitaire']) ? 'oui' : 'non';
   $etud = isset($_POST['etudiant']) ? 'oui' : 'non';

   $mdp = isset($_POST['mdp']) ? $_POST['mdp'] : '';


         if($mdp<>""){
            $query1 = 'UPDATE utilisateur SET MDP_Utilisateur="'.$mdp. '" WHERE ID_Utilisateur = "' . $id . '"';
            echo($query1);
            $result = $conn->query($query1);
         }
    

        $query2 = 'UPDATE habilitations SET Admin_Habilitations = "' . $admin . '", ResponsableUE_Habilitations = "' . $respue . '", 
        Scolarite_Habilitations = "' . $scolarite . '", TuteurUniversitaire_Habilitations= "' . $tu .  '", Etudiant_Habilitations= "' . $etud . '" WHERE ID_Utilisateur = "' . $id . '"';
        $result = $conn->query($query2);
  


    header('Location: habilitations.php?success=1');

}
