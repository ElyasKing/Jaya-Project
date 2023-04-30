<?php
session_start();
include "../application_config/db_class.php";
include 'header.php';
include 'navbar.php';
$conn = Database::connect();

$query = "SELECT ID_param, Nom_param, Description_param FROM parametres
WHERE NbPoint_param IS NULL";

$result = $conn->query($query);
$parametres = $result->fetchall();

if (isset($_GET['status'])) {
    if ($_GET['status'] == 'success') {
        echo '<h3 class="bg-success p-2">Les paramètres ont bien été enregistrés</h3>';
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Paramètres</title>
</head>

<body>

    <div class="container">

        <div class="row align-item center  mt-3 ms-5">
            <div class="col-4 ">
                <a href="parametreDynamique.php" class="btn btn-info">Paramètres dynamiques</a>
            </div>
            <div class="col-4">
                <a href="parametreFixe.php" class="btn btn-primary">Paramètres fixes</a>
            </div>
        </div>
        <div class="bg-light mt-3 border p-3">


            <form action="updateParamFixe.php" method="post">

                <?php
                foreach ($parametres as $parametre) {

                    if ($parametre['Nom_param'] == "Date de début des sessions de soutenance" || $parametre['Nom_param'] == "Date de fin des sessions de soutenances") {

                ?>
                        <div class="row mt-3">
                            <div class=" col-4 mb-3">
                                <input type="hidden" class="form-control" name="id[]" value="<?= $parametre['ID_param'] ?>">
                                <label for="description" class="form-label"><?= $parametre['Nom_param'] ?></label>
                            </div>
                            <div class="col-6">
                                <input type="date" class="form-control" name="description[]" value="<?= $parametre['Description_param'] ?>">
                            </div>
                        </div>

                    <?php
                    } else {
                    ?>
                        <div class="row mt-3">
                            <div class=" col-4 mb-3">
                                <input type="hidden" class="form-control" name="id[]" value="<?= $parametre['ID_param'] ?>">
                                <label for="description" class="form-label"><?= $parametre['Nom_param'] ?></label>
                            </div>
                            <div class="col-6">
                                <input type="number" class="form-control" name="description[]" value="<?= $parametre['Description_param'] ?>">
                            </div>
                        </div>
                <?php
                    }
                }

                ?>

                <div class="text-center">
                    <button class="btn btn-info mt-3" type="submit">Modifier</button>
                </div>

            </form>
        </div>
    </div>
</body>

</html>