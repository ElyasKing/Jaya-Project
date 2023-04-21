<?php
require_once("../application_config/db_class.php");

ini_set('SMTP', '127.0.0.1');
ini_set('smtp_port', 25);

if(isset($_POST['submit'])){

    $email = $_POST['email'];

    // vérifie que l'adresse email existe dans la table Mail_Utilisateur
    $query = "SELECT id_utilisateur, nom, prenom FROM Mail_Utilisateur WHERE email = :email";
    $stmt = Database::connect()->prepare($query);
    $stmt->bindParam(":email", $email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if($user === false){
        $_SESSION['reset_password_error'] = "Adresse email non trouvée.";
        header('Location: reset_password.php');
        exit();
    }

    // génère un token unique et l'insère dans la table password_reset_tokens
    $token = bin2hex(random_bytes(16));
    $query = "INSERT INTO password_reset_tokens (id_utilisateur, token) VALUES (:id_utilisateur, :token)";
    $stmt = Database::connect()->prepare($query);
    $stmt->bindParam(":id_utilisateur", $user['id_utilisateur']);
    $stmt->bindParam(":token", $token);
    $stmt->execute();

    // envoie un e-mail à l'utilisateur avec le lien de réinitialisation
    $to = $email;
    $subject = "Réinitialisation de votre mot de passe JAYA";
    $message = "Bonjour " . $user['prenom'] . " " . $user['nom'] . ",\n\n";
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