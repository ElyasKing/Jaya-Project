<?php
include("../../../../application_config/db_class.php");
include("../../../../fonctions/functions.php");
session_start();



if(!isset($_SESSION['active_profile'])) {
    $_SESSION['session_url']="";
    $_SESSION['active_profile'] = "";
}
    if ($_SESSION['active_profile'] <> "TUTEUR UNIVERSITAIRE" && $_SESSION['session_url'] == "") {
        $_SESSION['user_name'] = $_GET['nom'];
        $_SESSION['entreprise'] = $_GET['entreprise'];
        $_SESSION['user_id'] = $_GET['id'];
        $_SESSION['change_profile_access'] = 6;
        $_SESSION['active_profile'] = "INVITE";
        //$_SERVER['REQUEST_URI'] = "http://localhost/JAYA/web/home/soutenances/tuteurU/tuteurUniversitaire.php?id=".$_SESSION['user_id']."&nom=".$_SESSION['user_name']."&entreprise=".$_SESSION['entreprise']."";
        $_SESSION['session_url'] = $_SERVER['REQUEST_URI'];
    }



if($_SESSION['active_profile'] <> "INVITE") {
    if (!isConnectedUser()) {
        $_SESSION['success'] = 2;
        header("Location: login.php");
    }
}

$isoral = isTimeForOral();

?>

<!DOCTYPE html>
<html>

<head>
    <?php
    include("../../navigation/header.php");
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
            include('../../navigation/navbar.php');
            if($_SESSION['active_profile'] <> "INVITE") {
                $sql = "SELECT U.Nom_Utilisateur,
            NS.ID_NS,
            NS.NoteFinale_NS,
            NS.Commentaire_NS
            FROM notes_soutenance NS 
            LEFT JOIN Utilisateur U ON NS.ID_UtilisateurEvalue=U.ID_Utilisateur 
            WHERE NS.ID_UtilisateurEvaluateur = '".$_SESSION['user_id']."'";
                $result = $db->query($sql);
            } else {
                $sql = "SELECT U.Nom_Utilisateur,
            NS.ID_NS,
            NS.NoteFinale_NS,
            NS.Commentaire_NS
            FROM notes_soutenance NS 
            LEFT JOIN Utilisateur U ON NS.ID_UtilisateurEvalue=U.ID_Utilisateur 
            WHERE NS.ID_InviteEvaluateur = '".$_SESSION['user_id']."'";
                $result = $db->query($sql);
            }


            if ($result->rowCount() > 0) {
                $arr_users = $result->fetchAll();

            }
            ?>

            <div class="container-fluid space">
                <h2 class="center margin-title colored">Soutenances</h2>
                <hr>
                <?php if ($_SESSION['active_profile'] == "INVITE") { ?>
                <p>Bienvenue <?php echo $_SESSION['user_name']." de ".$_SESSION['entreprise']; ?></p>
                <?php } ?>
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
                            <?php if (!empty($arr_users))  {?>
                                <?php foreach ($arr_users as $user) { ?>
                                    <tr class="user-row">
                                        <td class="text-center"><?= $user['Nom_Utilisateur']; ?></td>
                                        <td class="text-center"><?= $user['NoteFinale_NS']; ?></td>
                                        <td class="text-center"><?= $user['Commentaire_NS']; ?></td>
                                        <td>
                                            <?php if ($isoral == 1)  : ?>
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
                                <a type="button" href="studentOralFormNew_tuteurUniversitaire.php?id_user=<?php echo $_SESSION['user_id']; ?>" class="btn me-md-3 btn-custom bg">Nouvelle soutenance</a>
                        <?php else : ?>
                            <button type="button" class="btn me-md-3 btn-custom bg" disabled>Nouvelle soutenance</button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <script src="../../../../js/toastr.min.js"></script>
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
            if(isset($_SESSION['success'])) {
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
            }
            ?>
            <script>
                $(document).ready(function() {
                    $(".bar").fadeOut(1000, function() {
                        $('#content').fadeIn();
                        $('#example').DataTable({
                            stateSave: true,
                            language: {
                                url: "//cdn.datatables.net/plug-ins/1.13.2/i18n/fr-FR.json"
                            },
                            order: [
                                [0, 'asc']
                            ],
                            dom: 'lfrtip'
                        });

                        $('.btn-delete').click(function() {
                            var id = $(this).data('id');
                            var user = $(this).data('nomutilisateur');
                            if (confirm('Êtes-vous sûr de vouloir supprimer la note de ' + user + ' ?')) {
                                window.location.href = 'studentOralDeletion_tuteurUniversitaire.php?id=' + id;
                            }
                        });
                    });
                });
            </script>
        </div>
    </div>
</body>

</html>