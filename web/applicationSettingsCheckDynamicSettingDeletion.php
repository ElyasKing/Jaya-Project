<?php
include("../application_config/db_class.php");
session_start();

$db = Database::connect();

if (isset($_GET['id'])) {
  $id = $_GET['id'];

  // Supprime l'utilisateur de la table utilisateur
  $query = "DELETE FROM parametres WHERE ID_param = $id";
  $statement = $db->prepare($query);
  $statement->execute();

  $db = Database::disconnect();

  $_SESSION['success'] = 3;
  // Redirige vers la page contenant la liste des utilisateurs
  header("Location: applicationSettings.php");
}
?>

