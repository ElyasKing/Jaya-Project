<?php
include("../application_config/db_class.php");
include("../fonctions/functions.php");
session_start();

$success = $_SESSION['success'];
switch ($success) {
    case 1:
        echo '<script>alert("Les paramètres ont bien été enregistrés !");</script>';
        break;
    case 2:
        echo '<script>alert("Les paramètres ont bien été enregistrés !");</script>';
        break;
    case 3:
        echo '<script>alert("Le paramètre a bien été supprimé !");</script>';
        break;
    case 4:
        echo '<script>alert("Le paramètre a bien été ajouté !");</script>';
        break;
    case 44:
        echo '<script>alert("Le paramètre ne peut être ajouté car la somme total de vos paramètres excède 20 points !");</script>';
        break;
    default:
        // rien
}
$_SESSION['success'] = 0;
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
                        <button type="button" id="btnPF" class="btn me-md-3 bg btn-custom active">Paramètres fixes</button>
                        <button type="button" id="btnPD" class="btn me-md-3 bg btn-custom">Paramètres dynamiques</button>
                    </div>
                    <br>
                    <div id="divPF">
                        <form action="applicationSettingsCheckFixedSettingsUpdate.php" method="post">
                            <?php
                            $numberOfCols = 0;
                            echo "<div class='row'>";
                            foreach ($fixedSettings as $fixedSetting) {
                                if ($numberOfCols < 2) {
                                    if (
                                        $fixedSetting['Nom_param'] == "Date de début des sessions de soutenance"
                                        || $fixedSetting['Nom_param'] == "Date de fin des sessions de soutenances"
                                    ) {
                                        echo '<div class="col">
                                            <input type="hidden" class="form-control" name="id[]" value="' . $fixedSetting["ID_param"] . '">
                                            <label for="description" class="form-label">' . $fixedSetting["Nom_param"] . '</label>
                                            <input type="date" class="form-control" name="description[]" value="' . $fixedSetting["Description_param"] . '">
                                        </div>';
                                    } elseif ($fixedSetting['Nom_param'] == "Durée d'une session de soutenance") {
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
                                        || $fixedSetting['Nom_param'] == "Date de fin des sessions de soutenances"
                                    ) {
                                        echo '<div class="col">
                                            <input type="hidden" class="form-control" name="id[]" value="' . $fixedSetting["ID_param"] . '">
                                            <label for="description" class="form-label">' . $fixedSetting["Nom_param"] . '</label>
                                            <input type="date" class="form-control" name="description[]" value="' . $fixedSetting["Description_param"] . '">
                                        </div>';
                                    } elseif ($fixedSetting['Nom_param'] == "Durée d'une session de soutenance") {
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
                            echo "</div>";
                            ?>
                            <br>
                            <div class="text-center">
                                <button class="btn me-md-3 bg" type="submit">Modifier</button>
                            </div>
                        </form>
                    </div>
                    <div hidden id="divPD">
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
                                        echo "<tr>
                                            <td>" . $dynamicSetting['Nom_param'] . "</td>
                                            <td>" . $dynamicSetting['NbPoint_param'] . "</td>
                                            <td>
                                                <a href='applicationSettingsDynamicsSettingsUpdate.php?id=" . $dynamicSetting['ID_param'] . "'>
                                                <i class='bi bi-pencil-fill'></i></a>
                                                <button class='btn-delete' data-id='" . $dynamicSetting["ID_param"] . "' data-name='" . $dynamicSetting["Nom_param"] . "'>
                                                    <i class='bi bi-trash-fill'></i>
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
                </div>
            </div>
        </div>
    </div>
</body>

</html>

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

        let btnPF = document.querySelector('#btnPF');
        let btnPD = document.querySelector('#btnPD');
        let divPF = document.querySelector('#divPF');
        let divPD = document.querySelector('#divPD');
        let hidden = true;

        btnPF.addEventListener('click', () => {
            if (!hidden) {
                divPF.removeAttribute('hidden');
                btnPF.className = "btn me-md-3 bg btn-custom active";

                divPD.setAttribute('hidden', '');
                btnPD.className = "btn me-md-3 bg btn-custom";

                hidden = true;
            } else {

            }
        });

        btnPD.addEventListener('click', () => {
            if (hidden) {
                divPD.removeAttribute('hidden');
                btnPD.className = "btn me-md-3 bg btn-custom active";

                divPF.setAttribute('hidden', '');
                btnPF.className = "btn me-md-3 bg btn-custom";

                hidden = false;
            } else {

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