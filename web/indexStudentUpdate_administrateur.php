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

            //récupérer la liste de tous les tuteurs universitaires 
            $query = "SELECT H.Id_Utilisateur, U.Nom_Utilisateur, U.Mail_Utilisateur FROM habilitations H JOIN utilisateur U ON U.Id_Utilisateur = H.Id_Utilisateur Where H.TuteurUniversitaire_Habilitations='oui';";
            $result = $conn->query($query);
            $liste_tuteur = $result->fetchAll();

            //récupérer la liste de tous les invités 
            $query = "SELECT ID_Invite, Nom_Invite, Mail_Invite FROM invite;";
            $result = $conn->query($query);
            $liste_invite = $result->fetchAll();


            if (empty($tuteur_entreprise)) {
                $nom_Utilisateur = "";
                $Mail_Utilisateur = "";
                $ID_Utilisateur = NULL;
            } else {
                $nom_Utilisateur = $tuteur_universitaire['nom_Utilisateur'];
                $Mail_Utilisateur = $tuteur_universitaire['Mail_Utilisateur'];
                $ID_Utilisateur = $tuteur_universitaire['ID_Utilisateur'];
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
                        $compteur = 1;
                        if (empty($tuteur_entreprise)) {
                        ?>
                            <div class="col-md-6 mb-3">
                                <label for="nomTE" class="form-label">Nom du tuteur entreprise</label>
                                <select class="form-control" name="nomte[]" data-index=<?= $compteur ?>>
                                    <option value="">-- Aucun tuteur entreprise enregistré --</option>
                                    <?php foreach ($liste_invite as $invite) { ?>
                                        <option value="<?= $invite['Nom_Invite'] ?>" data-email-te="<?= $invite['Mail_Invite'] ?>" <?= ($invite['Nom_Invite']) ? 'selected' : '' ?>>
                                            <?= $invite['Nom_Invite'] ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="emailTE" class="form-label">Email du tuteur entreprise</label>
                                <input type="email" class="form-control" name="emailte[]" id="emailTE" readonly>
                            </div>
                            <?php
                        } else {
                            foreach ($tuteur_entreprise as $TE) {
                            ?>
                                <div class="col-md-6 mb-3">
                                    <input type="hidden" class="form-control" name="idTE[]" value="<?= $TE['ID_Invite'] ?>">
                                    <label for="nomTE<?= $compteur ?>" class="form-label">Nom du tuteur entreprise <?= $compteur ?></label>
                                    <select class="form-control" name="nomte[]" data-index="<?= $compteur ?>">
                                        <option value="">-- Sélectionnez un nom --</option>
                                        <?php foreach ($liste_invite as $invite) { ?>
                                            <option value="<?= $invite['Nom_Invite'] ?>" data-email-te="<?= $invite['Mail_Invite'] ?>" <?= ($invite['Nom_Invite'] == $TE['Nom_Invite']) ? 'selected' : '' ?>>
                                                <?= $invite['Nom_Invite'] ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="emailTE<?= $compteur ?>" class="form-label">Email du tuteur entreprise <?= $compteur ?></label>
                                    <input type="email" class="form-control" name="emailte[]" id="emailTE<?= $compteur ?>" value="<?= $TE['Mail_Invite'] ?>" readonly>
                                </div>
                        <?php
                                $compteur++;
                            }
                        }
                        ?>
                        <div class="col-md-6 mb-3">
                            <label for="nomTuteur" class="form-label">Nom du tuteur universitaire</label>
                            <select class="form-control" name="nomTuteur" id="nomTuteur">
                                <option value="">-- Sélectionnez un nom --</option>
                                <?php foreach ($liste_tuteur as $tuteur) { ?>
                                    <option value="<?= $tuteur['Nom_Utilisateur'] ?>" data-email="<?= $tuteur['Mail_Utilisateur'] ?>" <?= ($tuteur['Nom_Utilisateur'] == $tuteur_universitaire['nom_Utilisateur']) ? 'selected' : '' ?>>
                                        <?= $tuteur['Nom_Utilisateur'] ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="emailTuteur" class="form-label">Email du tuteur universitaire</label>
                            <input type="email" class="form-control" name="emailTuteur" id="emailTuteur" value="<?= $Mail_Utilisateur ?>" readonly>
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
                    <input type="hidden" class="form-control" name="idTuteur" value="<?= $ID_Utilisateur ?>">
                </form>
            </div>
        </div>
    </div>
</body>

</html>

<script>
    // Récupération des éléments HTML
    const selectTuteur = document.querySelector('select[name="nomTuteur"]');
    const emailTuteur = document.querySelector('input[name="emailTuteur"]');

    // Écouteur d'événement pour la sélection du tuteur universitaire
    selectTuteur.addEventListener('change', () => {
        // Récupération de la valeur sélectionnée dans la liste déroulante
        const selectedTuteur = selectTuteur.options[selectTuteur.selectedIndex];

        // Mise à jour de la valeur du champ emailTuteur avec le mail du tuteur sélectionné
        emailTuteur.value = selectedTuteur.getAttribute('data-email');
    });

    // Au chargement de la page, on met à jour le champ emailTuteur avec la valeur sélectionnée par défaut
    const defaultTuteur = selectTuteur.options[selectTuteur.selectedIndex];
    emailTuteur.value = defaultTuteur.getAttribute('data-email');

    //-----------------------------------------------------------

    // Récupération des éléments HTML pour le tuteur entreprise
    var selectTuteurEntreprise = document.querySelectorAll('select[name="nomte[]"]');
    var emailTuteurEntreprise = document.querySelectorAll('input[name="emailte[]"]');

    // Écouteurs d'événement pour la sélection des tuteurs entreprise
    selectTuteurEntreprise.forEach((select, index) => {
        select.addEventListener('change', () => {
            // Récupération de la valeur sélectionnée dans la liste déroulante
            const selectedTuteur = select.options[select.selectedIndex];

            // Mise à jour de la valeur du champ emailTuteurEntreprise avec le mail du tuteur entreprise sélectionné
            emailTuteurEntreprise[index].value = selectedTuteur.getAttribute('data-email-te');
        });

        // Au chargement de la page, on met à jour le champ emailTuteurEntreprise avec la valeur sélectionnée par défaut
        const defaultTuteur = select.options[select.selectedIndex];
        emailTuteurEntreprise[index].value = defaultTuteur.getAttribute('data-email-te');
    });
</script>