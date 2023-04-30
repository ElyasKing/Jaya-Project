<?php
include("../application_config/db_class.php");
include("../fonctions/functions.php");
session_start();

if (!isConnectedUser()) {
    $_SESSION['success'] = 2;
    header("Location: login.php");
}
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
            $query = "SELECT ID_Invite, Nom_Invite, Mail_Invite,Entreprise_Invite, Ville_Invite FROM invite WHERE estProfessionel_Invite='oui';";
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
            <div class="container">
                <br>
                <br>
                <h4 class="text-center">Modifier les informations d'un étudiant</h4>
                <br>
                <br>
                <form action="indexCheckStudentUpdate_administrateur.php" method="post">
                    <div class="row d-flex justify-content-center">
                        <div class="col-12 col-md-8 col-lg-6 col-xl-10">
                            <div class="card shadow-2-strong css-login">
                                <div class="card-body p-5">
                                    <div class='row'>
                                        <div class="col">
                                            <p class="form-label">Nom : <?= $etudiant['nom_Utilisateur'] ?></p>
                                        </div>
                                        <div class="col">
                                            <p class="form-label">Email : <?= $etudiant['Mail_Utilisateur'] ?></p>
                                        </div>
                                    </div>
                                    <br>
                                    <div class='row'>
                                        <div class="col">
                                            <label for="nomTuteur" class="form-label">Nom du tuteur universitaire</label>
                                            <select class="form-select" name="nomTuteur" id="nomTuteur" required>
                                                <option value=""></option>
                                                <?php foreach ($liste_tuteur as $tuteur) { ?>
                                                    <option value="<?= $tuteur['Nom_Utilisateur'] ?>" data-email="<?= $tuteur['Mail_Utilisateur'] ?>" <?php if ($tuteur_universitaire && $tuteur['Nom_Utilisateur'] == $tuteur_universitaire['nom_Utilisateur']) echo 'selected'; ?>>
                                                        <?= $tuteur['Nom_Utilisateur'] ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="col">
                                            <label for="emailTuteur" class="form-label">Email du tuteur universitaire</label>
                                            <input type="email" class="form-control" name="emailTuteur" id="emailTuteur" value="<?= $Mail_Utilisateur ?>" readonly>
                                        </div>
                                    </div>
                                    <br>
                                    <div class='row'>
                                        <div class="col">
                                            <label for="promo" class="form-label">Promo :</label>
                                            <select class="form-select" name="promo">
                                                <option value="M1 MIAGE" <?php if ($etudiant['Promo_Utilisateur'] == 'M1 MIAGE') echo 'selected'; ?>>M1 MIAGE</option>
                                                <option value="M2 MIAGE" <?php if ($etudiant['Promo_Utilisateur'] == 'M2 MIAGE') echo 'selected'; ?>>M2 MIAGE</option>
                                            </select>
                                        </div>
                                        <div class="col">
                                            <label for="entreprise" class="form-label">Entreprise :</label>
                                            <input type="text" class="form-control" name="entreprise" value="<?= $etudiant['Entreprise_Invite'] ?>">
                                        </div>
                                    </div>
                                    <br>
                                    <div class='row'>
                                        <div class="col">
                                            <label for="ville" class="form-label">Ville de l'entreprise :</label>
                                            <input type="text" class="form-control" name="ville" value="<?= $etudiant['Ville_Invite'] ?>">
                                        </div>
                                    </div>
                                    <br>
                                    <div class='tuteurs-entreprise-container'>
                                        <?php
                                        $compteur = 0; // initialisation de compteur à 0
                                        if (empty($tuteur_entreprise)) {
                                        ?>
                                            <div class="row tuteur-entreprise">
                                                <div class="col">
                                                    <label for="nomTE" class="form-label">Nom du tuteur entreprise :</label>
                                                    <select class="form-select" name="nomte[]" required>
                                                        <option value=""></option>
                                                        <?php foreach ($liste_invite as $invite) { ?>
                                                            <option value="<?= $invite['Nom_Invite'] ?>" data-email-te="<?= $invite['Mail_Invite'] ?>" data-id-te="<?= $invite['ID_Invite'] ?>" data-ville-te="<?= $invite['Ville_Invite'] ?>" data-entreprise-te="<?= $invite['Entreprise_Invite'] ?>"  <?= (!$invite['Nom_Invite']) ? 'selected' : '' ?>>
                                                                <?= $invite['Nom_Invite'] ?>
                                                            </option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <div class="col">
                                                    <label for="emailTE" class="form-label">Email du tuteur entreprise :</label>
                                                    <input type="email" class="form-control" name="emailte[]" readonly>
                                                </div>
                                            </div>
                                            <?php
                                        } else {
                                            foreach ($tuteur_entreprise as $index => $TE) {
                                                $compteur = $index + 1;
                                            ?>
                                                <div class="row tuteur-entreprise">
                                                    <div class="col">
                                                        <label for="nomTE<?= $compteur ?>" class="form-label">Nom du tuteur entreprise :</label>
                                                        <select class="form-select" name="nomte[]" data-index="<?= $compteur ?>" required>
                                                            <option value=""></option>
                                                            <?php foreach ($liste_invite as $invite) { ?>
                                                                <option value="<?= $invite['Nom_Invite'] ?>" data-email-te="<?= $invite['Mail_Invite'] ?>" data-id-te="<?= $invite['ID_Invite'] ?>" data-entreprise-te="<?= $invite['Entreprise_Invite'] ?>" data-ville-te="<?= $invite['Ville_Invite'] ?>" <?= ($invite['Nom_Invite'] == $TE['Nom_Invite']) ? 'selected' : '' ?>>
                                                                    <?= $invite['Nom_Invite'] ?>
                                                                </option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                    <div class="col">
                                                        <label for="emailTE<?= $compteur ?>" class="form-label">Email du tuteur entreprise :</label>
                                                        <input type="email" class="form-control" name="emailte[]" id="emailTE<?= $compteur ?>" value="<?= $TE['Mail_Invite'] ?>" readonly>
                                                    </div>
                                                </div>
                                        <?php
                                            }
                                        }
                                        ?>
                                    </div>

                                    <br>
                                    <input type="hidden" class="form-control" name="id" value="<?= $etudiant['ID_Utilisateur'] ?>">
                                    <div class="row">
                                        <div class="col">
                                            <button type="button" class="btn btn-primary add-tuteur-entreprise-button">Ajouter un tuteur entreprise</button>
                                            <button class="btn btn-danger" type="button" id="delete-all-button">Supprimer</button>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col">
                                            <label for="huitClos" class="form-label">Huit-Clos</label>
                                            <input type="checkbox" class="form-check-input" name="huitClos" value="<?= $etudiant['HuisClos_Utilisateur'] ?>" <?= ($etudiant['HuisClos_Utilisateur'] == 'oui') ? 'checked' : '' ?>>
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <button class="btn me-md-3 bg" type="submit">Modifier</button>
                                        <a type="button" href="index.php" class="btn me-md-3 bg">Retour</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
            </form>
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

    //----------------------------------------------------------

    // Récupération des éléments HTML pour l'étudiant
    var inputEntreprise = document.querySelector('input[name="entreprise"]');
    var inputVille = document.querySelector('input[name="ville"]');

    // Récupération des éléments HTML pour le tuteur entreprise
    var selectTuteurEntreprise = document.querySelectorAll('select[name="nomte[]"]');
    var emailTuteurEntreprise = document.querySelectorAll('input[name="emailte[]"]');

    // Fonction pour mettre à jour les champs entreprise et ville
    function updateEntrepriseAndVille(index, selectedTuteur) {
        const entreprise = document.querySelectorAll('input[name="entreprise"]')[index];
        const ville = document.querySelectorAll('input[name="ville"]')[index];



        if (entreprise && ville) {
            entreprise.value = selectedTuteur.getAttribute('data-entreprise-te');
            ville.value = selectedTuteur.getAttribute('data-ville-te');
        }
    }


    // Traitement pour le cas où il n'y a pas de tuteur entreprise sélectionné
    if (selectTuteurEntreprise.length > 0 && selectTuteurEntreprise[0].selectedIndex === 0) {
        selectTuteurEntreprise.forEach((select, index) => {
            select.addEventListener('input', () => {
                const selectedTuteur = select.options[select.selectedIndex];
                updateEntrepriseAndVille(index, selectedTuteur);
            });
        });
    }

    // Traitement pour le cas où il y a un ou plusieurs tuteurs entreprise sélectionnés
    selectTuteurEntreprise.forEach((select, index) => {
        select.addEventListener('change', () => {
            const selectedTuteur = select.options[select.selectedIndex];
            // Mise à jour des champs emailTuteurEntreprise, entreprise et ville avec les données de l'invité sélectionné
            emailTuteurEntreprise[index].value = selectedTuteur.getAttribute('data-email-te');
            updateEntrepriseAndVille(index, selectedTuteur);
        });

        // Au chargement de la page, on met à jour les champs emailTuteurEntreprise, entreprise et ville avec les valeurs sélectionnées par défaut
        const defaultTuteur = select.options[select.selectedIndex];
        emailTuteurEntreprise[index].value = defaultTuteur.getAttribute('data-email-te');
        updateEntrepriseAndVille(index, defaultTuteur);
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
        newDiv.classList.add('row', 'tuteur-entreprise');

        const newDiv2 = document.createElement('div');
        newDiv2.classList.add('col');

        const nomLabel = document.createElement('label');
        nomLabel.textContent = 'Nom du tuteur entreprise :';
        nomLabel.classList.add('form-label');
        const nomSelect = document.createElement('select');
        nomSelect.classList.add('form-select');
        nomSelect.name = 'nomte[]';
        nomSelect.required = true;

        const newDiv3 = document.createElement('div');
        newDiv3.classList.add('col');

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
        <?php
        foreach ($liste_invite as $invite) { ?>
            var searchText = '<?= $invite['Mail_Invite'] ?>';
            var elements = document.getElementsByTagName("*");
            var matchingElements = [];
            for (var i = 0; i < elements.length; i++) {
                if (elements[i].textContent.includes(searchText)) {
                    matchingElements.push(elements[i]);
                }
            }
            <?php if (!empty($matchingElements)) {
                continue;
            }
            ?>
            const option<?= $compteur ?> = document.createElement('option');
            option<?= $compteur ?>.value = '<?= $invite['Nom_Invite'] ?>';
            option<?= $compteur ?>.text = '<?= $invite['Nom_Invite'] ?>';
            option<?= $compteur ?>.setAttribute('data-email-te', '<?= $invite['Mail_Invite'] ?>');
            option<?= $compteur ?>.setAttribute('data-id-te', '<?= $invite['ID_Invite'] ?>');
            nomSelect.appendChild(option<?= $compteur ?>);
        <?php
            $compteur++;
        }
        ?>


        // Ajout des éléments HTML à la nouvelle div
        newDiv.appendChild(newDiv2);
        newDiv.appendChild(newDiv3);
        newDiv2.appendChild(nomLabel);
        newDiv2.appendChild(nomSelect);
        newDiv3.appendChild(emailLabel);
        newDiv3.appendChild(emailInput);

        // Ajout de la nouvelle div à la liste des tuteurs entreprise
        tuteursEntrepriseContainer.appendChild(newDiv);
    });
    //---------------------------------------

    //Delete button 
    const deleteButton = document.querySelector('#delete-all-button');
    deleteButton.addEventListener('click', () => {
        // Vérification s'il y a plus d'un couple tuteur-entreprise avant de supprimer un couple
        const nbTuteursEntreprise = document.querySelectorAll('.tuteur-entreprise').length;
        if (nbTuteursEntreprise > 1) {
            document.querySelector('.tuteurs-entreprise-container').lastElementChild.remove();
        }
    });
</script>
</script>