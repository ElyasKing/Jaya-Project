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
            include('../navigation/navbar.php'); ?>

    <div id="">
        <?php
        include("navbar.php");

        $id = $_GET['id'];
        $query = "SELECT * FROM `invite` WHERE `ID_Invite`='" . $id . "'";
        $result = $db->query($query);
        $user = $result->fetch();

        $Nom = $user['Nom_Invite'];
        $Mail = $user['Mail_Invite'];
        $Entreprise = $user['Entreprise_Invite'];
        $Telephone = $user['Telephone_Invite'];
        $est_enseignant = $user['EstEnseignant_Invite'] == 'oui';
        $est_professionnel = $user['EstProfessionel_Invite'] == 'oui';


        $db = Database::disconnect();
        ?>

        <div class="container">
            <br>
            <br>
            <h4 class="text-center">Modifier un Invité</h4>
            <br>
            <br>
            <form id="myForm" action="guestManagementCheckFormModify_scolarite.php" method="post" onsubmit="return checkForm(this);">
                <div class="row d-flex justify-content-center">
                    <div class="col-12 col-md-8 col-lg-6 col-xl-10">
                        <div class="card shadow-2-strong css-login">
                            <div class="card-body p-5">
                                <div class='row'>
                                    <div class="col">
                                        <input type="hidden" class="form-control" name="id" value="<?= $id ?>">
                                        <label for="pw1" class="form-label">Nom/Prénom de l'invité : </label>
                                        <input class="form-control" type="text" id="nom" name="nom" value="<?= $Nom ?>" required>
                                    </div>
                                </div>
                                <div class='row'>
                                    <div class="col">
                                        <label for="pw1" class="form-label">Entreprise de l'invité : </label>
                                        <input class="form-control" type="text" id="entreprise" name="entreprise" value="<?= $Entreprise ?>" required>
                                    </div>
                                </div>
                                <div class='row'>
                                    <div class="col">
                                        <label for="pw1" class="form-label">Email de l'invité : </label>
                                        <input type="email" id="email" class="form-control" name="email" value="<?= $Mail ?>" required>
                                    </div>
                                </div>
                                <div class='row'>
                                    <div class="col">
                                        <label for="pw1" class="form-label">Téléphone de l'invité : </label>
                                        <input class="form-control" type="text" id="tel" name="tel" value="<?= $Telephone ?>">
                                    </div>
                                </div>
                                <br>
                                <div class='row'>
                                    <div class="switch d-flex flex-wrap">
                                        <div class="mb-3 flex-grow-1">
                                            <label for="tuteurUniversitaire" class="form-label">Est enseignant</label>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" name="est_enseignant" id="est_enseignant" <?= $est_enseignant ? 'checked' : '' ?>>
                                            </div>
                                        </div>
                                        <div class="mb-3 flex-grow-1">
                                            <label for="etudiant" class="form-label">Est professionnel</label>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" name="est_professionnel" id="est_professionnel" <?= $est_professionnel ? 'checked' : '' ?>>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <button class="btn me-md-3 bg" type="submit">Modifier</button>
                                    <a type="button" href="guestManagement_scolarite.php" class="btn me-md-3 bg">Retour</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</body>

</html>