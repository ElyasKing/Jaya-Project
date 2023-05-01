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

            <div class="container-fluid space">
                <h2 class="center colored">Suivi Recap</h2>
                <hr>
                <br>
                <br>
                <?php
                $db = Database::connect();

                //quelle est l'année en cours ?
                $date = getdate();
                $currentYear = $date['year'];
                $lastYear = $currentYear - 1;
                $currentStudentYear = $lastYear . "-" . $currentYear;

                $query = "";
                // $statement = $db->query($query);
                // $studentsList = $statement->fetchAll();

                // echo "<pre>";
                // var_dump($studentsList);
                // echo "</pre>";die;
                ?>

                <div class="panel" id="panel">
                    <div class="col-6 col-md-4 mx-auto">
                        <?php
                        echo "<select id='planningSelector' class='form-select' >";
                        echo "</select>";
                        ?>
                    </div>
                    <table id="planning" class="display" style="width:100%">
                        <thead>
                            <tr class="bg">
                                <th>Etudiant</th>
                                <th>Promo</th>
                                <th>Poster</th>
                                <th>Remarque</th>
                                <th>Rapport</th>
                                <th>Orthographe</th>
                                <th>Note de suivi</th>
                                <th>Note d'oral</th>
                                <th>Note PP</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <br>
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <form id="monitoring-validation-form" action="studentMonitoringCheckValidation_users.php" method="post">
                </form>
                <a href="#" onclick='if(confirm("Souhaitez-vous vraiment valider les notes des étudiants ? Cette action aura pour effet de donner  à chaque étudiant, un accès en consultation à ses notes. Vous pourrez toujours mettre à jour ces données plus tard.")){document.getElementById("schedule-validationSco-form").submit();}else{return false;};'><button id="btn-valider-scolarite" class="btn me-md-3 btn-custom bg">Valider</button></a>
            </div>
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