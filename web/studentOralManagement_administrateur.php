<?php
include("../application_config/db_class.php");
include("../fonctions/functions.php");
session_start();

if(!isConnectedUser()){
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

            $db = Database::connect();

            $sql = "SELECT NS.ID_NS, 
                    U.Nom_Utilisateur, 
                    IFNULL(I.Nom_Invite, U2.Nom_Utilisateur) AS Nom_Evaluateur, 
                    NS.NoteFinale_NS, 
                    NS.Commentaire_NS 
                    FROM notes_soutenance NS 
                    LEFT JOIN Utilisateur U ON NS.ID_UtilisateurEvalue=U.ID_Utilisateur 
                    LEFT JOIN Utilisateur U2 ON NS.ID_UtilisateurEvaluateur=U2.ID_Utilisateur 
                    LEFT JOIN invite I ON NS.ID_InviteEvaluateur=I.ID_Invite;";

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
                <div class="container">
                    <div class="text-center">
                        <button type="button" id="btnPF" class="btn me-md-3 bg btn-custom">Suivi différé des notes</button>
                        <button type="button" id="btnPD" class="btn me-md-3 bg btn-custom">Suivi des notes en direct</button>
                        <button type="button" id="btnSP" class="btn me-md-3 bg btn-custom">Suivi des sessions passées</button>
                    </div>
                    <br>
                    <div id="divPF">
                        <div class="container-fluid space">
                            <h2 class="center colored">Soutenance</h2>
                            <hr>
                            <br>
                            <br>
                            <div class="panel" id="panel">
                                <table id="tableD1" class="display" style="width:100%">
                                    <thead>
                                        <tr class="bg">
                                            <th>Evaluateur</th>
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
                                                    <td class="text-center"><?= $user['Nom_Evaluateur']; ?></td>
                                                    <td class="text-center"><?= $user['Nom_Utilisateur']; ?></td>
                                                    <td class="text-center"><?= $user['NoteFinale_NS']; ?></td>
                                                    <td class="text-center"><?= $user['Commentaire_NS']; ?></td>
                                                    <td>
                                                        <a href="studentOralFormModify_administrateur.php?id=<?= $user['ID_NS'] ?>&amp;nom_utilisateur=<?= urlencode($user['Nom_Evaluateur']) ?>">
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id=" divPD">

    </div>

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
        echo '<script>toastr.success("Note modifée avec succès !");</script>';
        break;
    case 2:
        echo '<script>toastr.success("Note supprimée avec succès !");</script>';
        break;
    default:
        // rien
}
$_SESSION['success'] = 0;
?>
<script>
    $(document).ready(function() {
        var table = $('#tableD1').DataTable({
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

/*
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
*/

        $('.btn-delete').click(function() {
            var id = $(this).data('id');
            var user = $(this).data('nomutilisateur');
            if (confirm('Êtes-vous sûr de vouloir supprimer la note de ' + user + ' ?')) {
                window.location.href = 'studentOralDeletion_administrateur.php?id=' + id;
            }
        });
    });
</script>