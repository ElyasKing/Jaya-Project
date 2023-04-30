<?php
include("../application_config/db_class.php");
include("../fonctions/functions.php");
session_start();

if(!isConnectedUser()){
    $_SESSION['success'] = 2;
    header("Location: login.php");
}
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
                        <div class="row d-flex justify-content-center">
                            <div class="col-12 col-md-8 col-lg-6 col-xl-10">
                                <div class="card shadow-2-strong css-login">
                                    <div class="card-body p-5">
                                        <div class='row'>
                                            <div class="col">
                                                <input type="hidden" class="form-control" name="id" value="<?= $user["ID_Utilisateur"] ?>">
                                                <p class="form-label">Utilisateur : <?= $user["Nom_Utilisateur"] ?></p>
                                            </div>
                                        </div>
                                        <div class='row'>
                                            <div class="col">
                                                <label for="pw1" class="form-label">Nouveau mot de passe :</label>
                                                <input id="field_pwd1" title="Un mot de passe fort doit contenir : 8 caractères minimum, des minuscules, des majuscules, des chiffres et des caractères spéciaux." required type="password" class="form-control" minlength="8" pattern="^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[^a-zA-Z\d\s]).{8,}$" name="pw1">
                                            </div>
                                        </div>
                                        <div class='row'>
                                            <div class="col">
                                                <label for="pw2" class="form-label">Confirmer le nouveau mot de passe :</label>
                                                <input id="field_pwd2" title="Entrez le même mot de passe que ci-dessus" required type="password" class="form-control" minlength="8" pattern="^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[^a-zA-Z\d\s]).{8,}$" name="pw2">
                                            </div>
                                        </div>
                                        <br>
                                        <div class="text-center">
                                            <button class="btn me-md-3 bg" type="submit">Modifier</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>

<script src="../js/toastr.min.js"></script>
<script>
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": false,
        "progressBar": true,
        "positionClass": "toast-top-center",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "7000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }
</script>
<?php
$success = $_SESSION['success'];
switch ($success) {
    case 1:
        echo '<script>toastr.success("Vous avez changé votre mot de passe.");</script>';
        break;
    case 11:
        echo '<script>toastr.error("Veillez à ce que les deux mots de passe saisis soient identiques !");</script>';
        break;
    default:
        // rien
}
$_SESSION['success'] = 0;
?>
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