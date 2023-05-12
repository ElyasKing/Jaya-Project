<?php
include("../application_config/db_class.php");
include("../fonctions/functions.php");
require '../vendor/autoload.php';

session_start();


$conn = Database::connect();


$file_mimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

if (isset($_FILES['file']['name']) && in_array($_FILES['file']['type'], $file_mimes)) {

    $arr_file = explode('.', $_FILES['file']['name']);
    $extension = end($arr_file);

    if ('csv' == $extension) {
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
    } else {
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx;
    }

    $spreadsheet = $reader->load($_FILES['file']['tmp_name']);


    $nombreSheets = $spreadsheet->getSheetCount();


    for ($j = 0; $j < $nombreSheets; $j++) {
        $sheetData =  $spreadsheet->getSheet($j)->toArray();


        for ($i = 1; $i < count($sheetData); $i++) {
            if ($sheetData[$i][0] != NULL) {
                $email = $sheetData[$i][1];
                $nom = $sheetData[$i][0];
                $telephone = $sheetData[$i][2];
                $enseignant = $sheetData[$i][3];
                $pro = $sheetData[$i][4];
                $entreprise = $sheetData[$i][5];
                $ville = $sheetData[$i][6];
                $queryVerify = "SELECT * FROM invite WHERE Mail_Invite LIKE '$email'";
                $verify = $conn->query($queryVerify);
                $nombre = $verify->fetchAll();

                if (count($nombre) == 0) {

                    $queryMA = "INSERT INTO invite(Nom_Invite, Mail_Invite, Entreprise_Invite, Ville_Invite, EstEnseignant_Invite, EstProfessionel_Invite) VALUES ('$nom', '$email', '$entreprise', '$ville', '$enseignant', '$pro')";
                    $conn->query($queryMA);
                }
            }
        }
    }

    $_SESSION['success'] = 4;
} else {
    $_SESSION['error'] = 1;
}


header("Location: guestManagement_scolarite.php");
die();
