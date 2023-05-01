<?php
include "../../../application_config/db_class.php";
include("../../../fonctions/functions.php");
session_start();
?>

<!DOCTYPE html>
<html>

<head>
    <?php
    include("../navigation/header.php");
    $db = Database::connect();
    ?>
</head>

<body>

<script>
    // Définir l'élément avec le temps d'expiration
    const expiresIn = 10 * 1000; // 10 secondes en millisecondes
    const expirationTime = new Date().getTime() + expiresIn;
    localStorage.setItem("connected", JSON.stringify({value: "True", expiresAt: expirationTime}));

    // Récupérer l'élément et vérifier s'il a expiré
    const connectedItem = JSON.parse(localStorage.getItem("connected"));
    const currentTime = new Date().getTime();

    if (connectedItem && currentTime < connectedItem.expiresAt) {
        const connected = connectedItem.value;
        console.log(connected);
    } else {
        // L'élément a expiré, vous pouvez le supprimer ou effectuer d'autres actions
        localStorage.removeItem("connected");
        console.log("L'élément a expiré");
        document.location.href="./token-expired.php";
    }


</script>



    <div class="content">
        <div class="bar">
            <span class="sphere"></span>
        </div>
        <div id="content">
            <?php
            include('../navigation/navbar.php');

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
                <h2 class="center colored">Soutenances</h2>
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
                                            <a href="Modify/studentOralFormModify_tuteurUniversitaire.php?id=<?= $user['ID_NS'] ?>">
                                                <button type='button' class='btn bg bi bi-pencil-fill'></button>
                                            </a>
                                            <button type='button' class='btn red bi bi-trash-fill btn-delete' data-id="<?= $user['ID_NS'] ?>" data-nomutilisateur="<?= $user['Nom_Utilisateur'] ?>">
                                            </button>
                                        </td>
                                    </tr>
                                <?php } ?>
                            <?php } ?>
                        </tbody>
                    </table>
                    <a href="New/studentOralFormNew_tuteurUniversitaire.php" class="btn btn-primary">Nouvelle soutenance</a>
                </div>
            </div>
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