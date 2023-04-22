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

            $db = Database::connect();

            $query = getSettings("fixe");

            $statement = $db->query($query);
            $fixedSettings = $statement->fetchAll();

            $query = getSettings("dynamique");

            $statement = $db->query($query);
            $dynamicsSettings = $statement->fetchAll();

            $sumOfpoints = 0;
            foreach ($dynamicsSettings as $points) {
                $sumOfpoints += $points['NbPoint_param'];
            }

            ?>
            <div class="container-fluid space">
                <h2 class="center colored">Paramètres</h2>
                <hr>
                <br>
                <br>
                <div class="container">
                    <div class="text-center">
                        <button type="button" id="btnPF" class="btn me-md-3 bg btn-custom">Paramètres fixes</button>
                        <button type="button" id="btnPD" class="btn me-md-3 bg btn-custom">Paramètres dynamiques</button>
                    </div>
                    <br>
                    <div id="divPF">
                        <form action="applicationSettingsCheckFixedSettingsUpdate.php" method="post">
                            <?php
                            $numberOfCols = 0;
                            echo '
                            <div class="row d-flex justify-content-center">
                                <div class="col-12 col-md-8 col-lg-6 col-xl-10">
                                    <div class="card shadow-2-strong css-login">
                                        <div class="card-body p-5">
                                            <div class="row">';
                            foreach ($fixedSettings as $fixedSetting) {
                                if ($numberOfCols < 2) {
                                    if (
                                        $fixedSetting['Nom_param'] == "Date de début des sessions de soutenance"
                                        || $fixedSetting['Nom_param'] == "Date de fin des sessions de soutenance"
                                    ) {
                                        echo '<div class="col">
                                                <input type="hidden" class="form-control" name="id[]" value="' . $fixedSetting["ID_param"] . '">
                                                <label for="description" class="form-label">' . $fixedSetting["Nom_param"] . '</label>
                                                <input type="date" class="form-control" name="description[]" value="' . $fixedSetting["Description_param"] . '">
                                            </div>';
                                    } elseif ($fixedSetting['Nom_param'] == "Durée d'une session de soutenance" 
                                            || $fixedSetting['Nom_param'] == "Temps supplémentaire accordé aux évaluateurs lors des sessions de soutenance") {
                                        echo '<div class="col">
                                                <input type="hidden" class="form-control" name="id[]" value="' . $fixedSetting["ID_param"] . '">
                                                <label for="description" class="form-label">' . $fixedSetting["Nom_param"] . '</label>
                                                <input type="time" class="form-control" name="description[]" value="' . $fixedSetting["Description_param"] . '">
                                            </div>';
                                    } else {
                                        echo '<div class="col">
                                                <input type="hidden" class="form-control" name="id[]" value="' . $fixedSetting["ID_param"] . '">
                                                <label for="description" class="form-label">' . $fixedSetting["Nom_param"] . '</label>
                                                <input type="text" class="form-control" name="description[]" value="' . $fixedSetting["Description_param"] . '">
                                            </div>';
                                    }
                                    $numberOfCols++;
                                } else {
                                    echo "</div><div class='row'>";
                                    $numberOfCols = 1;
                                    if (
                                        $fixedSetting['Nom_param'] == "Date de début des sessions de soutenance"
                                        || $fixedSetting['Nom_param'] == "Date de fin des sessions de soutenance"
                                    ) {
                                        echo '<div class="col">
                                                <input type="hidden" class="form-control" name="id[]" value="' . $fixedSetting["ID_param"] . '">
                                                <label for="description" class="form-label">' . $fixedSetting["Nom_param"] . '</label>
                                                <input type="date" class="form-control" name="description[]" value="' . $fixedSetting["Description_param"] . '">
                                            </div>';
                                    } elseif ($fixedSetting['Nom_param'] == "Durée d'une session de soutenance"
                                            || $fixedSetting['Nom_param'] == "Temps supplémentaire accordé aux évaluateurs lors des sessions de soutenance") {
                                        echo '<div class="col">
                                                <input type="hidden" class="form-control" name="id[]" value="' . $fixedSetting["ID_param"] . '">
                                                <label for="description" class="form-label">' . $fixedSetting["Nom_param"] . '</label>
                                                <input type="time" class="form-control" name="description[]" value="' . $fixedSetting["Description_param"] . '">
                                            </div>';
                                    } else {
                                        echo '<div class="col">
                                                <input type="hidden" class="form-control" name="id[]" value="' . $fixedSetting["ID_param"] . '">
                                                <label for="description" class="form-label">' . $fixedSetting["Nom_param"] . '</label>
                                                <input type="text" class="form-control" name="description[]" value="' . $fixedSetting["Description_param"] . '">
                                            </div>';
                                    }
                                }
                            }
                            echo "
                                </div>";
                            ?>
                            <br>
                            <div class="text-center">
                                <button class="btn me-md-3 bg" type="submit">Modifier</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="divPD">
        <div class="panel" id="panel">
            <table id="tablePD" class="display" style="width:100%">
                <thead>
                    <tr class="bg">
                        <th>Description du critère d'évaluation oral</th>
                        <th>Nombre de points</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($dynamicsSettings as $dynamicSetting) {
                        echo "
                        <tr>
                            <td>" . $dynamicSetting['Nom_param'] . "</td>
                            <td>" . $dynamicSetting['NbPoint_param'] . "</td>
                            <td>
                                <a href='applicationSettingsDynamicsSettingsUpdate.php?id=" . $dynamicSetting['ID_param'] . "'>
                                    <button type='button' class='btn bg bi bi-pencil-fill'></button>
                                </a>
                                <button class='btn red bi bi-trash-fill btn-delete' data-id='" . $dynamicSetting["ID_param"] . "' data-name='" . $dynamicSetting["Nom_param"] . "'>
                                </button>
                            </td>
                        </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <br>
        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
            <a href="applicationSettingsDynamicsSettingsCreation.php" class="btn me-lg-3 bg btn-custom">Ajouter un critère</a>
        </div>
    </div>
</body>

</html>
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
        echo '<script>toastr.success("Les paramètres ont bien été enregistrés !");</script>';
        break;
    case 2:
        echo '<script>toastr.success("Les paramètres ont bien été enregistrés !");</script>';
        break;
    case 3:
        echo '<script>toastr.success("Le paramètre a bien été supprimé !");</script>';
        break;
    case 4:
        echo '<script>toastr.success("Le paramètre a bien été ajouté !");</script>';
        break;
    case 44:
        echo '<script>toastr.error("Le paramètre ne peut être ajouté car la somme total de vos paramètres excède 20 points !");</script>';
        break;
    default:
        // rien
}
$_SESSION['success'] = 0;
?>
<script>
    $(document).ready(function() {
        var table = $('#tablePD').DataTable({
            stateSave: true,
            language: {
                url: "//cdn.datatables.net/plug-ins/1.13.2/i18n/fr-FR.json"
            },
            order: [
                [0, 'asc']
            ],
            dom: 'Blfrtip',
            buttons: ['excel'],
        });

        // let btnPF = document.querySelector('#btnPF');
        // let btnPD = document.querySelector('#btnPD');
        // let divPF = document.querySelector('#divPF');
        // let divPD = document.querySelector('#divPD');
        // let hidden = true;

        // btnPF.addEventListener('click', () => {
        //     if (!hidden) {
        //         divPF.removeAttribute('hidden');
        //         btnPF.className = "btn me-md-3 bg btn-custom active";

        //         divPD.setAttribute('hidden', '');
        //         btnPD.className = "btn me-md-3 bg btn-custom";

        //         hidden = true;
        //     } else {

        //     }
        // });

        // btnPD.addEventListener('click', () => {
        //     if (hidden) {
        //         divPD.removeAttribute('hidden');
        //         btnPD.className = "btn me-md-3 bg btn-custom active";

        //         divPF.setAttribute('hidden', '');
        //         btnPF.className = "btn me-md-3 bg btn-custom";

        //         hidden = false;
        //     } else {

        //     }
        // });

        let btnPF = document.querySelector('#btnPF');
        let btnPD = document.querySelector('#btnPD');
        let divPF = document.querySelector('#divPF');
        let divPD = document.querySelector('#divPD');
        let hidden = true;

        // Vérifie s'il y a une valeur stockée en session pour le bouton sélectionné
        if (sessionStorage.getItem('selectedButton') === 'btnPD') {
            hidden = false;
            divPD.removeAttribute('hidden');
            btnPD.className = "btn me-md-3 bg btn-custom active";
            divPF.setAttribute('hidden', '');
            btnPF.className = "btn me-md-3 bg btn-custom";
        } else {
            divPF.removeAttribute('hidden');
            btnPF.className = "btn me-md-3 bg btn-custom active";
            divPD.setAttribute('hidden', '');
            btnPD.className = "btn me-md-3 bg btn-custom";
        }

        btnPF.addEventListener('click', () => {
            if (!hidden) {
                divPF.removeAttribute('hidden');
                btnPF.className = "btn me-md-3 bg btn-custom active";
                divPD.setAttribute('hidden', '');
                btnPD.className = "btn me-md-3 bg btn-custom";
                hidden = true;
                // Sauvegarde le choix du bouton en session
                sessionStorage.setItem('selectedButton', 'btnPF');
            }
        });

        btnPD.addEventListener('click', () => {
            if (hidden) {
                divPD.removeAttribute('hidden');
                btnPD.className = "btn me-md-3 bg btn-custom active";
                divPF.setAttribute('hidden', '');
                btnPF.className = "btn me-md-3 bg btn-custom";
                hidden = false;
                // Sauvegarde le choix du bouton en session
                sessionStorage.setItem('selectedButton', 'btnPD');
            }
        });


        $('.btn-delete').click(function() {
            var id = $(this).data('id');
            var setting = $(this).data('name');
            if (confirm('Êtes-vous sûr de vouloir supprimer le paramètre "' + setting + '" ?')) {
                window.location.href = 'applicationSettingsCheckDynamicSettingDeletion.php?id=' + id;
            }
        });
    });
</script>