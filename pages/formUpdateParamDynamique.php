<?php
session_start();
include "../application_config/db_class.php";
include 'header.php';
include 'home/navigation/navbar.php';
$conn = Database::connect();

$id = $_GET['id'];
$query = "SELECT ID_param, Nom_param, NbPoint_param FROM parametres
WHERE ID_param = $id";

$result = $conn->query($query);
$parametre = $result->fetch();

$queryPoints = "SELECT ID_param, NbPoint_param FROM parametres
WHERE NbPoint_param IS NOT NULL";
$resultat = $conn->query($queryPoints);
$nbPoints = $resultat->fetchAll();

$totalPoint = 0;
foreach ($nbPoints as $point) {
    $totalPoint = $totalPoint + $point['NbPoint_param'];
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Modification d'un paramètre dynamique</title>
</head>

<body>

    <h3 class="text-center">Modifier un critère</h3>

    <div class="container bg-light mt-3 border p-4">

        <form action="updateParamDynamique.php" method="post">

            <input type="hidden" class="form-control" name="id" value="<?= $parametre['ID_param'] ?>">
            <input type="hidden" class="form-control" name="total" value="<?= $totalPoint ?>">
            <label for="nom" class="form-label">Description du critère d'évaluation oral</label>
            <textarea type="text" class="form-control" name="nom"><?= $parametre['Nom_param'] ?></textarea>
            <div class="mt-3">
                <label for="point" class="form-label">Nombre de points</label>
                <input type="number" class="form-control" name="point" min="0.1" step="0.01" value="<?= $parametre['NbPoint_param'] ?>">
            </div>
            <p class="mt-3">Nombre de points actuels : <?= $totalPoint ?></p>


            <div class="text-center">
                <button class="btn btn-info mt-3" type="submit">Modifier</button>
            </div>

        </form>
    </div>
</body>

</html>