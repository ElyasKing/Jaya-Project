<?php
include("../application_config/db_class.php");
include("../fonctions/functions.php");
session_start();

if (!isConnectedUser()) {
    $_SESSION['success'] = 2;
    header("Location: login.php");
}

$isoral = isTimeForOral();

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

            $sql = "SELECT U.Nom_Utilisateur,
            NS.ID_NS,
            NS.NoteFinale_NS,
            NS.Commentaire_NS 
            FROM notes_soutenance NS 
            LEFT JOIN Utilisateur U ON NS.ID_UtilisateurEvalue=U.ID_Utilisateur 
            WHERE NS.ID_UtilisateurEvaluateur = '" . $_SESSION["user_id"] . "';";

            $result = $db->query($sql);

            $arr_users = [];
            if ($result->rowCount() > 0) {
                $arr_users = $result->fetchAll();
            }
            ?>

            <div class="container-fluid space">
                <h2 class="center colored">Soutenance</h2>
                <hr>
                <br>
                <br>
                <div class="panel" id="panel">
                    <table id="example" class="display" style="width:100%">
                        <thead>
                            <tr class="bg">
                                <th>Etudiant</th>
                                <th>Note saisie</th>
                                <th>Appréciation</th>
                                <th> </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($arr_users)) { ?>
                                <?php foreach ($arr_users as $user) { ?>
                                    <tr class="user-row">
                                        <td class="text-center"><?= $user['Nom_Utilisateur']; ?></td>
                                        <td class="text-center"><?= $user['NoteFinale_NS']; ?></td>
                                        <td class="text-center"><?= $user['Commentaire_NS']; ?></td>
                                        <td>
                                            <?php if ($isoral == 1) : ?>
                                                <a href="studentOralFormModify_tuteurUniversitaire.php?id=<?= $user['ID_NS'] ?>">
                                                    <button type='button' class='btn bg bi bi-pencil-fill'></button>
                                                </a>
                                            <?php else : ?>
                                                <button type='button' class='btn bg bi bi-pencil-fill' disabled></button>
                                            <?php endif; ?>
                                            <button type='button' class='btn red bi bi-trash-fill btn-delete' data-id="<?= $user['ID_NS'] ?>" data-nomutilisateur="<?= $user['Nom_Utilisateur'] ?>" <?php if ($isoral == 0) echo 'disabled'; ?>>
                                            </button>
                                        </td>
                                    </tr>
                                <?php } ?>
                            <?php } ?>
                        </tbody>
                    </table>
                    <br>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <?php if ($isoral == 1) : ?>
                            <a type="button" href="studentOralFormNew_tuteurUniversitaire.php" class="btn me-md-3 btn-custom bg">Nouvelle soutenance</a>
                        <?php else : ?>
                            <button type="button" class="btn me-md-3 btn-custom bg" disabled>Nouvelle soutenance</button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
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
                    echo '<script>toastr.success("Note ajoutée avec succès !");</script>';
                    break;
                case 2:
                    echo '<script>toastr.success("Note modifée avec succès !");</script>';
                    break;
                case 3:
                    echo '<script>toastr.success("Note supprimée avec succès !");</script>';
                    break;
                default:
                    // rien
            }
            $_SESSION['success'] = 0;
            ?>
            <script>
                $(document).ready(function() {
                    $('#example').DataTable({
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

                    $('.btn-delete').click(function() {
                        var id = $(this).data('id');
                        var user = $(this).data('nomutilisateur');
                        if (confirm('Êtes-vous sûr de vouloir supprimer la note de ' + user + ' ?')) {
                            window.location.href = 'studentOralDeletion_tuteurUniversitaire.php?id=' + id;
                        }
                    });
                });
            </script>
        </div>
    </div>
</body>

</html>