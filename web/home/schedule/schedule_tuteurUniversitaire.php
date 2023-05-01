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

            <div class="container-fluid space">
                <h2 class="center colored">Planning</h2>
                <hr>
                <br>
                <br>
                <?php
                $db = Database::connect();

                $query = "SELECT `ValidationScolarite_Planning` FROM `decisions`;";
                $statement = $db->query($query);
                $validationSco = $statement->fetch();

                if($validationSco[0] == "oui"){

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
                    WHERE u1.ID_Planning IS NOT NULL AND et.ID_tuteur =". $_SESSION['user_id'] ."
                    GROUP BY u1.ID_Utilisateur"; //

                    $statement = $db->query($query);
                    $studentsList = $statement->fetchAll();

                    if(!empty($studentsList)){

                        // echo "<pre>";
                        // var_dump($studentsList);
                        // echo "</pre>";die;

                        //quelle est l'année en cours ?
                        $date = getdate();
                        $currentYear = $date['year'];
                        $lastYear = $currentYear - 1;
                        $currentStudentYear = $lastYear . "-" . $currentYear;

                        
                        $firstStudent = false;
                        foreach($studentsList as $student){
                            if(!$firstStudent){
                                $inSqlVar = "(".$student['ID_Etudiant'];
                                $firstStudent = true;
                            }else{
                                $inSqlVar .= ",".$student['ID_Etudiant'];
                            }
                        }
                        $inSqlVar .= ")";

                        $query = "SELECT p.ID_Planning, Nom_Planning FROM planning p
                        LEFT JOIN utilisateur u ON u.ID_Planning = p.ID_Planning
                        WHERE u.ID_Utilisateur IN".$inSqlVar;
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
                    <?php
                    }else{
                    ?>
                        <div class="panel" id="panel">
                            <div class="col-6 col-md-4 mx-auto">
                                <?php
                                echo "<select disabled id='planningSelector' class='form-select' >";
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
                                </tbody>
                            </table>
                        </div>
                    <?php
                    }
                }else if($validationSco[0] == "non"){
                    echo '
                    <div class="row d-flex justify-content-center">
                        <div class="col-12 col-md-8 col-lg-6 col-xl-10">
                            <div class="card shadow-2-strong css-login">
                                <div class="card-body p-5">
                                    <div class="row">
                                    <p class="text-center">Vous ne pourrez acceder aux plannings de soutenances qu\'une fois que la Scolarité les aura validé.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    ';
                }
                ?>
            </div>
            <br>
            
        </div>
    </div>
</body>

</html>

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
        }else {
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
        } else if (sessionStorage.getItem('btnConfigureHidden') === 'false'){
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