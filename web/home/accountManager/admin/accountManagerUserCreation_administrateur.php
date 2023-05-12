<?php
include("../../../../application_config/db_class.php");
include("../../../../fonctions/functions.php");
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
    include("../../navigation/header.php");
    ?>
</head>

<body>
    <div class="content">
            <?php
            include('../../navigation/navbar.php');
            ?>
            <div class="container">
                <br>
                <br>
                <h4 class="text-center">Créer un compte</h4>
                <br>
                <br>
                <form action="accountManagerCheckUserCreation_administrateur.php" method="post">
                    <div class="row d-flex justify-content-center">
                        <div class="col-12 col-md-8 col-lg-6 col-xl-10">
                            <div class="card shadow-2-strong css-login">
                                <div class="card-body p-5">
                                    <div class='row'>
                                        <div class="col">
                                            <label for="nomUtilisateur" class="form-label">Utilisateur : </label>
                                            <input type="text" class="form-control" name="user" id="user">
                                        </div>
                                    </div>
                                    <div class='row'>
                                        <div class="col">
                                            <label for="mail" class="form-label">Email : </label>
                                            <input type="email" class="form-control" name="mail" id="mail">
                                        </div>
                                    </div>
                                    <br>
                                    <div class='row'>
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
                                    </div>
                                    <div class="text-center">
                                        <button type="submit" class="btn me-md-3 bg">Enregistrer</button>
                                        <a type="button" href="../accountManager_administrateur.php" class="btn me-md-3 bg">Retour</a>
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