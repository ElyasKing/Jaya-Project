<?php
include("../application_config/db_class.php");
include("../fonctions/functions.php");
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
    include("header.php");
    ?>
</head>

<body>
    <?php
    include("navbar.php");

    $db = Database::connect();

    $id = $_GET['id'];
    $query = getStudentMonitoringById($id);
    $result = $db->query($query);
    $user = $result->fetch();
    ?>
    <div class="container">
        <br>
        <br>
        <h4 class="text-center">Modifier le suivi recap d'un étudiant</h4>
        <br>
        <br>
        <form action="studentMonitoringCheckUpdate_users.php" method="post">
            <div class="row d-flex justify-content-center">
                <div class="col-12 col-md-8 col-lg-6 col-xl-10">
                    <div class="card shadow-2-strong css-login">
                        <div class="card-body p-5">
                            <div class='row'>
                                <div class="col">
                                    <input type="hidden" class="form-control" name="id" value="<?= $id ?>">
                                    <p class="form-label">Utilisateur : <?= $user["nom_Utilisateur"] ?></p>
                                </div>
                            </div>
                            <div class='row'>
                                <div class="col">
                                    <p class="form-label">Promo : <?= $user["Promo_Utilisateur"] ?></p>
                                </div>
                            </div>
                            <br>
                            <div class='row'>
                                <div class="col-2">
                                    <div class="switch d-flex flex-wrap">
                                        <div class="mb-1 flex-grow-1">
                                            <label for="ckeckPoster" class="form-label">Poster :</label>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" name="ckeckPoster" id="ckeckPoster" <?= $user['Poster_NF'] == "oui" ? 'checked' : '' ?>>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-10">
                                    <label class="form-label" for="remarquePoster">Remarques sur le poster :</label>
                                    <textarea type="text" class="form-control" id="remarquePoster" name="remarquePoster"><?= $user['Remarque_NF'] ?></textarea>
                                </div>
                            </div>
                            <div class='row'>
                                <div class="col-2">
                                    <div class="switch d-flex flex-wrap">
                                        <div class="mb-1 flex-grow-1">
                                            <label for="ckeckRapport" class="form-label">Rapport :</label>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" name="ckeckRapport" id="ckeckRapport" <?= $user['Rapport_NF'] == "oui" ? 'checked' : '' ?>>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-10">
                                    <label class="form-label" for="remarqueRapport">Remarques sur le rapport :</label>
                                    <textarea type="text" class="form-control" id="remarqueRapport" name="remarqueRapport"><?= $user['Appreciation_NF'] ?></textarea>
                                </div>
                            </div>
                            <br>
                            <div class='row'>
                                <div class="col">
                                    <label for="orthographe" class="form-label">Orthographe (en moins) : </label>
                                    <input id="orthographe" type="number" class="form-control" min=0 max=20 step=0.01 name="orthographe" required value="<?= $user['Orthographe_NF'] ?>" >    
                                </div>
                                <div class="col">
                                    <label for="noteSuivi" class="form-label">Note de suivi : </label>
                                    <input id="noteSuivi" type="number" class="form-control" min=0 max=20 step=0.01 name="noteSuivi" value="<?= $user['NoteFinaleTuteur_NF'] ?>" >    
                                </div>
                                <div class="col">
                                    <label for="notePP" class="form-label">Note orale calculée : </label>
                                    <input id="notePP" type="number" class="form-control" readonly value="<?= $user['noteFinaleUE_NF'] ?>">    
                                </div>
                                <div class="col">
                                    <label for="notePP" class="form-label">Note PP calculée : </label>
                                    <input id="notePP" type="number" class="form-control" readonly value="<?= $user['noteFinaleUE_NF'] ?>">    
                                </div>
                            </div>
                            <br>
                            <div class="text-center">
                                <button class="btn me-md-3 bg" type="submit">Modifier</button>
                                <a type="button" href="studentMonitoring_users.php" class="btn me-md-3 bg">Retour</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</body>

</html>

<script>
    let ckeckPoster = document.getElementById('ckeckPoster');
    let remarquePoster = document.getElementById('remarquePoster');

    if (ckeckPoster.hasAttribute('checked')) {
        remarquePoster.disabled = false;
    } else {
        remarquePoster.disabled = true;
    }

    ckeckPoster.addEventListener("click", function() {
        if (ckeckPoster.checked) {
            ckeckPoster.setAttribute("checked", "");
        } else {
            ckeckPoster.removeAttribute("checked");
        }
    });

    function toggleRemarquePoster() {
        if (ckeckPoster.checked) {
            remarquePoster.disabled = false;
        } else {
            remarquePoster.disabled = true;
        }
    }

    ckeckPoster.addEventListener('input', toggleRemarquePoster);


    let ckeckRapport = document.getElementById('ckeckRapport');
    let remarqueRapport = document.getElementById('remarqueRapport');

    if (ckeckRapport.hasAttribute('checked')) {
        remarqueRapport.disabled = false;
    } else {
        remarqueRapport.disabled = true;
    }

    ckeckRapport.addEventListener("click", function() {
        if (ckeckRapport.checked) {
            ckeckRapport.setAttribute("checked", "");
        } else {
            ckeckRapport.removeAttribute("checked");
        }
    });

    function toggleRemarqueRapport() {
        if (ckeckRapport.checked) {
            remarqueRapport.disabled = false;
        } else {
            remarqueRapport.disabled = true;
        }
    }

    ckeckRapport.addEventListener('input', toggleRemarqueRapport);
</script>