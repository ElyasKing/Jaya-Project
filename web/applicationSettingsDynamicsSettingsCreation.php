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
    ?>
</head>

<body>
    <?php
    include("navbar.php");

    $db = Database::connect();

    $query = getSettings("dynamique");
    $statement = $db->query($query);
    $dynamicsSettings = $statement->fetchAll();

    $sumOfpoints = 0;
    foreach ($dynamicsSettings as $points) {
        $sumOfpoints += $points['NbPoint_param'];
    }

    $differenceMax = 20 - $sumOfpoints;
    $inputMax = $dynamicSetting['NbPoint_param'] + $differenceMax;
    ?>
    <div class="container">
        <br>
        <br>
        <h4 class="text-center">Ajouter un critère d'évaluation</h4>
        <br>
        <br>
        <form action="applicationSettingsCheckDynamicsSettingsCreation.php" method="post">
            <div class="row d-flex justify-content-center">
                <div class="col-12 col-md-8 col-lg-6 col-xl-10">
                    <div class="card shadow-2-strong css-login">
                        <div class="card-body p-5">
                            <div class='row'>
                                <div class="col">
                                    <p class="text-center" style="color: red;"><span class="glyphicon glyphicon-plus"></span>Si la somme des points venait à dépasser 20, la modification ne serait pas prise en compte.</p>
                                </div>
                            </div>
                            <div class='row'>
                                <div class="col">
                                    <input type="hidden" class="form-control" name="sumOfpoints" value="<?= $sumOfpoints ?>">
                                    <label for="settingName" class="form-label">Description du critère d'évaluation oral</label>
                                    <textarea required type="text" class="form-control" name="settingName"></textarea>
                                </div>
                            </div>
                            <div class='row'>
                                <div class="col">
                                    <label for="point" class="form-label">Nombre de points</label>
                                    <input required type="number" class="form-control" name="newPoints" min="0.1" step="0.01" max="<?= $inputMax ?>">
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col">
                                    <p class="form-label" <?= ( $sumOfpoints == 20 ? 'style="color: red;"':'') ?>>Nombre de points cumulés actuellement : <?= $sumOfpoints ?> / 20</p>
                                </div>
                            </div>
                            <div class="text-center">
                                <button class="btn me-md-3 bg" type="submit">Ajouter</button>
                                <a type="button" href="applicationSettings.php" class="btn me-md-3 bg">Retour</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</body>

</html>