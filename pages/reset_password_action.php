<?php
session_start();
require_once("../application_config/db_class.php");
include("../fonctions/functions.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Quand le formulaire est soumis, on récupère le nouveau mot de passe et le token
    $new_password = $_POST["new_password"];
    $token = $_POST["token"];

    // On vérifie le token
    $query = "SELECT * FROM password_reset_tokens WHERE token = :token";
    $stmt = Database::connect()->prepare($query);
    $stmt->bindParam(":token", $token);
    $stmt->execute();
    $reset_request = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$reset_request) {
        // Si le token n'est pas valide, on affiche un message d'erreur
        $_SESSION['reset_password_error'] = "Token de réinitialisation de mot de passe non valide.";
        header('Location: new_password.php');
        exit();
    }

    // Le token est valide, on met à jour le mot de passe
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    $query = "UPDATE utilisateur SET Mot_de_passe_Utilisateur = :password WHERE ID_Utilisateur = :user_id";
    $stmt = Database::connect()->prepare($query);
    $stmt->bindParam(":password", $hashed_password);
    $stmt->bindParam(":user_id", $reset_request['user_id']);
    $stmt->execute();

    // On supprime le token pour qu'il ne puisse plus être utilisé
    $query = "DELETE FROM password_reset_tokens WHERE token = :token";
    $stmt = Database::connect()->prepare($query);
    $stmt->bindParam(":token", $token);
    $stmt->execute();

    // On indique à l'utilisateur que le mot de passe a été réinitialisé
    $_SESSION['reset_password_success'] = "Votre mot de passe a été réinitialisé avec succès.";
    header('Location: login.php');
    exit();
} else {
    // Si le formulaire n'est pas encore soumis, on affiche le formulaire
    $token = $_GET["token"];
    ?>
    <form method="POST" action="new_password.php">
        <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
        <label for="new_password">Nouveau mot de passe :</label>
        <input type="password" id="new_password" name="new_password">
        <button type="submit">Réinitialiser le mot de passe</button>
    </form>
    <?php
}
?>