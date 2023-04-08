<?php
include "../application_config/db_class.php";
$conn = Database::connect();

if (isset($_POST['user']) && isset($_POST['mdp'])) {
  $admin = isset($_POST['admin']) ? 'oui' : 'non';
  $respue = isset($_POST['responsableUE']) ? 'oui' : 'non';
  $scolarite = isset($_POST['scolarite']) ? 'oui' : 'non';
  $tu = isset($_POST['tuteurUniversitaire']) ? 'oui' : 'non';
  $etud = isset($_POST['etudiant']) ? 'oui' : 'non';
  $mdp = $_POST['mdp'];
  $user = $_POST['user'];

  // Insertion de l'utilisateur
  $query1 = "INSERT INTO utilisateur (Nom_Utilisateur, MDP_Utilisateur) VALUES ('$user', '$mdp')";
  $conn->query($query1);
  $id_utilisateur = $conn->lastInsertId();

  // Insertion des habilitations
  $query2 = "INSERT INTO habilitations (ID_Utilisateur, Admin_Habilitations, ResponsableUE_Habilitations, Scolarite_Habilitations, TuteurUniversitaire_Habilitations, Etudiant_Habilitations) VALUES ('$id_utilisateur', '$admin', '$respue', '$scolarite', '$tu', '$etud')";
  $conn->query($query2);

  header('Location: habilitations.php?success_create=1');
  exit();
}
