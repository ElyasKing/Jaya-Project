<?php
include("../../../../application_config/db_class.php");
include("../../../../fonctions/functions.php");
session_start();

if (!isConnectedUser()) {
    $_SESSION['success'] = 2;
    header("Location: logout.php");
}
?>

<!DOCTYPE html>
<html>

<head>
    <?php
    include("../../navigation/header.php");
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

            $db = Database::connect();

            // ID : 5 = nombre minimum de note professionnel, ID : 6 = nombre minimum de note enseignant, ID :3 : Duree d'une duree de soutenance, ID 11 : Temps supplémentaire (cf DB)
            $sql = "SELECT `Description_param` FROM `parametres` WHERE `ID_param`='5' OR `ID_param`='6' OR `ID_param`='3' OR `ID_param`='11';";
            $result = $db->query($sql);

            $rows = $result->fetchAll();
            $duree_soutenance = $rows[0]['Description_param'];
            $note_pro = $rows[1]['Description_param'];
            $note_enseignant = $rows[2]['Description_param'];
            $duree_supp = $rows[3]['Description_param'];

            /*
            Requete pour l'index 1 
            */

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

            /*
            Requete pour l'index 2
            */

            $sql = "SELECT
            NS.ID_UtilisateurEvalue, 
            U.ID_Utilisateur, 
            U.Nom_Utilisateur, 
            COUNT(CASE WHEN I.EstProfessionel_Invite = 'oui' AND I.EstEnseignant_Invite = 'non'  AND NS.ID_UtilisateurEvaluateur IS NULL THEN 1 END) AS Nb_Professionnels, 
            COUNT(CASE WHEN I.EstProfessionel_Invite = 'oui' AND I.EstEnseignant_Invite = 'oui' OR NS.ID_UtilisateurEvaluateur IS NOT NULL THEN 1 END) AS Nb_Enseignants 
            FROM 
            utilisateur U 
            INNER JOIN planning P ON U.ID_Planning = P.ID_Planning
            LEFT JOIN notes_soutenance NS ON NS.ID_UtilisateurEvalue = U.ID_Utilisateur
            LEFT JOIN invite I ON I.ID_Invite = NS.ID_InviteEvaluateur OR I.ID_Invite = NS.ID_UtilisateurEvaluateur
            WHERE 
            (CONCAT(P.DateSession_Planning, ' ', P.HeureDebutSession_Planning) <= NOW() AND 
            ADDTIME(CONCAT(P.DateSession_Planning, ' ', P.HeureDebutSession_Planning, ':00'), SEC_TO_TIME(TIME_TO_SEC('$duree_soutenance') + TIME_TO_SEC('$duree_supp'))) >= NOW())
            OR U.SoutenanceSupp_Utilisateur = 'oui'
            GROUP BY 
            U.ID_Utilisateur, 
            U.Nom_Utilisateur;
        ";
            //Where clause à rajouter plus tard



            $result = $db->query($sql);

            $arr_users2 = [];
            if ($result->rowCount() > 0) {
                $arr_users2 = $result->fetchAll();
            }



            /*
            Requete pour l'index 3
            */

            $sql = "SELECT
            NS.ID_UtilisateurEvalue,
            U.ID_Utilisateur, 
            U.Nom_Utilisateur,
            U.SoutenanceSupp_Utilisateur, 
            COUNT(CASE WHEN I.EstProfessionel_Invite = 'oui' AND I.EstEnseignant_Invite = 'non' AND NS.ID_UtilisateurEvaluateur IS NULL THEN 1 END) AS Nb_Professionnels, 
            COUNT(CASE WHEN I.EstProfessionel_Invite = 'oui' AND I.EstEnseignant_Invite = 'oui' OR NS.ID_UtilisateurEvaluateur IS NOT NULL THEN 1 END) AS Nb_Enseignants 
            FROM 
            utilisateur U 
            INNER JOIN planning P ON U.ID_Planning = P.ID_Planning
            LEFT JOIN notes_soutenance NS ON NS.ID_UtilisateurEvalue = U.ID_Utilisateur
            LEFT JOIN invite I ON I.ID_Invite = NS.ID_InviteEvaluateur OR I.ID_Invite = NS.ID_UtilisateurEvaluateur 
            WHERE 
            P.DateSession_Planning < CURRENT_DATE() OR 
            (P.DateSession_Planning = CURRENT_DATE() AND 
            ADDTIME(CONCAT(P.DateSession_Planning, ' ', P.HeureDebutSession_Planning, ':00'), SEC_TO_TIME(TIME_TO_SEC('$duree_soutenance') + TIME_TO_SEC('$duree_supp'))) < NOW())
            GROUP BY 
            U.ID_Utilisateur, 
            U.Nom_Utilisateur
            HAVING 
            Nb_Professionnels < $note_pro OR Nb_Enseignants  < $note_enseignant OR U.SoutenanceSupp_Utilisateur = 'oui';

        ";
            //Where clause à rajouter plus tard

            $result = $db->query($sql);

            $arr_users3 = [];
            if ($result->rowCount() > 0) {
                $arr_users3 = $result->fetchAll();
            }


            //Début du main
            ?>




            <div class="container-fluid">
                <h2 class="center margin-title colored">Soutenances</h2>
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
                                                    <a href="studentOralFormModify_administrateur.php?id=<?= $user1['ID_NS'] ?>&amp;nom_utilisateur=<?= urlencode($user1['Nom_Evaluateur']) ?>">
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
                    <div id="divND">
                        <div class="panel" id="panel">
                            <table id="tableD2" class="display" style="width:100%">
                                <thead>
                                    <tr class="bg">
                                        <th>Etudiant</th>
                                        <th>Nombre de notes professionnelles</th>
                                        <th>Nombre de notes enseignants</th>
                                        <th>Note calculée</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($arr_users2)) { ?>
                                        <?php foreach ($arr_users2 as $user2) { ?>
                                            <tr <?php if (
                                                    $user2['Nb_Professionnels'] < $note_pro || $user2['Nb_Enseignants'] < $note_enseignant
                                                ) {
                                                    echo 'class="tr-bgColorYellow"';
                                                } else echo 'class="tr-bgColorGreen"' ?>>
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
                    <div id="divSP">
                        <div class="panel" id="panel">
                            <table id="tableD3" class="display" style="width:100%">
                                <thead>
                                    <tr class="bg">
                                        <th>Etudiant</th>
                                        <th>Nombre de note professionnels</th>
                                        <th>Nombre de notes enseignants</th>
                                        <th>Note calculée</th>
                                        <th> </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($arr_users3)) { ?>
                                        <?php foreach ($arr_users3 as $user3) { ?>
                                            <tr <?php if (
                                                    $user3['Nb_Professionnels'] < $note_pro || $user3['Nb_Enseignants'] < $note_enseignant
                                                ) {
                                                    echo 'class="tr-bgColorRed"';
                                                } else echo 'class="tr-bgColorGreen"' ?>>
                                                <td class="text-center"><?= $user3['Nom_Utilisateur']; ?></td>
                                                <td class="text-center"><?= $user3['Nb_Professionnels']; ?></td>
                                                <td class="text-center"><?= $user3['Nb_Enseignants']; ?></td>
                                                <td class="text-center"><?= getStutdentGradeOral($user3["ID_UtilisateurEvalue"]);; ?></td>
                                                <td>
                                                    <button type='button' class='btn bg bi <?= $user3['SoutenanceSupp_Utilisateur'] == 'oui' ? 'bi-lock-fill' : 'bi-unlock-fill' ?> btn-addSession' data-id="<?= $user3['ID_Utilisateur'] ?>" data-session="<?= $user3['SoutenanceSupp_Utilisateur'] ?>"></button>
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
            <br>
            <div class="d-grid gap-2 d-md-flex justify-content-md-end container">
                <a type="button" href="../tuteurU/studentOralFormNew_tuteurUniversitaire.php" class="btn me-md-3 btn-custom bg">Ajouter note</a>
            </div>
        </div>
    </div>
</body>

</html>
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
$success = $_SESSION['success'];
switch ($success) {
    case 1:
        echo '<script>toastr.success("Note modifée avec succès !");</script>';
        break;
    case 2:
        echo '<script>toastr.success("Note supprimée avec succès !");</script>';
        break;
    case 3:
        echo '<script>toastr.success("Session supplémentaire ajoutée avec succès !");</script>';
        break;
    case 4:
        echo '<script>toastr.success("Session supplémentaire supprimée avec succès !");</script>';
        break;
    default:
        // rien
}
$_SESSION['success'] = 0;
?>
<script>
    $(document).ready(function() {
        $(".bar").fadeOut(1000, function() {
            $('#content').fadeIn();
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

            var table = $('#tableD3').DataTable({
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
            let divSD = document.querySelector('#divSD');
            let divND = document.querySelector('#divND');
            let btnSP = document.querySelector('#btnSP');
            let divSP = document.querySelector('#divSP');
            let hidden = true;

            // Vérifie s'il y a une valeur stockée en session pour le bouton sélectionné
            if (sessionStorage.getItem('selectedButton') === 'btnND') {
                hidden = false;
                divND.removeAttribute('hidden');
                btnND.className = "btn me-md-3 bg btn-custom active";
                divSD.setAttribute('hidden', '');
                btnSD.className = "btn me-md-3 bg btn-custom";
                divSP.setAttribute('hidden', '');
                btnSP.className = "btn me-md-3 bg btn-custom";
            } else if (sessionStorage.getItem('selectedButton') === 'btnSP') {
                hidden = false;
                divSP.removeAttribute('hidden');
                btnSP.className = "btn me-md-3 bg btn-custom active";
                divND.setAttribute('hidden', '');
                btnND.className = "btn me-md-3 bg btn-custom";
                divSD.setAttribute('hidden', '');
                btnSD.className = "btn me-md-3 bg btn-custom";
            } else {
                divSD.removeAttribute('hidden');
                btnSD.className = "btn me-md-3 bg btn-custom active";
                divND.setAttribute('hidden', '');
                btnND.className = "btn me-md-3 bg btn-custom";
                divSP.setAttribute('hidden', '');
                btnSP.className = "btn me-md-3 bg btn-custom";
            }

            btnSD.addEventListener('click', () => {
                if (hidden) {
                    divSD.removeAttribute('hidden');
                    btnSD.className = "btn me-md-3 bg btn-custom active";
                    divND.setAttribute('hidden', '');
                    btnND.className = "btn me-md-3 bg btn-custom";
                    divSP.setAttribute('hidden', '');
                    btnSP.className = "btn me-md-3 bg btn-custom";
                    hidden = false;
                    // Sauvegarde le choix du bouton en session
                    sessionStorage.setItem('selectedButton', 'btnSD');
                }
                if (!hidden) {
                    divSD.removeAttribute('hidden');
                    btnSD.className = "btn me-md-3 bg btn-custom active";
                    divND.setAttribute('hidden', '');
                    btnND.className = "btn me-md-3 bg btn-custom";
                    divSP.setAttribute('hidden', '');
                    btnSP.className = "btn me-md-3 bg btn-custom";
                    hidden = true;
                    // Sauvegarde le choix du bouton en session
                    sessionStorage.setItem('selectedButton', 'btnSD');
                }
            });

            btnND.addEventListener('click', () => {
                if (hidden) {
                    divND.removeAttribute('hidden');
                    btnND.className = "btn me-md-3 bg btn-custom active";
                    divSD.setAttribute('hidden', '');
                    btnSD.className = "btn me-md-3 bg btn-custom";
                    divSP.setAttribute('hidden', '');
                    btnSP.className = "btn me-md-3 bg btn-custom";
                    hidden = false;
                    // Sauvegarde le choix du bouton en session
                    sessionStorage.setItem('selectedButton', 'btnND');
                }
                if (!hidden) {
                    divND.removeAttribute('hidden');
                    btnND.className = "btn me-md-3 bg btn-custom active";
                    divSD.setAttribute('hidden', '');
                    btnSD.className = "btn me-md-3 bg btn-custom";
                    divSP.setAttribute('hidden', '');
                    btnSP.className = "btn me-md-3 bg btn-custom";
                    hidden = true;
                    // Sauvegarde le choix du bouton en session
                    sessionStorage.setItem('selectedButton', 'btnND');
                }
            });

            btnSP.addEventListener('click', () => {
                if (hidden) {
                    divSP.removeAttribute('hidden');
                    btnSP.className = "btn me-md-3 bg btn-custom active";
                    divSD.setAttribute('hidden', '');
                    btnSD.className = "btn me-md-3 bg btn-custom";
                    divND.setAttribute('hidden', '');
                    btnND.className = "btn me-md-3 bg btn-custom";
                    hidden = false;
                    // Sauvegarde le choix du bouton en session
                    sessionStorage.setItem('selectedButton', 'btnSP');
                }
                if (!hidden) {
                    divSP.removeAttribute('hidden');
                    btnSP.className = "btn me-md-3 bg btn-custom active";
                    divND.setAttribute('hidden', '');
                    btnND.className = "btn me-md-3 bg btn-custom";
                    divSD.setAttribute('hidden', '');
                    btnSD.className = "btn me-md-3 bg btn-custom";
                    hidden = true;
                    // Sauvegarde le choix du bouton en session
                    sessionStorage.setItem('selectedButton', 'btnSP');
                }
            });



            $('.btn-delete').click(function() {
                var id = $(this).data('id');
                var user = $(this).data('nomutilisateur');
                if (confirm('Êtes-vous sûr de vouloir supprimer la note de ' + user + ' ?')) {
                    window.location.href = 'studentOralDeletion_administrateur.php?id=' + id;
                }
            });

            $('.btn-addSession').click(function() {
                var id = $(this).data('id');
                var session = $(this).data('session');
                if (session == "oui") {
                    if (confirm('Souhaitez vous vraiment fermer la session supplémentaire ouverte pour cette étudiant ?')) {
                        window.location.href = 'studentOralDeletionSession_administrateur.php?id=' + id;
                    }
                } else {
                    if (confirm('Souhaitez vous vraiment réouvrir une session supplémentaire pour cette étudiant ?')) {
                        window.location.href = 'studentOralAddSession_administrateur.php?id=' + id;
                    }
                }
            });
        });
    });
</script>