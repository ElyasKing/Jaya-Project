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

            $query = getStudentInformationById($id);
            $result = $db->query($query);
            $etudiant = $result->fetch();

            // recuperer les tuteurs entreprises d'un étudiant
            $query = getStudentProfessionalTutorById($id);
            $result = $db->query($query);
            $tuteur_entreprise = $result->fetchAll();

            // recuperer le tuteur universitaire d'un étudiant
            $query = getStudentUniversityTutorById($id);
            $result = $db->query($query);
            $tuteur_universitaire = $result->fetch();

            //récupérer la liste de tous les tuteurs universitaires 
            $query = "SELECT H.Id_Utilisateur, U.Nom_Utilisateur, U.Mail_Utilisateur FROM habilitations H JOIN utilisateur U ON U.Id_Utilisateur = H.Id_Utilisateur Where H.TuteurUniversitaire_Habilitations='oui';";
            $result = $db->query($query);
            $liste_tuteur = $result->fetchAll();

            //récupérer la liste de tous les invités 
            $query = "SELECT ID_Invite, Nom_Invite, Mail_Invite FROM invite;";
            $result = $db->query($query);
            $liste_invite = $result->fetchAll();


            if (empty($tuteur_universitaire)) {
                $nom_Utilisateur = "";
                $Mail_Utilisateur = "";
                $ID_Utilisateur = NULL;
            } else {
                $nom_Utilisateur = $tuteur_universitaire['nom_Utilisateur'];
                $Mail_Utilisateur = $tuteur_universitaire['Mail_Utilisateur'];
                $ID_Utilisateur = $tuteur_universitaire['ID_Utilisateur'];
            }

            $db = Database::disconnect();
            ?>

            <div class="container bg-light p-3">
                <h1>Modifier les informations d'un étudiant</h1>
                <form action="indexCheckStudentUpdate_administrateur.php" method="post">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nomEtudiant" class="form-label">Nom :</label>
                            <input type="text" class="form-control" name="nomEtudiant" value="<?= $etudiant['nom_Utilisateur'] ?>" readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="emailEtudiant" class="form-label">Email :</label>
                            <input type="email" class="form-control" name="emailEtudiant" value="<?= $etudiant['Mail_Utilisateur'] ?>" readonly>
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

                        <div class="tuteurs-entreprise-container">
                            <?php
                            $compteur = 1;
                            if (empty($tuteur_entreprise)) {
                            ?>
                                <div class="tuteur-entreprise">
                                    <div class="col-md-6 mb-3">
                                        <label for="nomTE" class="form-label">Nom du tuteur entreprise :</label>
                                        <select class="form-control" name="nomte[]" required>
                                            <option value=""></option>
                                            <?php foreach ($liste_invite as $invite) { ?>
                                                <option value="<?= $invite['Nom_Invite'] ?>" data-email-te="<?= $invite['Mail_Invite'] ?>" data-id-te="<?= $invite['ID_Invite'] ?>" <?= (!$tuteur_entreprise && !$invite['Nom_Invite']) ? 'selected' : '' ?>>
                                                    <?= $invite['Nom_Invite'] ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="emailTE" class="form-label">Email du tuteur entreprise :</label>
                                        <input type="email" class="form-control" name="emailte[]" readonly>
                                    </div>
                                </div>
                                <?php
                            } else {
                                foreach ($tuteur_entreprise as $TE) {
                                ?>
                                    <div class="tuteur-entreprise">
                                        <div class="col-md-6 mb-3">
                                            <label for="nomTE<?= $compteur ?>" class="form-label">Nom du tuteur entreprise <?= $compteur ?>:</label>
                                            <select class="form-control" name="nomte[]" data-index="<?= $compteur ?>" required>
                                                <option value=""></option>
                                                <?php foreach ($liste_invite as $invite) { ?>
                                                    <option value="<?= $invite['Nom_Invite'] ?>" data-email-te="<?= $invite['Mail_Invite'] ?>" <?= ($invite['Nom_Invite'] == $TE['Nom_Invite']) ? 'selected' : '' ?>>
                                                        <?= $invite['Nom_Invite'] ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="emailTE<?= $compteur ?>" class="form-label">Email du tuteur entreprise <?= $compteur ?>:</label>
                                            <input type="email" class="form-control" name="emailte[]" id="emailTE<?= $compteur ?>" value="<?= $TE['Mail_Invite'] ?>" readonly>
                                        </div>
                                    </div>
                            <?php
                                    $compteur++;
                                }
                            }
                            ?>
                        </div>
                        <div class="col-md-12 mb-3">
                            <button type="button" class="btn btn-primary add-tuteur-entreprise-button">Ajouter un tuteur entreprise</button>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="nomTuteur" class="form-label">Nom du tuteur universitaire</label>
                            <select class="form-control" name="nomTuteur" id="nomTuteur" required>
                                <option value=""></option>
                                <?php foreach ($liste_tuteur as $tuteur) { ?>
                                    <option value="<?= $tuteur['Nom_Utilisateur'] ?>" data-email="<?= $tuteur['Mail_Utilisateur'] ?>" <?php if ($tuteur_universitaire && $tuteur['Nom_Utilisateur'] == $tuteur_universitaire['nom_Utilisateur']) echo 'selected'; ?>>
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
                            <input type="checkbox" class="form-check-input" name="huitClos" value="<?= $etudiant['HuitClos_Utilisateur'] ?>" <?= ($etudiant['HuitClos_Utilisateur'] == 'oui') ? 'checked' : '' ?>>
                        </div>
                        <div class="col-md-12 mb-3">
                            <button class="btn btn-info" type="submit">Modifier</button>
                        </div>
                    </div>
                    <input type="hidden" class="form-control" name="id" value="<?= $etudiant['ID_Utilisateur'] ?>">
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

    //-----------------------------

    // Récupération de l'élément HTML qui contient toutes les div de tuteur entreprise
    const tuteursEntrepriseContainer = document.querySelector('.tuteurs-entreprise-container');

    // Récupération de l'élément HTML du bouton "+"
    const addButton = document.querySelector('.add-tuteur-entreprise-button');

    // Compteur pour générer des ids uniques pour les nouveaux éléments créés
    let compteur = 0;

    addButton.addEventListener('click', () => {
        // Création des éléments HTML pour la nouvelle div
        const newDiv = document.createElement('div');
        newDiv.classList.add('tuteur-entreprise');

        const nomLabel = document.createElement('label');
        nomLabel.textContent = 'Nom du tuteur entreprise :';
        const nomSelect = document.createElement('select');
        nomSelect.classList.add('form-control');
        nomSelect.name = 'nomte[]';
        nomSelect.required = true;

        const emailLabel = document.createElement('label');
        emailLabel.textContent = 'Email du tuteur entreprise :';
        const emailInput = document.createElement('input');
        emailInput.type = 'email';
        emailInput.classList.add('form-control');
        emailInput.name = 'emailte[]';
        emailInput.readOnly = true;

        // Ajout des événements pour mettre à jour l'email du tuteur entreprise lorsque le nom est sélectionné
        nomSelect.addEventListener('change', () => {
            const selectedOption = nomSelect.options[nomSelect.selectedIndex];
            emailInput.value = selectedOption.getAttribute('data-email-te');
        });

        // Création de l'option vide par défaut
        const defaultOption = document.createElement('option');
        defaultOption.value = '';
        defaultOption.text = '';
        nomSelect.appendChild(defaultOption);

        // Ajout des options pour chaque tuteur entreprise dans la liste
        <?php foreach ($liste_invite as $invite) { ?>
            const option<?= $compteur ?> = document.createElement('option');
            option<?= $compteur ?>.value = '<?= $invite['Nom_Invite'] ?>';
            option<?= $compteur ?>.text = '<?= $invite['Nom_Invite'] ?>';
            option<?= $compteur ?>.setAttribute('data-email-te', '<?= $invite['Mail_Invite'] ?>');
            option<?= $compteur ?>.setAttribute('data-id-te', '<?= $invite['ID_Invite'] ?>');
            nomSelect.appendChild(option<?= $compteur ?>);
        <?php $compteur++;
        } ?>

        // Ajout des éléments HTML à la nouvelle div
        newDiv.appendChild(nomLabel);
        newDiv.appendChild(nomSelect);
        newDiv.appendChild(emailLabel);
        newDiv.appendChild(emailInput);

        // Ajout de la nouvelle div à la liste des tuteurs entreprise
        tuteursEntrepriseContainer.appendChild(newDiv);
    });
</script>