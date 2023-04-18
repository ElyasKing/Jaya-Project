<?php
include("../application_config/db_class.php");
include("../fonctions/functions.php");
session_start();
?>

<!DOCTYPE html>
<html>

<head>
    <?php
    include("header.php");
    $conn = Database::connect();
    ?>
</head>

<body>
    <div class="content">
        <div class="bar">
            <span class="sphere"></span>
        </div>
        <div id="content">
            <?php
            $id = $_GET['id'];

            $query = getStudentInformationById($id);
            $result = $conn->query($query);
            $etudiant = $result->fetch();

            // recuperer les tuteurs entreprises d'un étudiant
            $query = getStudentProfessionalTutorById($id);
            $result = $conn->query($query);
            $tuteur_entreprise = $result->fetchAll();

            // recuperer le tuteur universitaire d'un étudiant
            $query = getStudentUniversityTutorById($id);
            $result = $conn->query($query);
            $tuteur_universitaire = $result->fetch();


         if (empty($tuteur_entreprise)){
               $nom_Utilisateur = "";
               $Mail_Utilisateur="";
               $ID_Utilisateur=NULL;
            }else{
              $nom_Utilisateur = $tuteur_universitaire['nom_Utilisateur'];
              $Mail_Utilisateur=$tuteur_universitaire['Mail_Utilisateur'];
              $ID_Utilisateur=$tuteur_universitaire['ID_Utilisateur'];
            }

            $conn = Database::disconnect();
            ?>

            <div class="container bg-light p-3">
                <h1>Modifier les informations d'un étudiant</h1>
                <form action="updateEtudiantAdmin.php" method="post">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nomEtudiant" class="form-label">Nom :</label>
                            <input type="text" class="form-control" name="nomEtudiant" value="<?= $etudiant['nom_Utilisateur'] ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="emailEtudiant" class="form-label">Email :</label>
                            <input type="email" class="form-control" name="emailEtudiant" value="<?= $etudiant['Mail_Utilisateur'] ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="promo" class="form-label">Promo :</label>
                            <input type="text" class="form-control" name="promo" value="<?= $etudiant['Promo_Utilisateur'] ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="entreprise" class="form-label">Entreprise :</label>
                            <input type="text" class="form-control" name="entreprise" value="<?= $etudiant['Entreprise_Invite'] ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="ville" class="form-label">Ville de l'entreprise :</label>
                            <input type="text" class="form-control" name="ville" value="<?= $etudiant['Ville_Invite'] ?>">
                        </div>
                        <?php
                        foreach ($tuteur_entreprise as $TU) {
                        ?>
                            <div class="col-md-6 mb-3">
                                <input type="hidden" class="form-control" name="idMA" value="<?= $TU['ID_Invite'] ?>">
                                <label for="nomMA<? $compteur ?>" class="form-label">Nom du tuteur entreprise <? $compteur ?></label>
                                <input type="text" class="form-control" name="nomma[]" value="<?= $TU['Nom_Invite'] ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="emailMA<? $compteur ?>" class="form-label">Email du tuteur entreprise <? $compteur ?></label>
                                <input type="email" class="form-control" name="emailma[]" value="<?= $TU['Mail_Invite'] ?>">
                            </div>
                        <?php
                        }
                        ?>
                        <div class="col-md-6 mb-3">
                            <label for="nomTuteur" class="form-label">Nom du tuteur universitaire</label>
                            <input type="text" class="form-control" name="nomTuteur" value="<?= $nom_Utilisateur ?>" >
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="emailTuteur" class="form-label">Email du tuteur universitaire</label>
                            <input type="email" class="form-control" name="emailTuteur" value="<?= $Mail_Utilisateur ?>" >
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="huitClos" class="form-label">Huit-Clos</label>
                            <input type="checkbox" class="form-check-input" name="huitClos" value="<?= $etudiant['HuitClos_Utilisateur'] ?>">
                        </div>
                        <div class="col-md-12 mb-3">
                            <button class="btn btn-info" type="submit">Modifier</button>
                        </div>
                    </div>
                    <input type="hidden" class="form-control" name="id" value="<?= $etudiant['ID_Utilisateur'] ?>">
                    <input type="hidden" class="form-control" name="idTuteur" value="<?= $ID_Utilisateur ?>" >
                </form>
            </div>
        </div>
    </div>
</body>

</html>