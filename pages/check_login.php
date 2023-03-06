<?php
session_start();
include "../application_config/db_class.php";
$conn = Database::connect();


 if ($_SERVER["REQUEST_METHOD"] == "POST") {
     // Récupérer les données du formulaire
     $email = $_POST["email"];
     $password = $_POST["password"];

     // Requête SQL pour récupérer les informations de l'utilisateur correspondant à l'email fourni
     $query = "SELECT ID_UTILISATEUR, MDP_Utilisateur FROM utilisateur WHERE Mail_Utilisateur = '" .$email ."'";
     $user = $conn->query($query)->fetch();

         // Vérifier si l'utilisateur existe
         // Vérifier si le mot de passe est correct
         if ($password == $user["MDP_Utilisateur"]) {
            $_SESSION["user_id"] = $user["ID_UTILISATEUR"]; // Ajout dans la session l'ID
             // Authentification réussie, rediriger l'utilisateur vers une page protégée
              $_SESSION['flag']=0;
             header("Location: users_grades_student.php");
         } else {
            // Mot de passe incorrect
            $_SESSION['flag']=1;
             header("Location: login.php");
         }
 }
