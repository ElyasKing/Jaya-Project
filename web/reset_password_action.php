<?php
session_start();
require_once("../application_config/db_class.php");
include("../fonctions/functions.php");
include("./home/navigation/header.php");
?>

<!DOCTYPE html>
<html>

<head>
  <title>Réinitialisation du mot de passe</title>
  <link rel="stylesheet" type="text/css" href="./styles.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <?php
  include("./home/navigation/navbar.php");
  ?>
</head>

<body>
  <nav class="navbar navbar-dark navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        </ul>
      </div>
    </div>
  </nav>
  <br><br><br>
  <div class="container">
    <div class="row d-flex justify-content-center">
      <div class="col-12">
        <div class="card shadow-2-strong css-login" style="width: auto; max-width: 1200px;">
          <div class="card-body p-5 text-left" style="min-width: 1100px; max-width: 100%; overflow-x: auto;">
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

              // Récupérer le nom d'utilisateur
              $query = "SELECT Nom_Utilisateur FROM utilisateur WHERE ID_Utilisateur = :user_id";
              $stmt = Database::connect()->prepare($query);
              $stmt->bindParam(":user_id", $user_id);
              $stmt->execute();
              $user_data = $stmt->fetch(PDO::FETCH_ASSOC);
              $user_name = $user_data['Nom_Utilisateur'];

            // Afficher le formulaire de réinitialisation du mot de passe
            echo "<p style='margin-bottom: 1rem;'>Utilisateur : $user_name</p>";
            echo "<form method='post' action='reset_password_action.php'>";
            echo "<input type='hidden' name='user_id' value='$user_id'>";
            echo "<input type='hidden' name='token' value='$token'>";
            echo "<div style='margin-bottom: 1rem;'>";
            echo "<label for='password'>Nouveau mot de passe : </label>";
            echo "<input type='password' id='password' name='password' required>";
            echo "</div>";
            echo "<div style='margin-bottom: 1rem;'>";
            echo "<label for='confirm_password'>Confirmer le nouveau mot de passe :</label>";
            echo "<input type='password' id='confirm_password' name='confirm_password' required>";
            echo "</div>";
            echo "<p style='margin-bottom: 1rem;'><i class='fas fa-exclamation-triangle' style='color: red;'></i> Un mot de passe fort doit contenir : 8 caractères minimum, des minuscules, des majuscules, des chiffres et des caractères spéciaux.</p>";
            echo "<div class='text-center'>";
            echo "<input type='submit' class='btn me-md-3 bg' name='submit' value='Enregistrer'>";
            echo "</div>";
            echo "</form>";
          }
      } elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
          $new_password = $_POST['password'];
          $confirm_password = $_POST['confirm_password'];

          // Check that both passwords match
          if ($new_password !== $confirm_password) {
            echo "<p>Les mots de passe ne correspondent pas.</p>";
            exit();
          }

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
