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
    $db = Database::connect();
    ?>
</head>

<body>
    <div class="content">
        <div class="bar">
            <span class="sphere"></span>
        </div>
        <div id="content">
            <?php
            include("navbar.php");

            $id = $_GET['id'];
            $query = getAccountInformationsById($id);
            $result = $db->query($query);
            $user = $result->fetch();

            $est_administrateur = $user['Admin_Habilitations'] == 'oui';
            $est_responsableUE = $user['ResponsableUE_Habilitations'] == 'oui';
            $est_scolarite = $user['Scolarite_Habilitations'] == 'oui';
            $est_tuteurUniversitaire = $user['TuteurUniversitaire_Habilitations'] == 'oui';
            $est_etudiant = $user['Etudiant_Habilitations'] == 'oui';

            $db = Database::disconnect();
            ?>

            <div class="container bg-light p-3">
                <h1>Modifier un compte</h1>
                <form action="accountManagerCheckUserUpdate_administrateur.php" method="post">
                    <input type="hidden" class="form-control" name="id" value="<?= $id ?>">
                    <div class="mb-3">
                        <label for="nomUtilisateur" class="form-label">Utilisateur : </label>
                        <p class="form-control-plaintext"><?= $user['Nom_Utilisateur'] ?></p>
                    </div>
                    <div class="mb-3">
                        <label for="mdp" class="form-label">Mot de passe : </label>
                        <input type="password" class="form-control" name="mdp">
                    </div>
                    <div class="switch d-flex flex-wrap">
                        <div class="mb-3 flex-grow-1">
                            <label for="admin" class="form-label">Administrateur</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="administrateur" id="admin" <?= $est_administrateur ? 'checked' : '' ?>>
                            </div>
                        </div>
                        <div class="mb-3 flex-grow-1">
                            <label for="responsableUE" class="form-label">Responsable d'UE</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="responsableUE" id="responsableUE" <?= $est_responsableUE ? 'checked' : '' ?>>
                            </div>
                        </div>
                        <div class="mb-3 flex-grow-1">
                            <label for="scolarite" class="form-label">Scolarité</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="scolarite" id="scolarite" <?= $est_scolarite ? 'checked' : '' ?>>
                            </div>
                        </div>
                        <div class="mb-3 flex-grow-1">
                            <label for="tuteurUniversitaire" class="form-label">Tuteur universitaire</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="tuteurUniversitaire" id="tuteurUniversitaire" <?= $est_tuteurUniversitaire ? 'checked' : '' ?>>
                            </div>
                        </div>
                        <div class="mb-3 flex-grow-1">
                            <label for="etudiant" class="form-label">Étudiant</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="etudiant" id="etudiant" <?= $est_etudiant ? 'checked' : '' ?>>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-info">Modifier</button>
                    <a href="accountManager_administrateur.php" class="btn btn-primary">Retour</a>
                </form>
            </div>
        </div>
    </div>
</body>

</html>