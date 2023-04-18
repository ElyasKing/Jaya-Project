<?php
include("../application_config/db_class.php");
session_start();

$conn = Database::connect();

if (isset($_GET['id'])) {
  $id = $_GET['id'];

  // Supprime l'utilisateur de la table utilisateur
  $query = "DELETE FROM utilisateur WHERE Id_Utilisateur = $id";
  $statement = $conn->prepare($query);
  $statement->execute();

  $conn = Database::disconnect();

  $_SESSION['success'] = 3;
  // Redirige vers la page contenant la liste des utilisateurs
  header("Location: accountManager_administrateur.php");
}
?>
