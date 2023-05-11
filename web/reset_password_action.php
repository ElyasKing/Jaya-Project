<?php
session_start();
require_once("../application_config/db_class.php");
include("../fonctions/functions.php");
?>

<!DOCTYPE html>
<html>
  <head>
    <title>Réinitialisation du mot de passe</title>
    <link rel="stylesheet" type="text/css" href="./styles.css" />
  </head>
  <body>
    <div class="container bg-light p-3">
      <?php
      if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['token'])) {
          $token = $_GET['token'];

          // Vérifie si le jeton existe dans la table password_reset_tokens
          $query = "SELECT user_id, Mail_Utilisateur FROM password_reset_tokens WHERE token = :token";
          $stmt = Database::connect()->prepare($query);
          $stmt->bindParam(":token", $token);
          $stmt->execute();
          $token_data = $stmt->fetch(PDO::FETCH_ASSOC);

          if ($token_data === false) {
              echo "<h2>Erreur de réinitialisation du mot de passe</h2>";
              echo "<p>Le jeton de réinitialisation du mot de passe est invalide ou a expiré.</p>";
          } else {
              $user_id = $token_data['user_id'];
              $email = $token_data['Mail_Utilisateur'];

              // Afficher le formulaire de réinitialisation du mot de passe
              echo "<h2>Réinitialisation du mot de passe</h2>";
              echo "<p>Veuillez entrer votre nouveau mot de passe :</p>";
              echo "<form method='post' action='reset_password_action.php'>";
              echo "<input type='hidden' name='user_id' value='$user_id'>";
              echo "<input type='hidden' name='token' value='$token'>";
              echo "<label for='password'>Nouveau mot de passe :</label>";
              echo "<input type='password' id='password' name='password' required>";
              echo "<input type='submit' name='submit' value='Réinitialiser le mot de passe'>";
              echo "</form>";
          }
      } elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
          $new_password = $_POST['password'];

          // Met à jour le mot de passe dans la table utilisateur
          $hashed_password = $new_password;
          $user_id = $_POST['user_id'];
          $token = $_POST['token'];

          try {
            $query = "UPDATE utilisateur SET MDP_Utilisateur = :password WHERE ID_Utilisateur = :user_id";
            $stmt = Database::connect()->prepare($query);
            $stmt->bindParam(":password", $hashed_password);
            $stmt->bindParam(":user_id", $user_id);
            $stmt->execute();
        
            // Supprime le jeton de réinitialisation de la table password_reset_tokens
            $query = "DELETE FROM password_reset_tokens WHERE token = :token";
            $stmt = Database::connect()->prepare($query);
            $stmt->bindParam(":token", $token);
            $stmt->execute();
        
            // Redirige vers une page de confirmation ou affiche un message de succès
            echo "<p>Le mot de passe a été réinitialisé avec succès.</p>";
        } catch (PDOException $e) {
            echo "Erreur lors de la réinitialisation du mot de passe : " . $e->getMessage();
        }
      }
