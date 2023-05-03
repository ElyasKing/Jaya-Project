<?php
include("../../../application_config/db_class.php");
include("../../../fonctions/functions.php");
session_start();

if (!isConnectedUser()) {
    $_SESSION['success'] = 2;
    print("SUC : " + $_SESSION['success']);
    header("Location: login.php");
}

// Elias

$db = Database::connect();

//$sql = getStudentInformationForIndexes();

$sql = "SELECT * FROM invite";

$result = $db->query($sql);
$arr_users = [];
if ($result->rowCount() > 0) {
    $arr_users = $result->fetchAll();
}
?>

<!DOCTYPE html>
<html>

<head>
    <?php
    include("../navigation/header.php");
    include('../navigation/navbar.php');
    ?>
</head>

<body>

    <div class="container-fluid space">
        <h2 class="center colored">Invité</h2>
        <hr>
        <br>
        <br>
        <div class="panel" id="panel">
            <table id="example" class="display" style="width:100%">
                <thead>
                    <tr class="bg">
                        <th>Nom</th>
                        <th>Entreprise</th>
                        <th>Email</th>
                        <th>Telephone</th>
                        <th>Enseignant</th>
                        <th>Professionel</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($arr_users)) { ?>
                        <?php foreach ($arr_users as $user) { ?>
                            <tr>
                                <td class="text-center"><?= $user['Nom_Invite']; ?></td>
                                <td class="text-center"><?= lineFeedWithSeparator($user['Entreprise_Invite']); ?></td>
                                <td class="text-center"><?= lineFeedWithSeparator($user['Mail_Invite']); ?></td>
                                <td class="text-center"><?= $user['Telephone_Invite']; ?></td>
                                <td class="text-center"><?= $user['EstEnseignant_Invite']; ?></td>
                                <td class="text-center"><?= $user['EstProfessionel_Invite']; ?></td>
                                <!-- <td><button type="button" class="btn bg bi bi-pencil-fill"></button></a></td> -->
                                <td>
                                    <a href="guestManagementFormModify_scolarite.php?id=<?= $user['ID_Invite'] ?>">
                                        <button type='button' class='btn bg bi bi-pencil-fill'></button>
                                    </a>
                                    <button type='button' class='btn red bi bi-trash-fill btn-delete' data-id="<?= $user['ID_Invite'] ?>" data-nominvite="<?= $user['Nom_Invite'] ?>">
                                    </button>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <br>
    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
        <a type="button" href="qrCodeGeneration.php" class="btn me-md-3 bg btn-custom" >Générer QR code</a>
        <button type="button" id="btn-importer-admin" class="btn me-md-3 bg btn-custom">Importer</button>
        <a type="button" href="guestManagementFormCreation_scolarite.php" class="btn me-md-3 bg btn-custom">Ajouter un invité</a>
    </div>

</body>
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
        echo '<script>toastr.success("Invité modifé avec succès !");</script>';
        break;
    case 2:
        echo '<script>toastr.success("Invité supprimé avec succès !");</script>';
        break;
    case 3:
        echo '<script>toastr.success("Invité ajouté avec succès !");</script>';
        break;
    default:
        // rien
}
$_SESSION['success'] = 0;
?>
<script>
    $(document).ready(function() {
        var table = $('#example').DataTable({
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
    });
</script>

<script>
    $('.btn-delete').click(function() {
        var id = $(this).data('id');
        var user = $(this).data('nominvite');
        if (confirm('Êtes-vous sûr de vouloir supprimer l\'invité ' + user + ' ?')) {
            window.location.href = 'guestManagementDeletion_scolarite.php?id=' + id;
        }
    });
</script>