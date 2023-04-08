<?php
session_start();
include "../application_config/db_class.php";
include 'header.php';
include 'navbar.php';

$conn = Database::connect();

?>

<!DOCTYPE html>
<html lang="en">


<body>

    <div class="container bg-light mt-3 p-5">
        <div class="text-center text-primary">
            <h3>Importation d'un fichier</h3>
        </div>

        <form class="form-horizontal" action="import.php" method="post" name="upload_excel" enctype="multipart/form-data">
            <div class="form-group mt-3">
                <label class="col-md-4 control-label" for="filebutton">Veuillez sélectionner un fichier</label>
                <div class="col-md-4">
                    <input type="file" name="file" id="file" class="input-large">
                </div>
            </div>

            <div class="text-center mt-2">
                <button type="submit" id="submit" name="Import" class="btn btn-primary button-loading">Import</button>
            </div>

    </div>

</body>

</html>