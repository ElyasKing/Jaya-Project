<?php
include("../application_config/db_class.php");
session_start();
?>

<!DOCTYPE html>
<html>

<head>
    <?php
    include("header.php");
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

            <div class="container bg-light p-3">
                <h1>Créer un compte</h1>
                <form action="accountManagerCheckUserCreation_administrateur.php" method="post">
                    <input type="hidden" class="form-control" name="id" value="<?= $id ?>">
                    <div class="mb-3">
                        <label for="nomUtilisateur" class="form-label">Utilisateur : </label>
                        <input type="text" class="form-control" name="user" id="user">
                    </div>
                    <div class="mb-3">
                        <label for="mail" class="form-label">Email : </label>
                        <input type="text" class="form-control" name="mail" id="mail">
                    </div>
                    <div class="switch d-flex flex-wrap">
                        <div class="mb-3 flex-grow-1">
                            <label for="administrateur" class="form-label">Administrateur</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="admin" id="admin">
                            </div>
                        </div>
                        <div class="mb-3 flex-grow-1">
                            <label for="responsableUE" class="form-label">Responsable d'UE</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="responsableUE" id="responsableUE">
                            </div>
                        </div>
                        <div class="mb-3 flex-grow-1">
                            <label for="scolarite" class="form-label">Scolarité</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="scolarite" id="scolarite">
                            </div>
                        </div>
                        <div class="mb-3 flex-grow-1">
                            <label for="tuteurUniversitaire" class="form-label">Tuteur universitaire</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="tuteurUniversitaire" id="tuteurUniversitaire">
                            </div>
                        </div>
                        <div class="mb-3 flex-grow-1">
                            <label for="etudiant" class="form-label">Étudiant</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="etudiant" id="etudiant">
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-info" id="enregistrerBtn" disabled>Enregistrer</button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>

<script>
    const user = document.getElementById('user');
    const mail = document.getElementById('mail');
    const enregistrerBtn = document.getElementById('enregistrerBtn');

    function toggleEnregistrerBtn() {
        if (user.value && mail.value) {
            enregistrerBtn.disabled = false;
        } else {
            enregistrerBtn.disabled = true;
        }
    }

    user.addEventListener('input', toggleEnregistrerBtn);
    mail.addEventListener('input', toggleEnregistrerBtn);
</script>