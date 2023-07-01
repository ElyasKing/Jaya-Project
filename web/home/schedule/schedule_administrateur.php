<?php
include("../../../application_config/db_class.php");
include("../../../fonctions/functions.php");
session_start();

if (!isConnectedUser()) {
    $_SESSION['success'] = 2;
    header("Location: logout.php");
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

            $db = Database::connect();

            $query = "SELECT `ValidationScolarite_Planning` FROM `decisions`;";
            $statement = $db->query($query);
            $validationSco = $statement->fetch();

            if ($validationSco[0] == "non") {
            ?>
                <div class="container-fluid">
                    <h2 class="center margin-title colored">Planning</h2>
                    <hr>
                    <br>
                    <br>
                    <?php
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
                       utilisateur u1
                            LEFT JOIN etudiant_tuteur et ON u1.id_Utilisateur = et.id_Etudiant
                            LEFT JOINutilisateur u2 ON et.id_Tuteur = u2.id_Utilisateur
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

                    // echo "<pre>";
                    // var_dump($studentsList);
                    // echo "</pre>";die;
                    $query = "SELECT ID_Planning, Nom_Planning FROM planning";
                    $statement = $db->query($query);
                    $planning = $statement->fetchAll();

                    $query = "SELECT ID_Planning, Nom_Planning FROM planning";
                    $statement = $db->query($query);
                    ?>

                    <div class="panel" id="panel">
                        <div class="col-6 col-md-4 mx-auto">
                            <?php
                            if (empty($planning)) {
                                echo "<select id='planningSelector' disabled class='form-select' >";
                            } else {
                                echo "<select id='planningSelector' class='form-select' >";
                            }

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
                            ?>
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
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $query = 'SELECT Description_param FROM parametres WHERE ID_param = "3"';
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
            <?php
            } else if ($validationSco[0] == "oui") {
            ?>
                <div class="container-fluid">
                    <h2 class="center margin-title colored">Planning</h2>
                    <hr>
                    <br>
                    <br>
                    <?php

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
                   utilisateur u1
                        LEFT JOIN etudiant_tuteur et ON u1.id_Utilisateur = et.id_Etudiant
                        LEFT JOINutilisateur u2 ON et.id_Tuteur = u2.id_Utilisateur
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
                FROM utilisateur u1 
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
                    $planning = $statement->fetchAll();

                    $query = "SELECT ID_Planning, Nom_Planning FROM planning";
                    $statement = $db->query($query);
                    ?>

                    <div class="panel" id="panel">
                        <div class="col-6 col-md-4 mx-auto">
                            <?php
                            if (empty($planning)) {
                                echo "<select id='planningSelector' disabled class='form-select' >";
                            } else {
                                echo "<select id='planningSelector' class='form-select' >";
                            }

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
                                <p class="text-center"><abbr title="Certains étudiants n'ont pas été pris en compte dans la planification. Il se peut qu'ils n'aient pas été associés à un tuteur entreprise. Ils sont donc considérés comme non-alternants et ne peuvent soutenir (c.f. onglet Accueil - Administrateur) : <?php foreach ($studentsWithoutSchedule as $sws) {
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
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $query = 'SELECT Description_param FROM parametres WHERE ID_param = "3"';
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
                                    echo "
                                        <td><a href='scheduleStudentConfiguration_administrateur.php?id=" . $student["ID_Etudiant"] . "&planning=" . $student["ID_Planning"] . "'><button type='button' class='btn bg bi bi-pencil-fill'></button></a></td>
                                    </tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <br>
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <form id="configure-planning-form" action="scheduleConfiguration_administrateur.php" method="post">
                    </form>
                    <a href="#" onclick='
                    var SelectedOption = document.querySelector("#planningSelector option[selected]"); 
                    var activeSchedule = planningSelector.value; 
                    /*console.log(activeSchedule);*/
                    $.ajax({
                        url: "scheduleAjaxPostConfigurationVariable.php",
                        type: "POST",
                        data: {
                            activeSchedule: activeSchedule
                        },
                        "success": function(data) {
                            console.log("Variable enregistrée dans la session : " + data);
                            document.getElementById("configure-planning-form").submit();
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.log("Erreur : " + textStatus);
                        }
                    });
                    '>
                        <button id="btnConfigure" hidden class='btn me-md-3 btn-custom bg'>Parametrer la session</button>
                    </a>

                    <?php
                    $query = 'SELECT `ValidationResponsableUE_Planning` FROM `decisions`';
                    $statement = $db->query($query);
                    $validationByRespUE = $statement->fetch();

                    if ($validationByRespUE[0] == "oui") {
                    ?>
                        <form id="schedule-sendMail-form" action="scheduleCheckSendMail_administrateur.php" method="post">
                        </form>
                        <a href="#" onclick="if(confirm('Souhaitez-vous vraiment envoyer un planning individuel aux étudiants et à leurs tuteurs par mail.')){document.getElementById('schedule-sendMail-form').submit();}else{return false;};"><button id="btn-envoyer-planning" class="btn me-md-3 btn-custom bg">Envoyer le planning</button></a>
                    <?php
                    }
                    ?>

                    <form id="generate-planning-form" action="scheduleGeneration_administrateur.php" method="post">
                    </form>
                    <a href="#" onclick='if(confirm("Souhaitez-vous vraiment g&eacute;n&eacute;rer ou ajouter des plannings de soutenances ?")){document.getElementById("generate-planning-form").submit();}else{return false;};'><button id="btn-generer" class="btn me-md-3 btn-custom bg">G&eacute;n&eacute;rer</button></a>
                </div>
        </div>
    <?php
            }
    ?>
    <br>
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
            dom: 'Blfrtip',
            buttons: ['excel'],

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