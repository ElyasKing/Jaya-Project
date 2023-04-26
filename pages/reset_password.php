<?php
session_start();
?>

<!DOCTYPE html>
<html>
  <head>
    <title>Mot de passe oublié</title>
    <link rel="stylesheet" type="text/css" href="./styles.css" />
  </head>
  <body>
    <div class="container bg-light p-3">
      <form method="post" action="reset_password.php">
        <h2>Mot de passe oublié</h2>
        <label for="email">Entrez votre adresse e-mail :</label>
        <input type="email" id="email" name="email" required>
        <input type="submit" name="submit" value="Réinitialiser le mot de passe">
      </form>
    </div>

    <?php
      if (isset($_SESSION['reset_password_success'])) {
          echo "<script>alert('" . $_SESSION['reset_password_success'] . "')</script>";
          unset($_SESSION['reset_password_success']);
      }

      if (isset($_SESSION['reset_password_error'])) {
          echo "<script>alert('" . $_SESSION['reset_password_error'] . "')</script>";
          unset($_SESSION['reset_password_error']);
      }
    ?>
  </body>
</html>

<?php
require_once("../application_config/db_class.php");

ini_set('SMTP', '127.0.0.1');
ini_set('smtp_port', 25);

if(isset($_POST['submit'])){

    $email = $_POST['email'];

// vérifie que l'adresse email existe dans la table utilisateur
$query = "SELECT ID_Utilisateur, Nom_Utilisateur, Mail_Utilisateur FROM utilisateur WHERE Mail_Utilisateur = :email";
$stmt = Database::connect()->prepare($query);
$stmt->bindParam(":email", $email);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

var_dump($user); // Ajout de cette ligne pour vérifier les données utilisateur récupérées

if($user === false){
    $_SESSION['reset_password_error'] = "Adresse email non trouvée.";
    header('Location: reset_password.php');
    exit();
}

// génère un token unique et l'insère dans la table password_reset_tokens
$token = bin2hex(random_bytes(16));
$query = "INSERT INTO password_reset_tokens (user_id, token, Mail_Utilisateur) VALUES (:user_id, :token, :Mail_Utilisateur)";
$stmt = Database::connect()->prepare($query);
$stmt->bindParam(":user_id", $user['ID_Utilisateur']);
$stmt->bindParam(":token", $token);
$stmt->bindParam(":Mail_Utilisateur", $user['Mail_Utilisateur']);
$stmt->execute();

 // envoie un e-mail à l'utilisateur avec le lien de réinitialisation
$to = $email;
$subject = "Réinitialisation de votre mot de passe JAYA";
$message = "Bonjour " . $user['Nom_Utilisateur'] . ",\n\n";
$message .= "Veuillez cliquer sur le lien suivant pour réinitialiser votre mot de passe JAYA : \n";
$message .= "http://localhost/Jaya-Project/pages/new_password.php?token=" . $token;
$headers = "From: jaya-project@example.com";

if(mail($to, $subject, $message, $headers)){
    $_SESSION['reset_password_success'] = "Un e-mail de réinitialisation de mot de passe a été envoyé à votre adresse e-mail.";
} else {
    $_SESSION['reset_password_error'] = "Une erreur est survenue lors de l'envoi de l'e-mail de réinitialisation de mot de passe.";
}

header('Location: reset_password.php');
exit();
}
?>
