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

            //Requete pour l'index 1 
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

            $arr_users1 = [];
            if ($result->rowCount() > 0) {
                $arr_users1 = $result->fetchAll();
            }

            //Requete pour l'index 2
            $sql = "SELECT NS.ID_UtilisateurEvalue, U.Nom_Utilisateur, 
                    COUNT(CASE WHEN I.EstProfessionel_Invite = 'oui' AND I.EstEnseignant_Invite = 'non' THEN NS.ID_InviteEvaluateur END) AS Nb_Professionnels, 
                    COUNT(CASE WHEN I.EstEnseignant_Invite = 'oui' THEN NS.ID_InviteEvaluateur 
                        WHEN NS.ID_UtilisateurEvaluateur  IS NOT NULL THEN NS.ID_UtilisateurEvaluateur 
                        ELSE NULL END) AS Nb_Enseignants 
                    FROM notes_soutenance NS 
                    LEFT JOIN Utilisateur U ON NS.ID_UtilisateurEvalue = U.ID_Utilisateur 
                    LEFT JOIN invite I ON I.ID_Invite = NS.ID_InviteEvaluateur OR I.ID_Invite = NS.ID_UtilisateurEvaluateur
                    GROUP BY NS.ID_UtilisateurEvalue, U.Nom_Utilisateur;";
            //Where clause à rajouter plus tard



            $result = $db->query($sql);

            $arr_users2 = [];
            if ($result->rowCount() > 0) {
                $arr_users2 = $result->fetchAll();
            }


            ?>



            <div class="container-fluid space">
                <h2 class="center colored">Soutenances</h2>
                <hr>
                <br>
                <br>
                <div class="container">
                    <div class="text-center">
                        <button type="button" id="btnSD" class="btn me-md-3 bg btn-custom">Suivi différé des notes</button>
                        <button type="button" id="btnND" class="btn me-md-3 bg btn-custom">Suivi des notes en direct</button>
                        <button type="button" id="btnSP" class="btn me-md-3 bg btn-custom">Suivi des sessions passées</button>
                    </div>
                    <br>
                    <div id="divSD">
                        <div class="container-fluid space">
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
                                        <?php if (!empty($arr_users1)) { ?>
                                            <?php foreach ($arr_users1 as $user1) { ?>
                                                <tr class="user-row">
                                                    <td class="text-center"><?= $user1['Nom_Evaluateur']; ?></td>
                                                    <td class="text-center"><?= $user1['Nom_Utilisateur']; ?></td>
                                                    <td class="text-center"><?= $user1['NoteFinale_NS']; ?></td>
                                                    <td class="text-center"><?= $user1['Commentaire_NS']; ?></td>
                                                    <td>
                                                        <a href="studentOralFormModify_administrateur.php?id=<?= $user1['ID_NS'] ?>&amp;nom_utilisateur=<?= urlencode($user['Nom_Evaluateur']) ?>">
                                                            <button type='button' class='btn bg bi bi-pencil-fill'></button>
                                                        </a>
                                                        <button type='button' class='btn red bi bi-trash-fill btn-delete' data-id="<?= $user1['ID_NS'] ?>" data-nomutilisateur="<?= $user1['Nom_Utilisateur'] ?>">
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
    <div class="container">
        <div id="divND">
            <div class="container-fluid space">
                <div class="panel" id="panel">
                    <table id="tableD2" class="display" style="width:100%">
                        <thead>
                            <tr class="bg">
                                <th>Etudiant</th>
                                <th>Nombre de note professionnels</th>
                                <th>Nombre de notes enseignants</th>
                                <th>Note calculée</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($arr_users2)) { ?>
                                <?php foreach ($arr_users2 as $user2) { ?>
                                    <tr class="user-row">
                                        <td class="text-center"><?= $user2['Nom_Utilisateur']; ?></td>
                                        <td class="text-center"><?= $user2['Nb_Professionnels']; ?></td>
                                        <td class="text-center"><?= $user2['Nb_Enseignants']; ?></td>
                                        <td class="text-center"><?= getStutdentGradeOral($user2["ID_UtilisateurEvalue"]);; ?></td>
                                    </tr>
                                <?php } ?>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
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

        var table = $('#tableD2').DataTable({
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





        let btnSD = document.querySelector('#btnSD');
        let btnND = document.querySelector('#btnND');
        let btnSP = document.querySelector('#btnSP');
        let divSD = document.querySelector('#divSD');
        let divND = document.querySelector('#divND');
        let divSP = document.querySelector('#divSP');
        let hidden = true;

        // Vérifie s'il y a une valeur stockée en session pour le bouton sélectionné
        if (sessionStorage.getItem('selectedButton') === 'btnND') {
            hidden = false;
            divND.removeAttribute('hidden');
            btnND.className.add('active');
            divSP.setAttribute('hidden', '');
            btnSP.className = "btn me-md-3 bg btn-custom";
            divSD.setAttribute('hidden', '');
            btnSD.className = "btn me-md-3 bg btn-custom";
        } else if (sessionStorage.getItem('selectedButton') === 'btnSP') {
            hidden = false;
            divSP.removeAttribute('hidden');
            btnSP.className.add('active');
            divND.setAttribute('hidden', '');
            btnND.className = "btn me-md-3 bg btn-custom";
            divSD.setAttribute('hidden', '');
            btnSD.className = "btn me-md-3 bg btn-custom";
        } else {
            divSD.removeAttribute('hidden');
            btnSD.className.add('active');
            divSP.setAttribute('hidden', '');
            btnSP.className = "btn me-md-3 bg btn-custom";
            divND.setAttribute('hidden', '');
            btnND.className = "btn me-md-3 bg btn-custom";
        }

        btnSD.addEventListener('click', () => {
            if (!hidden) {
                divSD.removeAttribute('hidden');
                btnSD.className.add('active');
                divND.setAttribute('hidden', '');
                divSP.setAttribute('hidden', '');
                btnND.className.remove('active');
                btnSP.className.remove('active');
                hidden = true;
                // Sauvegarde le choix du bouton en session
                sessionStorage.setItem('selectedButton', 'btnSD');
            }
        });

        btnND.addEventListener('click', () => {
            if (hidden) {
                divND.removeAttribute('hidden');
                btnND.className.add('active');
                divSP.setAttribute('hidden', '');
                divSD.setAttribute('hidden', '');
                btnSD.className.remove('active');
                btnSP.className.remove('active');
                hidden = false;
                // Sauvegarde le choix du bouton en session
                sessionStorage.setItem('selectedButton', 'btnND');
            }
        });
        /*
        btnSP.addEventListener('click', () => {
            if (hidden) {
                divSP.removeAttribute('hidden');
                btnSP.className.add('active');
                divND.setAttribute('hidden', '');
                divSD.setAttribute('hidden', '');
                btnND.className.remove('active');
                btnSD.className.remove('active');
                hidden = false;
                // Sauvegarde le choix du bouton en session
                sessionStorage.setItem('selectedButton', 'btnSP');
            }
        });*/



        $('.btn-delete').click(function() {
            var id = $(this).data('id');
            var user = $(this).data('nomutilisateur');
            if (confirm('Êtes-vous sûr de vouloir supprimer la note de ' + user + ' ?')) {
                window.location.href = 'studentOralDeletion_administrateur.php?id=' + id;
            }
        });
    });
</script>