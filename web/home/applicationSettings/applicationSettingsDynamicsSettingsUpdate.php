<?php
include("../../../application_config/db_class.php");
include("../../../fonctions/functions.php");
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
    include("../navigation/header.php");
    ?>
</head>

<body>
    <?php
    include('../navigation/navbar.php');

    $db = Database::connect();

    $id = $_GET['id'];

    $query = "SELECT 
        ID_param, 
        Nom_param, 
        NbPoint_param 
    FROM parametres WHERE ID_param = $id";
    $statement = $db->query($query);
    $dynamicSetting = $statement->fetch();

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
        <h4 class="text-center">Modifier un critère d'évaluation</h4>
        <br>
        <br>
        <form action="applicationSettingsCheckDynamicsSettingsUpdate.php" method="post">
            <div class="row d-flex justify-content-center">
                <div class="col-12 col-md-8 col-lg-6 col-xl-10">
                    <div class="card shadow-2-strong css-login">
                        <div class="card-body p-5">
                            <div class='row'>
                                <div class="col">
                                    <p class="text-center"><span style="color: red;" class="bi bi-exclamation-triangle-fill"></span> Si la somme des points venait à dépasser 20, la modification ne serait pas prise en compte.</p>
                                </div>
                            </div>
                            <div class='row'>
                                <div class="col">
                                    <input type="hidden" class="form-control" name="id" value="<?= $dynamicSetting['ID_param'] ?>">
                                    <input type="hidden" class="form-control" name="sumOfpoints" value="<?= $sumOfpoints ?>">
                                    <label for="nom" class="form-label">Description du critère d'évaluation oral</label>
                                    <textarea required type="text" class="form-control" name="settingName"><?= $dynamicSetting['Nom_param'] ?></textarea>
                                </div>
                            </div>
                            <div class='row'>
                                <div class="col">
                                    <label for="point" class="form-label">Nombre de points</label>
                                    <input required type="number" class="form-control" name="newPoints" min="0.1" step="0.01" max="<?= $inputMax ?>" value="<?= $dynamicSetting['NbPoint_param'] ?>">
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col">
                                    <p class="form-label">Nombre de points cumulés actuellement : <?= $sumOfpoints ?> / 20</p>
                                </div>
                            </div>
                            <div class="text-center">
                                <button class="btn me-md-3 bg" type="submit">Modifier</button>
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