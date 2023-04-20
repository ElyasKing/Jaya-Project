<?php
include("../application_config/db_class.php");
include("../fonctions/functions.php");
session_start();

$success = $_SESSION['success'];
switch ($success) {
    case 1:
        echo '<script>
        setTimeout(function() {
            alert("Vous avez changé votre mot de passe. Vous allez être redirigé vers le portail de connexion.");
            window.location.href = "logout.php";
        }, 0);</script>';
        break;
    case 11:
        echo '<script>alert("Veillez à ce que les deux mots de passe saisis soient identiques !");</script>';
        break;
    default:
        // rien
}
$_SESSION['success'] = 0;
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
                <div class="container">
                    <?php
                    // Requête SQL pour récupérer les informations de l'utilisateur correspondant à l'email fourni
                    $query = "SELECT * FROM utilisateur WHERE ID_Utilisateur = " . $_SESSION['user_id'];
                    $result = $db->query($query);
                    $user = $result->fetch();
                    ?>

                    <form id="myForm" action="accountManagerCheckUserUpdate_users.php" method="post" onsubmit="return checkForm(this);">
                        <div class="mb-3 mt-3">
                            <input type="hidden" class="form-control" name="id" value="<?= $user["ID_Utilisateur"] ?>">
                            <p class="form-label">Utilisateur : <?= $user["Nom_Utilisateur"] ?></p>
                        </div>
                        <div class="mb-3 mt-3">
                            <label for="pw1" class="form-label">Nouveau mot de passe :</label>
                            <input id="field_pwd1" title="Un mot de passe fort doit contenir : 8 caractères minimum, des minuscules, des majuscules, des chiffres et des caractères spéciaux." required type="password" class="form-control" minlength="8" pattern="^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[^a-zA-Z\d\s]).{8,}$" name="pw1">
                        </div>
                        <div class="mb-3 mt-3">
                            <label for="pw2" class="form-label">Confirmer le nouveau mot de passe :</label>
                            <input id="field_pwd2" title="Entrez le même mot de passe que ci-dessus" required type="password" class="form-control" minlength="8" pattern="^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[^a-zA-Z\d\s]).{8,}$" name="pw2">
                        </div>
                        <br>
                        <div class="text-center">
                            <button class="btn me-md-3 bg" type="submit">Modifier</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
</body>

</html>

<script>
    window.addEventListener("DOMContentLoaded", function(e) {

        // JavaScript form validation

        var checkPassword = function(str) {
            var re = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[^a-zA-Z\d\s]).{8,}$/;
            return re.test(str);
        };

        var checkForm = function(e) {
            if (this.pwd1.value != "" && this.pwd1.value == this.pwd2.value) {
                if (!checkPassword(this.pwd1.value)) {
                    alert("Le mot de passe que vous avez saisi n'est pas valide !");
                    this.pwd1.focus();
                    e.preventDefault();
                    return;
                }
            } else {
                alert("Veuillez vérifier que vous avez saisi et confirmé votre mot de passe !");
                this.pwd1.focus();
                e.preventDefault();
                return;
            }
            alert("Le mot de passe est valide !");
        };

        var myForm = document.getElementById("myForm");
        myForm.addEventListener("submit", checkForm, true);

        // HTML5 form validation

        var supports_input_validity = function() {
            var i = document.createElement("input");
            return "setCustomValidity" in i;
        }

        if (supports_input_validity()) {

            var pwd1Input = document.getElementById("field_pwd1");
            pwd1Input.setCustomValidity(pwd1Input.title);

            var pwd2Input = document.getElementById("field_pwd2");

            // input key handlers
            pwd1Input.addEventListener("keyup", function(e) {
                this.setCustomValidity(this.validity.patternMismatch ? pwd1Input.title : "");
                if (this.checkValidity()) {
                    pwd2Input.pattern = RegExp.escape(this.value);
                    pwd2Input.setCustomValidity(pwd2Input.title);
                } else {
                    pwd2Input.pattern = this.pattern;
                    pwd2Input.setCustomValidity("");
                }
            }, false);

            pwd2Input.addEventListener("keyup", function(e) {
                this.setCustomValidity(this.validity.patternMismatch ? pwd2Input.title : "");
            }, false);

        }

    }, false);
</script>