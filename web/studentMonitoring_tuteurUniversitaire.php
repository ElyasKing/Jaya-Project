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
            ?>

            <div class="container-fluid">
                <h2 class="center colored">Suivi Recap</h2>
                <hr>
                <br>
                <br>
                <?php
                $db = Database::connect();

                //infos suivi recap
                $query = getStudentMonitoringForTuteurUniversitaire($_SESSION["user_id"]);
                $statement = $db->query($query);
                $studentsList = $statement->fetchAll();

                //liste des années promo en base
                $query = "SELECT DISTINCT `Annee_Utilisateur` 
                    FROM `utilisateur` u 
                    LEFT JOIN etudiant_tuteur et ON u.ID_Utilisateur = et.ID_etudiant 
                    WHERE Annee_Utilisateur IS NOT NULL AND et.ID_tuteur =" . $_SESSION["user_id"];
                $statement = $db->query($query);
                $annee = $statement->fetch();

                $query = "SELECT DISTINCT `Annee_Utilisateur` 
                    FROM `utilisateur` u 
                    LEFT JOIN etudiant_tuteur et ON u.ID_Utilisateur = et.ID_etudiant 
                    WHERE Annee_Utilisateur IS NOT NULL AND et.ID_tuteur =" . $_SESSION["user_id"];
                $statement = $db->query($query);
                // echo "<pre>";
                // var_dump($studentsList);
                // echo "</pre>";die;
                ?>

                <div class="panel" id="panel">
                    <div class="col-6 col-md-4 mx-auto">
                        <?php
                        if (empty($annee)) {
                            echo "<select id='suiviRecapSelector' disabled class='form-select' >";
                        } else {
                            echo "<select id='suiviRecapSelector' class='form-select' >";
                        }

                        $firstOption = true;
                        $i = 0;
                        while ($row = $statement->fetch()) {
                            if ($firstOption) {
                                echo "<option value='tous' selected>Toutes années confondues</option>";
                                echo "<option value='" . $row['Annee_Utilisateur'] . "'>" . $row['Annee_Utilisateur'] . "</option>";
                                $firstOption = false;
                                $idPlanningSelected = $row['Annee_Utilisateur'];
                            } else {
                                echo "<option value='" . $row['Annee_Utilisateur'] . "' >" . $row['Annee_Utilisateur'] . "</option>";
                                $idPlanningSelected = $row['Annee_Utilisateur'];
                            }
                        }
                        echo "</select>";
                        ?>
                    </div>
                    <table id="suiviRecap" class="display" style="width:100%">
                        <thead>
                            <tr class="bg">
                                <th hidden></th>
                                <th>Etudiant</th>
                                <th>Promo</th>
                                <th>Poster</th>
                                <th>Remarque</th>
                                <th>Rapport</th>
                                <th>Appréciation</th>
                                <th>Orthographe</th>
                                <th>Note de suivi</th>
                                <th>Note d'oral</th>
                                <th>Note PP</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($studentsList as $student) { ?>
                                <tr <?php
                                    if (
                                        empty($student['nom_Utilisateur']) || empty($student['Promo_Utilisateur']) ||
                                        empty($student['Poster_NF']) || empty($student['Rapport_NF']) ||
                                        empty($student['Orthographe_NF']) || empty($student['NoteFinaleTuteur_NF']) ||
                                        empty($student['noteFinaleUE_NF'])
                                    ) {
                                        echo 'class="tr-bgColorRed"';
                                    }
                                    ?>>
                                <?php
                                echo "
                                        <td class='text-center' style='display:none;'>" . $student['Annee_Utilisateur'] . "</td>
                                        <td class='text-center'>" . $student['nom_Utilisateur'] . "</td>
                                        <td class='text-center'>" . $student['Promo_Utilisateur'] . "</td>
                                        <td class='text-center'>" . $student['Poster_NF'] . "</td>
                                        <td class='text-center'>" . $student['Remarque_NF'] . "</td>
                                        <td class='text-center'>" . $student['Rapport_NF'] . "</td>
                                        <td class='text-center'>" . $student['Appreciation_NF'] . "</td>
                                        <td class='text-center'>" . $student['Orthographe_NF'] . "</td>
                                        <td class='text-center'>" . $student['NoteFinaleTuteur_NF'] . "</td>
                                        <td class='text-center'>" . getStutdentGradeOral($student['ID_Utilisateur']) . "</td>
                                        <td class='text-center'>" . $student['noteFinaleUE_NF'] . "</td>
                                        <td><a href='studentMonitoringUpdate_tuteurUniversitaire.php?id=" . $student["ID_Utilisateur"] . "'><button type='button' class='btn bg bi bi-pencil-fill'></button></a></td>
                                    </tr>";
                            }
                                ?>
                        </tbody>
                    </table>
                </div>
                <p><span style="color: red;" class="bi bi-exclamation-triangle-fill"></span> Les étudiants dont les informations sont incomplètes (ex : document manquant, note manquante) seront considérés comme étant défaillants.</p>
            </div>
        </div>
    </div>
