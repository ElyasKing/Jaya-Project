<?php
include("../../../application_config/db_class.php");
include("../../../fonctions/functions.php");
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
    include("../navigation/header.php");
    ?>
</head>

<body>
    <div class="content">
        <div class="bar">
            <span class="sphere"></span>
        </div>
        <div id="content">
            <?php
            include('../navigation/navbar.php');
            ?>

            <div class="container-fluid">
                <h2 class="center colored">Planning</h2>
                <hr>
                <br>
                <br>
                <?php
                $db = Database::connect();

                $query = "SELECT 
                    u1.ID_Utilisateur AS ID_Etudiant,
                    u1.Nom_Utilisateur AS Nom_Etudiant,
                    u1.Mail_Utilisateur AS Mail_Etudiant,
                    u1.Annee_Utilisateur,
                    u1.Promo_Utilisateur,
                    u1.HuisClos_Utilisateur,
                    u2.Nom_Utilisateur AS Nom_Tuteur_Universitaire,
                    u2.Mail_Utilisateur AS Mail_Tuteur_Universitaire,
                    i.Entreprise_Invite,
                    i.Ville_Invite,
                    i.Nom_Invite,
                    i.Mail_Invite,
                    u1.ID_Planning,
                    p.Nom_Planning,
                    p.DateSession_Planning,
                    p.HeureDebutSession_Planning,
                    TRIM(BOTH ', ' FROM CONCAT_WS(', ',
                        CASE WHEN SUM(h.Admin_Habilitations = 'oui') > 0 THEN 'Admin' ELSE NULL END,
                        CASE WHEN SUM(h.ResponsableUE_Habilitations = 'oui') > 0 THEN 'ResponsableUE' ELSE NULL END,
                        CASE WHEN SUM(h.Scolarite_Habilitations = 'oui') > 0 THEN 'Scolarite' ELSE NULL END,
                        CASE WHEN SUM(h.Etudiant_Habilitations = 'oui') > 0 THEN 'Etudiant' ELSE NULL END,
                        CASE WHEN SUM(h.TuteurUniversitaire_Habilitations = 'oui') > 0 THEN 'TuteurUniversitaire' ELSE NULL END
                    )) AS Roles
                FROM
                    Utilisateur u1
                        LEFT JOIN etudiant_tuteur et ON u1.id_Utilisateur = et.id_Etudiant
                        LEFT JOIN Utilisateur u2 ON et.id_Tuteur = u2.id_Utilisateur
                        LEFT JOIN est_apprenti ea ON u1.id_utilisateur = ea.id_utilisateur
                        LEFT JOIN invite i ON ea.id_invite = i.id_invite
                        LEFT JOIN habilitations h ON u1.ID_Utilisateur = h.ID_Utilisateur
                        LEFT JOIN planning p ON u1.ID_Planning = p.ID_Planning
                WHERE u1.ID_Planning IS NOT NULL
                GROUP BY u1.ID_Utilisateur";

                $statement = $db->query($query);
                $studentsList = $statement->fetchAll();

                //quelle est l'année en cours ?
                $date = getdate();
                $currentYear = $date['year'];
                $lastYear = $currentYear - 1;
                $currentStudentYear = $lastYear . "-" . $currentYear;

                // certains étudiants n'ont pas d'alternance et ne peuvent par conséquent pas être plannifiés.
                $query = "SELECT DISTINCT 
                    u1.id_Utilisateur,
                    u1.Nom_Utilisateur AS Nom_Etudiant,
                    u1.Annee_Utilisateur,
                    u1.Promo_Utilisateur, 
                    u1.HuisClos_Utilisateur 
                FROM Utilisateur u1 
                LEFT JOIN etudiant_tuteur et ON u1.id_Utilisateur = et.id_Etudiant
                WHERE u1.Annee_Utilisateur = '$currentStudentYear'
                AND u1.ID_Planning IS NULL;";

                $statement = $db->query($query);
                $studentsWithoutSchedule = $statement->fetchAll();
                $cpt = count($studentsWithoutSchedule);

                // echo "<pre>";
                // var_dump($studentsList);
                // echo "</pre>";die;

                $query = "SELECT ID_Planning, Nom_Planning FROM planning";
                $statement = $db->query($query);
                ?>

                <div class="panel" id="panel">
                    <div class="col-6 col-md-4 mx-auto">
                        <?php
                        echo "<select id='planningSelector' class='form-select' >";

                        $firstOption = true;
                        $i = 0;
                        while ($row = $statement->fetch()) {
                            if ($firstOption) {
                                echo "<option value='tous' selected>Tous plannings confondus</option>";
                                echo "<option value='" . $row['ID_Planning'] . "'>" . $row['Nom_Planning'] . "</option>";
                                $firstOption = false;
                                $idPlanningSelected = $row['ID_Planning'];
                            } else {
                                echo "<option value='" . $row['ID_Planning'] . "' >" . $row['Nom_Planning'] . "</option>";
                                $idPlanningSelected = $row['ID_Planning'];
                            }
                        }
                        echo "</select>";
                        if ($cpt > 0) {
                        ?>
                            <p class="text-center"><abbr title="Certains étudiants n'ont pas été pris en compte dans plannification. Il se peut qu'il n'aient pas été associés à un tuteur entreprise. Ils sont donc considérés comme non alternants et ne peuvent soutenir (c.f. onglet Accueil - Administrateur) : <?php foreach ($studentsWithoutSchedule as $sws) {
                                                                                                                                                                                                                                                                                                                            echo $sws['Nom_Etudiant'] . " (" . $sws['Promo_Utilisateur'] . ")" . " ; ";
                                                                                                                                                                                                                                                                                                                        } ?> ">
                                    <span style="color: red;" class="bi bi-exclamation-triangle-fill"></span> Etudiant non plannifiés : <?= $cpt ?></abbr></p>
                        <?php } ?>
                    </div>
                    <table id="planning" class="display" style="width:100%">
                        <thead>
                            <tr class="bg">
                                <th hidden></th>
                                <th>Etudiant</th>
                                <th>Entreprise</th>
                                <th>Ville</th>
                                <th>Promo</th>
                                <th>Huis clos</th>
                                <th>Date de la session de soutenance</th>
                                <th>Horaires de la session de soutenance</th>
                                <!-- <th></th> -->
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = 'SELECT Description_param FROM parametres WHERE Nom_param = "Durée d\'une session de soutenances"';
                            $statement = $db->query($query);
                            $time = $statement->fetch();

                            foreach ($studentsList as $student) {
                                if ($student['DateSession_Planning'] != null) {
                                    $date = DateTime::createFromFormat('Y-m-d', $student['DateSession_Planning']);
                                    $formatted_date = $date->format('d/m/Y');
                                }

                                echo "<tr>
                                        <td class='text-center' style='display:none;'>" . $student['ID_Planning'] . "</td>
                                        <td class='text-center'>" . $student['Nom_Etudiant'] . "</td>
                                        <td class='text-center'>" . $student['Entreprise_Invite'] . "</td>
                                        <td class='text-center'>" . $student['Ville_Invite'] . "</td>
                                        <td class='text-center'>" . $student['Promo_Utilisateur'] . "</td>
                                        <td class='text-center'>" . $student['HuisClos_Utilisateur'] . "</td>";
                                if ($student['DateSession_Planning'] != null) {
                                    $date = DateTime::createFromFormat('Y-m-d', $student['DateSession_Planning']);
                                    $formatted_date = $date->format('d/m/Y');
                                    echo "<td class='text-center'>" . $formatted_date . "</td>";
                                } else {
                                    echo "<td class='text-center'></td>";
                                }
                                if ($student['HeureDebutSession_Planning'] != null) {
                                    $f1 = str_replace(":", ".", $student['HeureDebutSession_Planning']);
                                    $sessionStartTime = (float)$f1;
                                    $sessionStartTime = substr($sessionStartTime, 0, 5);
                                    $num_rounded = round($sessionStartTime, 2);
                                    $sessionStartTime2 = number_format($num_rounded, 2, 'H', '.');

                                    $f2 = str_replace(":", ".", $time[0]);
                                    $sessionEndTime = (float)$f2;
                                    $sessionTimes =  $sessionStartTime + $sessionEndTime;
                                    $num_rounded = round($sessionTimes, 2);
                                    $sessionTimes = number_format($num_rounded, 2, 'H', '.');

                                    echo "<td class='text-center'>" . $sessionStartTime2 . "-" . $sessionTimes . "</td>";
                                } else {
                                    echo "<td class='text-center'></td>";
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <br>
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <?php
                $query = "SELECT ValidationScolarite_Planning FROM decisions";
                $statement = $db->query($query);
                $result = $statement->fetch();

                if ($result[0] == "non") { ?>
                    <form id="schedule-validation-form" action="scheduleCheckValidation_responsableUE.php" method="post">
                    </form>
                    <a href="#" onclick='if(confirm("Souhaitez-vous valider les informations de soutenances. Les utilisateurs ayant le rôle \"Scolarité\" pourront envoyer le planning aux étudiants ainsi qu’à leur(s) tuteur(s).")){document.getElementById("schedule-validation-form").submit();}else{return false;};'><button id="btn-valider" class="btn me-md-3 btn-custom bg">Valider</button></a>
                <?php
                } else { ?>
                    <form id="schedule-validation-form" action="scheduleCheckValidation_responsableUE.php" method="post">
                    </form>
                    <a href="#" onclick='if(confirm("Souhaitez-vous revenir sur votre validation des informations de soutenances. Les utilisateurs ayant le rôle \"Scolarité\" ne pourront plus envoyer le planning aux étudiants ainsi qu’à leur(s) tuteur(s).")){document.getElementById("schedule-validation-form").submit();}else{return false;};'><button id="btn-valider" class="btn me-md-3 btn-custom bg">Valider</button></a>
                <?php
                }
                ?>
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
<script src="../../../js/toastr.min.js"></script>
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
        echo '<script>toastr.success("Les génération des plannings à été effectuée !");</script>';
        break;
    case 2:
        echo '<script>toastr.success("Le changement de planning à été effectué.");</script>';
        break;
    case 3:
        echo '<script>toastr.success("Le planning à été mis à jour.");</script>';
        break;
    default:
        // rien
}
$_SESSION['success'] = 0;
?>

<script>
    $(document).ready(function() {
        // Récupérer la valeur sélectionnée en session
        let selectedPlanning = sessionStorage.getItem('selectedPlanning');

        $('#planning').DataTable({
            stateSave: true,
            language: {
                url: "//cdn.datatables.net/plug-ins/1.13.2/i18n/fr-FR.json"
            },
            order: [
                [3, 'desc']
            ],
            dom: 'lfrtip',

        });

        // Fonction pour filtrer les lignes en fonction de la sélection
        function filterRows() {
            selectedPlanning = planningSelector.value;
            let rows = document.getElementById("planning").getElementsByTagName("tr");

            for (let i = 1; i < rows.length; i++) {
                let row = rows[i];
                let planningId = row.cells[0].textContent;
                if (planningId === selectedPlanning || selectedPlanning === "tous") {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            }

            // Enregistrer la valeur sélectionnée en session
            sessionStorage.setItem('selectedPlanning', selectedPlanning);

            // Réaffecter l'attribut "selected" à l'option sélectionnée
            for (let i = 0; i < planningSelector.options.length; i++) {
                let option = planningSelector.options[i];
                if (option.value === selectedPlanning) {
                    option.selected = true;
                } else {
                    option.selected = false;
                }
            }
        }

        // Sélectionner l'option enregistrée en session
        if (selectedPlanning && document.querySelector(`#planningSelector option[value="${selectedPlanning}"]`)) {
            planningSelector.value = selectedPlanning;
            let oldSelectedOption = document.querySelector("#planningSelector option[selected]");
            if (oldSelectedOption) {
                oldSelectedOption.removeAttribute("selected");
            }
            let newSelectedOption = document.querySelector("#planningSelector option:checked");
            if (newSelectedOption) {
                newSelectedOption.setAttribute("selected", "");
            }
            filterRows();
        } else {
            // L'option sélectionnée n'existe plus dans le sélecteur, sélectionner l'option par défaut
            selectedPlanning = planningSelector.options[0].value;
            sessionStorage.setItem('selectedPlanning', selectedPlanning);
            planningSelector.value = selectedPlanning;
            filterRows();
        }

        // Filtrer les lignes lorsqu'on change la sélection
        planningSelector.addEventListener("change", function() {
            let oldSelectedOption = document.querySelector("#planningSelector option[selected]");
            if (oldSelectedOption) {
                oldSelectedOption.removeAttribute("selected");
            }
            let newSelectedOption = document.querySelector("#planningSelector option:checked");
            if (newSelectedOption) {
                newSelectedOption.setAttribute("selected", "");
            }
            filterRows();
        });

        let btnConfigure = document.querySelector('#btnConfigure');

        // Vérifie s'il y a une valeur stockée en session pour le bouton Configure
        if (sessionStorage.getItem('btnConfigureHidden') === 'true') {
            btnConfigure.setAttribute('hidden', '');
        } else if (sessionStorage.getItem('btnConfigureHidden') === 'false') {
            btnConfigure.removeAttribute('hidden');
        }

        // Enregistre l'état du bouton Configure en session storage lorsque l'état change
        planningSelector.addEventListener("change", function() {
            let selectOption = document.querySelector("#planningSelector option[selected]");
            if (selectOption.value === 'tous') {
                btnConfigure.setAttribute('hidden', '');
                sessionStorage.setItem('btnConfigureHidden', 'true');
            } else {
                btnConfigure.removeAttribute('hidden');
                sessionStorage.setItem('btnConfigureHidden', 'false');
            }
        });
    });
</script>