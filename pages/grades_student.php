<?php
include "../application_config/db_class.php";
$conn = Database::connect();
session_start();
?>

<!DOCTYPE html>
<html>
<head>
	<title>Noter un étudiant</title>
	<link rel="stylesheet" type="text/css" href="./styles.css" />
</head>
<body>


<?php
 //Permet de mettre les informatinos à jour en db
 if ($_SERVER["REQUEST_METHOD"] == "POST") {
     // Récupérer les données du formulaire
     $email = $_POST["email"];
     $password = $_POST["password"];
     $password_confirm = $_POST["password_confirm"];

         // Vérifier si le mot de passe correspond à la confirmation du mot de passe 
         if ($password != $password_confirm) {
             $error_message = "Les mots de passe ne correspondent pas ! ";
             echo $error_message;
         } else {
            //longueur trop faible
            if ((strlen($password)<8) OR  (strlen($email)<8)){
             $error_message = "La taille du mot de passe ou de l'email est trop petite";
             echo $error_message;
         }else{
            //requete pour mettre à jour informations de l'utilisateur 
            $query = "UPDATE utilisateur SET Mail_UTILISATEUR = '".$email."', MDP_Utilisateur = '".$password."' WHERE ID_Utilisateur = " .$_SESSION['user_id'];
            $result = $conn->query($query);
            $user = $result->fetch();

            header("Location: manage_account.php");
             exit();
         }    
     }
 
        
       }
?>



<?php
     // Requête SQL pour récupérer les informations de l'utilisateur correspondant à l'email fourni
     $query = "SELECT * FROM utilisateur WHERE ID_Utilisateur = ".$_SESSION['user_id'];
     $result = $conn->query($query);
     $user = $result->fetch();

     // Requête SQL pour récupérer les informations de l'utilisateur correspondant à l'email fourni
     $query2 = "SELECT Nom_Utilisateur  FROM utilisateur WHERE ID_Utilisateur != ".$_SESSION['user_id'];
     $result2 = $conn->query($query);
     $listEtud = $result2->fetch();
     
    // Requête SQL pour récupérer les informations de l'utilisateur correspondant à l'email fourni
     $query3 = "SELECT * from parametres";
     $result3 = $conn->query($query);
     $listparam = $result3->fetch();


     

    

     if ($user) {
         ?>

        <form method="post">
		<h2>Noter un étudiant</h2>
        <label for="nom">Votre nom :</label>
        <br>
        <php 
        while ($row = mysqli_fetch_array($resultat)) {
         echo '<option value="'.$row['nom'].'">'.$row['nom'].'</option>';
        } ?>
        <label for="email">Email :</label>
		<input type="text" id="email" name="email" <?php print($user['Mail_Utilisateur']) ?> required>
        <br>
        <label for="password">Mot de passe :</label>
		<input type="password" id="password" name="password" required>
        <br>
        <label for="password_confirm">Confirmer mot de passe : </label>
        <input type="password" id="password_confirm" name="password_confirm" required>
        <br>
		<input type="submit" value="Confirmer">
	</form>





         <?php
     } else {
         // Utilisateur non trouvé
         $error_message = "Aucun utilisateur trouvé";
         echo $error_message;
     }
?>





</body>
</html>