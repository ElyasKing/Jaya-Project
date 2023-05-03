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
    $db = Database::connect();

    $query = "SELECT ID_Planning, Nom_Planning, DateSession_Planning, HeureDebutSession_Planning FROM planning WHERE ID_Planning ='" . $_SESSION['activeSchedule'] . "'";
    $statement = $db->query($query);
    $scheduleName = $statement->fetch();
    ?>
</head>

<body>
    <div id="">
        <?php
        include("navbar.php");
        ?>
        <div class="container">
            <br>
            <br>
            <h4 class="text-center">Parametrer la session</h4>
            <br>
            <br>
            <form action="scheduleCheckConfiguration_administrateur.php" method="post">
                <div class="row d-flex justify-content-center">
                    <div class="col-12 col-md-8 col-lg-6 col-xl-10">
                        <div class="card shadow-2-strong css-login">
                            <div class="card-body p-5">
                                <div class='row'>
                                    <div class="col">
                                        <input type="hidden" class="form-control" name="id" value="<?= $scheduleName['ID_Planning'] ?>">
                                        <p class="form-label">Planning : <?= $scheduleName['Nom_Planning'] ?></p>
                                    </div>
                                </div>
                                <div class='row'>
                                    <div class="col">
                                        <label for="sessionDate" class="form-label">Date de la session de soutenance :</label>
                                        <input id="sessionDate" required type="date" class="form-control" name="sessionDate" value="<?= $scheduleName['DateSession_Planning'] ?>">
                                    </div>
                                </div>
                                <div class='row'>
                                    <div class="col">
                                        <label for="sessionTime" class="form-label">Heure de début de la session de soutenance :</label>
                                        <input id="sessionTime" required type="time" class="form-control" name="sessionTime" value="<?= $scheduleName['HeureDebutSession_Planning'] ?>">
                                    </div>
                                </div>
                                <br>
                                <div class="text-center">
                                    <button class="btn me-md-3 bg" type="submit">Modifier</button>
                                    <a type="button" href="schedule_administrateur.php" class="btn me-md-3 bg">Retour</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
        </form>
    </div>
</body>

</html>