</body>

</html>

<script>
    $(document).ready(function() {
        $(".bar").fadeOut(1000, function() {
            $('#content').fadeIn();
        });
    });
</script>
<script src="../js/toastr.min.js"></script>
<script>
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": false,
        "progressBar": true,
        "positionClass": "toast-top-center",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "7000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }
</script>
<?php
$success = $_SESSION['success'];
switch ($success) {
    case 1:
        echo '<script>toastr.success("Suivi recap modifié avec succès !");</script>';
        break;
    default:
        // rien
}
$_SESSION['success'] = 0;
?>
<script>
    $(document).ready(function() {
        // Récupérer la valeur sélectionnée en session
        let selectedSuiviRecap = sessionStorage.getItem('selectedSuiviRecap');

        $('#suiviRecap').DataTable({
            stateSave: true,
            language: {
                url: "//cdn.datatables.net/plug-ins/1.13.2/i18n/fr-FR.json"
            },
            order: [
                [0, 'asc']
            ],
            dom: 'lfrtip'

        });

        // Fonction pour filtrer les lignes en fonction de la sélection
        function filterRows() {
            selectedSuiviRecap = suiviRecapSelector.value;
            let rows = document.getElementById("suiviRecap").getElementsByTagName("tr");

            for (let i = 1; i < rows.length; i++) {
                let row = rows[i];
                let suiviRecapId = row.cells[0].textContent;
                if (suiviRecapId === selectedSuiviRecap || selectedSuiviRecap === "tous") {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            }

            // Enregistrer la valeur sélectionnée en session
            sessionStorage.setItem('selectedSuiviRecap', selectedSuiviRecap);

            // Réaffecter l'attribut "selected" à l'option sélectionnée
            for (let i = 0; i < suiviRecapSelector.options.length; i++) {
                let option = suiviRecapSelector.options[i];
                if (option.value === selectedSuiviRecap) {
                    option.selected = true;
                } else {
                    option.selected = false;
                }
            }
        }

        // Sélectionner l'option enregistrée en session
        if (selectedSuiviRecap && document.querySelector(`#suiviRecapSelector option[value="${selectedSuiviRecap}"]`)) {
            suiviRecapSelector.value = selectedSuiviRecap;
            let oldSelectedOption = document.querySelector("#suiviRecapSelector option[selected]");
            if (oldSelectedOption) {
                oldSelectedOption.removeAttribute("selected");
            }
            let newSelectedOption = document.querySelector("#suiviRecapSelector option:checked");
            if (newSelectedOption) {
                newSelectedOption.setAttribute("selected", "");
            }
            filterRows();
        } else {
            // L'option sélectionnée n'existe plus dans le sélecteur, sélectionner l'option par défaut
            selectedSuiviRecap = suiviRecapSelector.options[0].value;
            sessionStorage.setItem('selectedSuiviRecap', selectedSuiviRecap);
            suiviRecapSelector.value = selectedSuiviRecap;
            filterRows();
        }

        // Filtrer les lignes lorsqu'on change la sélection
        suiviRecapSelector.addEventListener("change", function() {
            let oldSelectedOption = document.querySelector("#suiviRecapSelector option[selected]");
            if (oldSelectedOption) {
                oldSelectedOption.removeAttribute("selected");
            }
            let newSelectedOption = document.querySelector("#suiviRecapSelector option:checked");
            if (newSelectedOption) {
                newSelectedOption.setAttribute("selected", "");
            }
            filterRows();
        });
    });
</script>