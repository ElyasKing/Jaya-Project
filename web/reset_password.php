<?php
session_start();
require_once("../application_config/db_class.php");
include("../fonctions/functions.php");
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

    <?php if (isset($_SESSION['reset_password_success'])): ?>
  <div class="alert alert-success">
    <?php echo $_SESSION['reset_password_success']; unset($_SESSION['reset_password_success']); ?>
  </div>
<?php endif; ?>

<?php if (isset($_SESSION['reset_password_error'])): ?>
  <div class="alert alert-danger">
    <?php echo $_SESSION['reset_password_error']; unset($_SESSION['reset_password_error']); ?>
  </div>
<?php endif; ?>
  </body>
</html>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    // vérifie que l'adresse email existe dans la table utilisateur
    $query = "SELECT ID_Utilisateur, Nom_Utilisateur, Mail_Utilisateur FROM utilisateur WHERE Mail_Utilisateur = :email";
    $stmt = Database::connect()->prepare($query);
    $stmt->bindParam(":email", $email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user === false) {
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

    // Envoyer un email
    $subject = "Réinitialisation de votre mot de passe";
    $reset_link = "http://localhost/Jaya-Project/web/reset_password_action.php?token=" . $token;
    $body = "<p>Vous avez demandé à réinitialiser votre mot de passe. Cliquez sur le lien suivant pour le réinitialiser :</p>
            <p><a href='$reset_link'>$reset_link</a></p>
            <p>Si vous n'avez pas demandé de réinitialisation de mot de passe, veuillez ignorer cet email.</p>";
    $send_email_status = send_email($email, $subject, $body);

    if (strpos($send_email_status, "Erreur lors de l'envoi de l'email") !== false) {
        $_SESSION['reset_password_error'] = "Une erreur est survenue lors de l'envoi de l'e-mail de réinitialisation de mot de passe.";
        header('Location: reset_password.php');
        exit();
    } else {
        $_SESSION['reset_password_success'] = "Un e-mail de réinitialisation de mot de passe a été envoyé à votre adresse e-mail.";
        header('Location: reset_password.php');
        exit();
    }
}
?>