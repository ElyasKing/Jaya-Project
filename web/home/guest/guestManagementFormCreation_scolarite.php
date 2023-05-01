<?php
include("../../../application_config/db_class.php");
include("../../../fonctions/functions.php");
session_start();

if (!isConnectedUser()) {
    $_SESSION['success'] = 2;
    header("Location: login.php");
}
?>

<!DOCTYPE html>
<html>

<head>
    <?php
    include("../navigation/header.php");
    ?>
</head>

<body>
    <div class="content">
        <div class="bar">
            <span class="sphere"></span>
        </div>
        <div id="content">
            <?php
            include('../navigation/navbar.php');
            ?>

            <div class="container">
                <br>
                <br>
                <h4 class="text-center">Modifier un Invité</h4>
                <br>
                <br>
                <form id="myForm" action="guestManagementCheckFormCreation_scolarite.php" method="post" onsubmit="return checkForm(this);">
                    <div class="row d-flex justify-content-center">
                        <div class="col-12 col-md-8 col-lg-6 col-xl-10">
                            <div class="card shadow-2-strong css-login">
                                <div class="card-body p-5">
                                    <div class='row'>
                                        <div class="col">
                                            <label for="pw1" class="form-label">Nom/Prénom de l'invité : </label>
                                            <input class="form-control" type="text" id="nom" name="nom" required>
                                        </div>
                                    </div>
                                    <div class='row'>
                                        <div class="col">
                                            <label for="pw1" class="form-label">Entreprise de l'invité : </label>
                                            <input class="form-control" type="text" id="entreprise" name="entreprise" required>
                                        </div>
                                    </div>
                                    <div class='row'>
                                        <div class="col">
                                            <label for="pw1" class="form-label">Email de l'invité : </label>
                                            <input type="email" id="email" class="form-control" name="email" required>
                                        </div>
                                    </div>
                                    <div class='row'>
                                        <div class="col">
                                            <label for="pw1" class="form-label">Téléphone de l'invité : </label>
                                            <input class="form-control" type="text" id="tel" name="tel">
                                        </div>
                                    </div>
                                    <br>
                                    <div class='row'>
                                        <div class="switch d-flex flex-wrap">
                                            <div class="mb-3 flex-grow-1">
                                                <label for="tuteurUniversitaire" class="form-label">Est enseignant</label>
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" name="est_enseignant" id="est_enseignant">
                                                </div>
                                            </div>
                                            <div class="mb-3 flex-grow-1">
                                                <label for="etudiant" class="form-label">Est professionnel</label>
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" name="est_professionnel" id="est_professionnel">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <button class="btn me-md-3 bg" type="submit">Enregistrer</button>
                                        <a type="button" href="guestManagement_scolarite.php" class="btn me-md-3 bg">Retour</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>