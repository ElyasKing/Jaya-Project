<?php
include("../application_config/db_class.php");
include("../fonctions/functions.php");
session_start();
?>

<!DOCTYPE html>
<html>

<head>
    <?php
    include("header.php");
    $db = Database::connect();
    ?>
</head>

<body>
    <div class="content">
        <div class="bar">
            <span class="sphere"></span>
        </div>
        <div id="content">
            <?php
            include("navbar.php");
            ?>
            <div class="container-fluid space">
                <h2 class="center colored">Compte</h2>
                <hr>
                <br>
                <br>
                <?php
                // Requête SQL pour récupérer les informations de l'utilisateur correspondant à l'email fourni
                $query = "SELECT * FROM utilisateur WHERE ID_Utilisateur = " . $_SESSION['user_id'];
                $result = $db->query($query);
                $user = $result->fetch();

                $name = explode(" ", $user['Nom_Utilisateur']);

                if ($user) {
                ?>
                    <form method="post">
                        <label for="nom">Nom :</label>
                        <input type="text" id="nom" name="nom" value=<?php print($name[1]) ?> disabled>
                        <br>
                        <label for="Prenom">Prenom :</label>
                        <input type="text" id="prenom" name="prenom" value=<?php print($name[0]) ?> disabled>
                        <br>
                        <label for="email">Email :</label>
                        <input type="text" id="email" name="email" <?php print($user['Mail_Utilisateur']) ?> required>
                        <br>
                        <label for="password">Mot de passe :</label>
                        <input type="password" id="password" name="password" required>
                        <br>
                        <label for="password_confirm">Confirmer mot de passe : </label>
                        <input type="password" id="password_confirm" name="password_confirm" required>
                        <br>
                        <input type="submit" value="Confirmer">
                    </form>
                <?php
                } else {
                    // Utilisateur non trouvé
                    $error_message = "Aucun utilisateur trouvé";
                    echo $error_message;
                }
                ?>
            </div>
        </div>
    </div>
</body>

</html>