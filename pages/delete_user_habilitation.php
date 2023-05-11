<?php
session_start();
include "../application_config/db_class.php";
$conn = Database::connect();

if (isset($_GET['id'])) {
  $id = $_GET['id'];
/*
  // Supprime l'utilisateur de la table utilisateur
  $sql_delete = "DELETE FROM Utilisateurs WHERE Id_Utilisateur = :id";
  $stmt_delete = $conn->prepare($sql_delete);
  $stmt_delete->bindParam(':id', $id, PDO::PARAM_INT);
  $stmt_delete->execute();
*/
  // Supprime l'utilisateur de la table utilisateur
  $sql_delete_user = "DELETE FROM utilisateur WHERE Id_Utilisateur = :id";
  $stmt_delete_user = $conn->prepare($sql_delete_user);
  $stmt_delete_user->bindParam(':id', $id, PDO::PARAM_INT);
  $stmt_delete_user->execute();

  $_SESSION['success_message'] = "L'utilisateur a été supprimé avec succès.";

  // Redirige vers la page contenant la liste des utilisateurs
  header("Location: habilitations.php");
  exit();
}
?>
