<?php
include "../application_config/db_class.php";
include "../application_config/get_connectUser.php";
include 'header.php';
include 'navbar.php';
$conn = Database::connect();

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

    <h3 class="text-center">Ajouter un critère</h3>

    <div class="container bg-light mt-3 border p-4">

        <p class="text-danger">A l'ajout d'un nouveau critère, si la somme des points venaient à dépasser 20, le critère ne seras pas enregistré</p>

        <form action="createParamDynamique.php" method="post">

            <input type="hidden" class="form-control" name="total" value="<?= $totalPoint ?>">
            <label for="nom" class="form-label">Description du critère d'évaluation oral</label>
            <textarea type="text" class="form-control" name="nom"></textarea>
            <div class="mt-3">
                <label for="point" class="form-label">Nombre de points</label>
                <input type="text" class="form-control" name="point">
            </div>
            <p class="mt-3">Nombre de points actuels : <?= $totalPoint ?></p>


            <div class="text-center">
                <button class="btn btn-info mt-3" type="submit">Créer</button>
            </div>

        </form>
    </div>
</body>

</html>