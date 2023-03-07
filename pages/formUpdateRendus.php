<?php
include "../application_config/db_class.php";
include "../application_config/get_connectUser.php";
include 'header.php';
include 'navbar.php';
$conn = Database::connect();

$id = $_GET['id'];

$query =
    "SELECT utilisateur.ID_Utilisateur, utilisateur.nom_Utilisateur, utilisateur.Mail_Utilisateur, utilisateur.Promo_Utilisateur, 
    notes_suivi.Poster_NF, notes_suivi.Remarque_NF, notes_suivi.Rapport_NF, notes_suivi.Appreciation_NF, notes_suivi.noteFinale_NF, notes_suivi.Orthographe_NF FROM utilisateur
    LEFT JOIN notes_suivi ON utilisateur.ID_Utilisateur = notes_suivi.ID_Utilisateur 
    LEFT JOIN notes_soutenance ON utilisateur.ID_Utilisateur = notes_soutenance.ID_UtilisateurEvalue
    JOIN habilitations ON utilisateur.ID_Utilisateur = habilitations.ID_Utilisateur 
    WHERE utilisateur.ID_Utilisateur = $id";
$result = $conn->query($query);
$etudiant = $result->fetch();

$poster = 0;
$rapport = 0;

if ($etudiant['Poster_NF'] == 'oui') {
    $poster = true;
}
if ($etudiant['Rapport_NF'] == 'oui') {
    $rapport = true;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Modification du suivi d'un étudiant</title>
</head>


<h1 class="mt-2 text-center">Modifier les informations d'un étudiant</h1>
<div class="container bg-light p-5 border">

    <div class="row mt-2">
        <div class="col-6">
            <h4>Nom de l'étudiant: <?= $etudiant['nom_Utilisateur'] ?> </h4>
        </div>
        <div class="col-6">
            <h4>Promo: <?= $etudiant['Promo_Utilisateur'] ?> </h4>
        </div>
    </div>

    <form action="updateSuivi.php" method="post">

        <input type="hidden" class="form-control" name="id" value="<?= $etudiant['ID_Utilisateur'] ?>">
        <div class="row mt-3">
            <div class="col-2 form-check form-switch">
                <input type="checkbox" class="form-check-input" name="poster">
                <label for="poster" class="form-label">Poster</label>
            </div>
            <div class="col-10">
                <label for="remarque" class="form-label">Remarque</label>
                <textarea type="text" class="form-control" name="remarque"><?= $etudiant['Remarque_NF'] ?></textarea>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-2 form-check form-switch">
                <label for="rapport" class="form-label">Rapport</label>
                <input type="checkbox" class="form-check-input" name="rapport">
            </div>
            <div class="col-10">
                <label for="appreciation" class="form-label">Appréciation</label>
                <textarea type="text" class="form-control" name="appreciation"><?= $etudiant['Appreciation_NF'] ?></textarea>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-3">
                <label for="orthographe" class="form-label">Orthographe (point en moins)</label>
                <input type="number" class="form-control" min="0" name="orthographe" value="<?= $etudiant['Orthographe_NF'] ?>">
            </div>
            <div class="col-3">
                <label for="suivi" class="form-label">Note de suivi</label>
                <input type="text" class="form-control" name="suivi" value="<?= $etudiant['noteFinale_NF'] ?>">
            </div>
        </div>

        <div class="text-center">
            <button class="btn btn-info mt-3" type="submit">Modifier</button>
        </div>

    </form>

</div>