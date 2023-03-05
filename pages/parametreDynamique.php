<?php
include "../application_config/db_class.php";
include "../application_config/get_connectUser.php";
include 'header.php';
include 'navbar.php';

$conn = Database::connect();

$query = "SELECT ID_param, Nom_param, NbPoint_param FROM parametres
WHERE NbPoint_Param IS NOT NULL";
$result = $conn->query($query);
$parametres = $result->fetchAll();

$queryPoints = "SELECT ID_param, NbPoint_param FROM parametres
WHERE NbPoint_param IS NOT NULL";
$resultat = $conn->query($queryPoints);
$nbPoints = $resultat->fetchAll();

$totalPoint = 0;
foreach ($nbPoints as $point) {
    $totalPoint = $totalPoint + $point['NbPoint_param'];
}

if (isset($_GET['status'])) {
    if ($_GET['status'] == 'success') {
        echo '<h3 class="bg-success p-2">Les paramètres ont bien été enregistrés</h3>';
    }
    if ($_GET['status'] == 'fail') {
        echo '<h3 class="bg-danger p-2">Vos paramètres excèdent 20 points</h3>';
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Pramètres dynamiques</title>
    <link rel="stylesheet" href="../css/MDB/css/datatables.min.css">
</head>

<body>

    <div class="container">

        <div class="row text-center mt-3">
            <div class="col-4">
                <a href="parametreDynamique.php" class="btn btn-primary">Paramètres dynamiques</a>
            </div>
            <div class="col-4">
                <a href="parametreFixe.php" class="btn btn-info">Paramètres fixes</a>
            </div>
        </div>
        <div class="row">
            <div class="col-9 mt-3">
                <table id='dtVisuData' class="table mt-5  table-responsive">
                    <thead class="table-primary">
                        <th>Description du critère d'évaluation oral</th>
                        <th>Nombre de points</th>
                        <th></th>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($parametres as $parametre) {

                        ?>

                            <td><?php echo $parametre['Nom_param']; ?></td>
                            <td><?php echo $parametre['NbPoint_param']; ?></td>

                            <td>
                                <a href="formUpdateParamDynamique.php?id=<?php echo $parametre['ID_param'] ?>">
                                    <i class="bi bi-pencil-fill"></i></a>
                                <a href="deleteParamDynamique.php?id=<?php echo $parametre['ID_param'] ?>"><i class="bi bi-trash-fill"></i></a>
                            </td>

                            </tr>

                        <?php
                        }
                        ?>
                    </tbody>
                </table>


            </div>
            <div class="col-3">
                <?php
                if ($totalPoint > 20) {
                    echo '<h3 class="text-danger mt-5 text-center">Attention, la somme des points est égale à plus de 20</h3>';
                }
                ?>
            </div>
        </div>
        <div class="float-right">
            <a href="formCreateParamDynamique.php" class="btn btn-primary">Ajouter un critère</a>
        </div>
    </div>

    <script src="../css/MDB/js/datatables.min.js"></script>
    <script src="../css/MDB/js/app.js"></script>
</body>

</html>