<?php
include("../application_config/db_class.php");
include("../fonctions/functions.php");
session_start();

$db = Database::connect();


// Requête SQL pour récupérer les informations de tous les étudiants sauf celui connecté
$query = "SELECT U.Nom_Utilisateur FROM utilisateur U JOIN habilitations H ON U.ID_Utilisateur = H.ID_Utilisateur WHERE H.Etudiant_Habilitations ='oui'";
$listEtud = $db->query($query)->fetchAll();

// Requête SQL pour récupérer les informations des paramètres
$query = "SELECT nom_param, nbpoint_param from parametres where nbpoint_param is not null";
$listparam = $db->query($query)->fetchAll();

$conn = Database::disconnect();

?>


<!DOCTYPE html>
<html>

<head>
    <?php
    include("header.php");
    ?>
</head>


<!DOCTYPE html>
<html>

<head>
    <title>Noter un étudiant</title>
    <link rel="stylesheet" type="text/css" href="./styles.css" />
</head>

<body>
    <div class="content">
        <div class="bar">
            <span class="sphere"></span>
        </div>
        <?php include("navbar.php"); ?>
        <div class="container">
            <br><br>
            <h4 class="text-center">Noter un étudiant</h4>
            <br><br>
            <form id="note_etud_oral" method="post" action="StudentOralCheck_tuteurUniversitaire.php">
                <div class="row justify-content-center">
                    <div class="col-12 col-md-8 col-lg-6 col-xl-10">
                        <div class="card shadow-2-strong css-login">
                            <div class="card-body p-5">
                                <!---personne attribuée à la session--->
                                <div class="row">
                                    <div class="col">
                                        <p class="form-label">Utilisateur : <?php echo ($_SESSION['user_name']) ?></p>
                                    </div>
                                </div>
                                <br>
                                <!---liste déroulante des étudiants--->
                                <div class="row">
                                    <div class="col">
                                        <label for="nom_etud" class="form-label">Nom de l'étudiant :</label>

                                        <select name="liste-noms" class="form-control">
                                            <?php foreach ($listEtud as $row) { ?>
                                                <option value="<?php echo $row['Nom_Utilisateur']; ?>"><?php echo $row['Nom_Utilisateur']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <!---liste des notes affectées à la soutenance--->
                                    <?php foreach ($listparam as $param) { ?>
                                        <div class="col">
                                            <label class="form-label" for="<?php echo $param['nom_param']; ?>"><?php echo $param['nom_param']; ?>:</label>
                                            <input class="form-control" type="number" id="<?php echo str_replace(" ", "_", $param['nom_param']); ?>" name="<?php echo str_replace(" ", "_", $param['nom_param']); ?>" min="0" max="<?php echo $param['nbpoint_param']; ?>" value="<?php echo $param['nbpoint_param']; ?>">
                                        </div>
                                    <?php } ?>
                                </div>
                                <!---Note finale--->
                                <div class="row">
                                    <div class="col">
                                        <label class="form-label" for="note_finale">Note finale :</label>
                                        <input class="form-control" type="number" id="note_finale" name="note_finale" min="0" max="20" value="">
                                    </div>
                                </div>
                                <br>
                                <!---commentaire--->
                                <div class="row">
                                    <div class="col">
                                        <label class="form-label" for="commentaire">Commentaire :</label>
                                        <input class="form-control" type="text" id="commentaire" name="commentaire">
                                    </div>
                                </div>
                                <br>
                                <div class="text-center">
                                    <button class="btn me-md-3 bg" type="submit">Confirmer</button>
                                </div>
                            </div>
                        </div>
                    </div>
            </form>
        </div>
    </div>
</body>

</html>