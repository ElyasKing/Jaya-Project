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

            $id = $_GET['id'];
            $query = getAccountInformationsById($id);
            $result = $db->query($query);
            $user = $result->fetch();

            $est_administrateur = $user['Admin_Habilitations'] == 'oui';
            $est_responsableUE = $user['ResponsableUE_Habilitations'] == 'oui';
            $est_scolarite = $user['Scolarite_Habilitations'] == 'oui';
            $est_tuteurUniversitaire = $user['TuteurUniversitaire_Habilitations'] == 'oui';
            $est_etudiant = $user['Etudiant_Habilitations'] == 'oui';

            $db = Database::disconnect();
            ?>

            <!-- <div class="container">
                            <br>
                            <br>
                            <h4 class="text-center">Modifier un compte</h4>
                            <br>
                            <br>
                            <form action="accountManagerCheckUserUpdate_administrateur.php" method="post">
                                <div class="row d-flex justify-content-center">
                                    <div class="col-12 col-md-8 col-lg-6 col-xl-10">
                                        <div class="card shadow-2-strong css-login">
                                            <div class="card-body p-5">
                                                <input type="hidden" class="form-control" name="id" value="<?= $id ?>">
                                                <div class='row'>
                                                    <div class="col">
                                                    <label for="nomUtilisateur" class="form-label">Utilisateur : </label>
                                                    <p class="form-control"><?= $user['Nom_Utilisateur'] ?></p> -->

            <div class="container bg-light p-3">
                <h1>Modifier un compte</h1>
                <form id="myForm" action="accountManagerCheckUserUpdate_administrateur.php" method="post" onsubmit="return checkForm(this);">
                    <input type="hidden" class="form-control" name="id" value="<?= $id ?>">
                    <div class="mb-3">
                        <label for="nomUtilisateur" class="form-label">Utilisateur : </label>
                        <p class="form-control-plaintext"><?= $user['Nom_Utilisateur'] ?></p>
                    </div>
                    <div class="mb-3">
                        <label for="pw1" class="form-label">Mot de passe : </label>
                        <input id="field_pwd1" title="Un mot de passe fort doit contenir : 8 caractères minimum, des minuscules, des majuscules, des chiffres et des caractères spéciaux." required type="password" class="form-control" minlength="8" pattern="^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[^a-zA-Z\d\s]).{8,}$" name="pw1">
                    </div>
                    <div class="switch d-flex flex-wrap">
                        <div class="mb-3 flex-grow-1">
                            <label for="admin" class="form-label">Administrateur</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="administrateur" id="admin" <?= $est_administrateur ? 'checked' : '' ?>>
                            </div>
                        </div>
                        <div class="mb-3 flex-grow-1">
                            <label for="responsableUE" class="form-label">Responsable d'UE</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="responsableUE" id="responsableUE" <?= $est_responsableUE ? 'checked' : '' ?>>
                            </div>
                        </div>
                        <div class="mb-3 flex-grow-1">
                            <label for="scolarite" class="form-label">Scolarité</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="scolarite" id="scolarite" <?= $est_scolarite ? 'checked' : '' ?>>
                            </div>
                        </div>
                        <div class="mb-3 flex-grow-1">
                            <label for="tuteurUniversitaire" class="form-label">Tuteur universitaire</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="tuteurUniversitaire" id="tuteurUniversitaire" <?= $est_tuteurUniversitaire ? 'checked' : '' ?>>
                            </div>
                        </div>
                        <div class="mb-3 flex-grow-1">
                            <label for="etudiant" class="form-label">Étudiant</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="etudiant" id="etudiant" <?= $est_etudiant ? 'checked' : '' ?>>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-info">Modifier</button>
                    <a href="accountManager_administrateur.php" class="btn btn-primary">Retour</a>
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
            if (this.pwd1.value != "") {
                if (!checkPassword(this.pwd1.value)) {
                    alert("Le mot de passe que vous avez saisi n'est pas valide !");
                    this.pwd1.focus();
                    e.preventDefault();
                    return;
                }
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

            // input key handlers
            pwd1Input.addEventListener("keyup", function(e) {
                this.setCustomValidity(this.validity.patternMismatch ? pwd1Input.title : "");
            }, false);
        }

    }, false);
</script>