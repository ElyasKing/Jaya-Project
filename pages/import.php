<?php

require '../vendor/autoload.php';

session_start();
include "../application_config/db_class.php";
include 'header.php';
include 'navbar.php';


$conn = Database::connect();

function generatePassword()
{
  $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
  $password = array();
  $alphaLength = strlen($alphabet) - 1;
  for ($i = 0; $i < 8; $i++) {
    $n = rand(0, $alphaLength);
    $password[] = $alphabet[$n];
  }
  return implode($password);
}


if (isset($_POST["Import"])) {

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
          $emailEtudiant = $sheetData[$i][8];
          $nomEtudiant = $sheetData[$i][10];
          $prenomEtudiant = $sheetData[$i][11];
          $nomEtudiant = $prenomEtudiant . "" . $nomEtudiant;
          $nomFeuilles = $spreadsheet->getSheetNames();
          $promotion = $nomFeuilles[$j];
          //On prend la date en string et on troncate pour avoir l'année
          $dateString = $sheetData[$i][14];
          $anneeString = substr($dateString, -4);
          //On le passe en int pour le calcul
          $anneeInt = (int) $anneeString;
          $anneePlusDeux = $anneeInt + 2;
          $anneePlusDeuxString = (string) $anneePlusDeux;
          $annee = $anneeString . "-" . $anneePlusDeuxString;

          $mdp = generatePassword();



          $queryVerify = "SELECT * FROM utilisateur WHERE Mail_Utilisateur LIKE '$emailEtudiant'";
          $verify = $conn->query($queryVerify);
          $nombre = $verify->fetchAll();



          if (count($nombre) == 0) {
            $queryEtudiant = "INSERT INTO utilisateur(Nom_Utilisateur, Mail_Utilisateur, MDP_Utilisateur, Promo_Utilisateur, Annee_Utilisateur) 
          VALUES('$nomEtudiant', '$emailEtudiant', '$mdp', '$promotion', '$annee')";

            $conn->query($queryEtudiant);

            $queryIdEtudiant = "SELECT Id_Utilisateur FROM utilisateur WHERE Mail_Utilisateur LIKE '$emailEtudiant'";
            $resultatEtudiant = $conn->query($queryIdEtudiant);
            $etudiant = $resultatEtudiant->fetch();

            $idEtudiant = $etudiant['Id_Utilisateur'];

            $nomTuteur = $sheetData[$i][87];
            $emailTuteur = $sheetData[$i][88];

            if ($nomTuteur != NULL && $emailTuteur != NULL) {

              $mdpTuteur = generatePassword();

              $queryTuteur = "INSERT IGNORE INTO utilisateur(Nom_Utilisateur, Mail_Utilisateur, MDP_Utilisateur) VALUES('$nomTuteur', '$emailTuteur', '$mdpTuteur')";
              $conn->query($queryTuteur);



              $queryIdTuteur = "SELECT Id_Utilisateur FROM utilisateur WHERE Mail_Utilisateur LIKE '$emailTuteur'";
              $resultatTuteur = $conn->query($queryIdTuteur);
              $tuteur = $resultatTuteur->fetch();
              $idTuteur = $tuteur['Id_Utilisateur'];

              $queryEtudiantTuteur = "INSERT INTO etudiant_tuteur(Id_etudiant, Id_tuteur) VALUES ('$idEtudiant', '$idTuteur')";
              $conn->query($queryEtudiantTuteur);
            }

            $nomMA = $sheetData[$i][65];
            $emailMa = $sheetData[$i][66];
            $entreprise = $sheetData[$i][57];
            $adresse = $sheetData[$i][63];
            $mots = explode(' ', $adresse);
            $ville = array_pop($mots);

            $queryMA = "INSERT IGNORE INTO invite(Nom_Invite, Mail_Invite, Entreprise_Invite, Ville_Invite, EstEnseignant_Invite, EstProfessionel_Invite) 
          VALUES ('$nomMA', '$emailMa', '$entreprise', '$ville', 'non', 'oui')";
            $conn->query($queryMA);

            $queryIdMa = "SELECT Id_Invite FROM invite WHERE Mail_Invite LIKE '$emailMa'";
            $resultatMA = $conn->query($queryIdMa);
            $ma = $resultatMA->fetch();
            $idMa = $ma['Id_Invite'];

            $queryMaEtudiant = "INSERT INTO est_apprenti(Id_Utilisateur, Id_Invite) VALUES('$idEtudiant', '$idMa')";
            $conn->query($queryMaEtudiant);
          }
        }
      }
    }
  }
}

header("Location: suiviEtudiants.php");
