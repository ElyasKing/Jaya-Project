<?php
session_start();
include "../application_config/db_class.php";
include 'header.php';
include 'navbar.php';
$conn = Database::connect();



// Requête SQL pour récupérer les informations de l'utilisateur correspondant à l'email fourni
$query = "SELECT * FROM utilisateur WHERE ID_Utilisateur = " . $_SESSION['user_id'];
$user = $conn->query($query)->fetch();

// Requête SQL pour récupérer les informations de tous les étudiants sauf celui connecté
$query = 'SELECT Nom_Utilisateur 
FROM utilisateur 
JOIN habilitations ON utilisateur.ID_Utilisateur = habilitations.ID_Utilisateur
WHERE habilitations.Etudiant_Habilitations LIKE "oui"';
$listEtud = $conn->query($query)->fetchAll();

// Requête SQL pour récupérer les informations des paramètres
$query = "SELECT nom_param, nbpoint_param from parametres where nbpoint_param is not null";
$listparam = $conn->query($query)->fetchAll();

$conn = Database::disconnect();

$count = 0;

if (isset($_GET['status'])) {
    if ($_GET['status'] == 'errorTuteur') {
        echo '<h3 class="bg-danger p-2">Vous ne pouvez pas noter les étudiants que vous suivez</h3>';
    }
    if ($_GET['status'] == 'error') {
        echo '<h3 class="bg-danger p-2">Vous avez déjà noté cet étudiant</h3>';
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Noter un étudiant</title>
    <link rel="stylesheet" type="text/css" href="./styles.css" />
</head>

<body>
    <div class="container bg-light p-3 mt-3 border">
        <form id="note_etud_oral" method="post" action="saisieNoteSoutenanceTuteur.php">
            <h2>Noter un étudiant</h2>

            <!---personne attribuée à la session--->
            <div class="row">
                <div class="col-auto">
                    <label for="nom" class="form-label">Evaluateur :</label>
                </div>
                <div class="col-auto">
                    <p id="nom" name="nom"><?php echo ($user['Nom_Utilisateur']) ?></p>
                </div>
            </div>

            <!---liste déroulante des étudiants--->
            <label class="form-label mt-1" for="nom_etud">Nom de l'étudiant :</label>
            <select name="liste-noms" class="form-select">
                <?php foreach ($listEtud as $row) { ?>
                    <option value="<?php echo $row['Nom_Utilisateur']; ?>"><?php echo $row['Nom_Utilisateur']; ?></option>
                <?php } ?>
            </select>

            <!---liste des notes affectées à la soutenance--->
            <?php


            foreach ($listparam as $param) {
                //traitement qui va remplacer les " " par des "_" avant le passer dans tes inputs
            ?>
                <label class="form-label mt-2" for="<?php echo $param['nom_param']; ?>"><?php echo $param['nom_param']; ?>:</label>
                <div class="form-text"><?php echo "Maximum " . $param['nbpoint_param'] . " points"; ?></div>
                <input type="number" class="form-control" id="<?php echo str_replace(" ", "_", $param['nom_param']); ?>" name="<?php echo str_replace(" ", "_", $param['nom_param']); ?>" min="0" max="<?php echo $param['nbpoint_param']; ?>">

            <?php } ?>

            <!---Note finale--->
            <label for="note_finale" class="form-label mt-2">Note finale :</label>
            <input type="number" class="form-control" id="note_finale" name="note_finale" min="0" max="20" value="">

            <!---commentaire--->
            <label for="commentaire" class="form-label mt-2">Commentaire :</label>
            <textarea type="text" class="form-control" id="commentaire" name="commentaire"></textarea>
            <div class="text-center">
                <button class="btn btn-info mt-3" type="submit">Confirmer</button>
            </div>

        </form>
    </div>
</body>

</html>