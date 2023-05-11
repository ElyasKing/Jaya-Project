<?php
include "../application_config/db_class.php";
$conn = Database::connect();


  $admin = isset($_POST['admin']) ? 'oui' : 'non';
  $respue = isset($_POST['responsableUE']) ? 'oui' : 'non';
  $scolarite = isset($_POST['scolarite']) ? 'oui' : 'non';
  $tu = isset($_POST['tuteurUniversitaire']) ? 'oui' : 'non';
  $etud = isset($_POST['etudiant']) ? 'oui' : 'non';
  $mail= $_POST['mail'];
  $user = $_POST['user'];

  
  // Génération d'un mot de passe aléatoire
  $mdp = generatePassword();

  // Insertion de l'utilisateur avec le mot de passe
  $query1 = "INSERT INTO utilisateur (Nom_Utilisateur, Mail_Utilisateur, MDP_Utilisateur) VALUES ('$user', '$mail', '$mdp')";
  $conn->query($query1);
  $id_utilisateur = $conn->lastInsertId();

  // Insertion des habilitations
  $query2 = "INSERT INTO habilitations (ID_Utilisateur, Admin_Habilitations, ResponsableUE_Habilitations, Scolarite_Habilitations, TuteurUniversitaire_Habilitations, Etudiant_Habilitations) VALUES ('$id_utilisateur', '$admin', '$respue', '$scolarite', '$tu', '$etud')";
  $conn->query($query2);

  header('Location: habilitations.php?success_create=1');
  Exit();


// Fonction de génération de mot de passe aléatoire
function generatePassword() {
    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $password = array();
    $alphaLength = strlen($alphabet) - 1;
    for ($i = 0; $i < 8; $i++) {
        $n = rand(0, $alphaLength);
        $password[] = $alphabet[$n];
    }
    return implode($password);
}
?>
