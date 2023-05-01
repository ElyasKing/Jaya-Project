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

            $sql = "SELECT 
                    H.Id_Utilisateur, 
                    U.Nom_Utilisateur, 
                    '****' AS MDP_Utilisateur, 
                    H.Admin_Habilitations, 
                    H.ResponsableUE_Habilitations, 
                    H.Scolarite_Habilitations, 
                    H.TuteurUniversitaire_Habilitations, 
                    H.Etudiant_Habilitations 
                FROM habilitations H 
                JOIN utilisateur U ON U.Id_Utilisateur = H.Id_Utilisateur;";

            $result = $db->query($sql);

            $arr_users = [];
            if ($result->rowCount() > 0) {
                $arr_users = $result->fetchAll();
            }
            ?>

            <div class="container-fluid space">
                <h2 class="center colored">Comptes</h2>
                <hr>
                <br>
                <br>
                <div class="panel" id="panel">
                    <div class="col-6 col-md-4 mx-auto">
                        <select id='habilitation-filter' class='form-select'>
                            <option value="">Toutes les habilitations</option>
                            <option value="Admin">Administrateur</option>
                            <option value="ResponsableUE">Responsable d'UE</option>
                            <option value="Scolarite">Scolarité</option>
                            <option value="TuteurUniversitaire">Tuteur universitaire</option>
                            <option value="Etudiant">Étudiant</option>
                        </select>
                    </div>
                    <table id="example" class="display" style="width:100%">
                        <thead>
                            <tr class="bg">
                                <th>Utilisateur</th>
                                <th>Mot de passe</th>
                                <th>Administrateur</th>
                                <th>Responsable d'UE</th>
                                <th>Scolarité</th>
                                <th>Tuteur universitaire</th>
                                <th>Etudiant</th>
                                <th> </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($arr_users)) { ?>
                                <?php foreach ($arr_users as $user) { ?>
                                    <tr class="user-row">
                                        <td class="text-center"><?= $user['Nom_Utilisateur']; ?></td>
                                        <td class="text-center"><?= $user['MDP_Utilisateur']; ?></td>
                                        <td class="text-center"><?= $user['Admin_Habilitations']; ?></td>
                                        <td class="text-center"><?= $user['ResponsableUE_Habilitations']; ?></td>
                                        <td class="text-center"><?= $user['Scolarite_Habilitations']; ?></td>
                                        <td class="text-center"><?= $user['TuteurUniversitaire_Habilitations']; ?></td>
                                        <td class="text-center"><?= $user['Etudiant_Habilitations']; ?></td>
                                        <td>
                                            <a href="accountManagerUserUpdate_administrateur.php?id=<?= $user['Id_Utilisateur'] ?>">
                                                <button type="button" class="btn bg bi bi-pencil-fill"></button>
                                            </a>
                                            <?php if ($user['Nom_Utilisateur'] != $_SESSION["user_name"]) { ?>
                                                <button class="btn red bi bi-trash-fill btn-delete" data-id="<?= $user['Id_Utilisateur'] ?>" data-nomutilisateur="<?= $user['Nom_Utilisateur'] ?>">
                                                </button>
                                            <?php }else { ?>
                                               <button disabled class="btn red bi bi-trash-fill btn-delete" data-id="<?= $user['Id_Utilisateur'] ?>" data-nomutilisateur="<?= $user['Nom_Utilisateur'] ?>">
                                               </button> 
                                               <?php
                                            } ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            <?php } ?>
                        </tbody>
                    </table>
                    <br>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a type="button" href="accountManagerUserCreation_administrateur.php" class="btn me-md-3 btn-custom bg">Créer un compte</a>
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
                    echo '<script>toastr.success("Utilisateur modifié avec succès !");</script>';
                    break;
                case 2:
                    echo '<script>toastr.success("Utilisateur ajouté avec succès !");</script>';
                    break;
                case 3:
                    echo '<script>toastr.success("Utilisateur supprimé avec succès !");</script>';
                    break;
                case 22:
                    echo '<script>toastr.error("Cette adresse mail est déjà associée à un compte utilisateur.");</script>';
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
                            [3, 'desc']
                        ],
                        dom: 'lfrtip'
                    });

                    $('.btn-delete').click(function() {
                        var id = $(this).data('id');
                        var user = $(this).data('nomutilisateur');
                        if (confirm('Êtes-vous sûr de vouloir supprimer l\'utilisateur "' + user + '" ?')) {
                            window.location.href = 'accountManagerCheckUserDeletion_administrateur.php?id=' + id;
                        }
                    });

                    $('#habilitation-filter').on('change', function() {
                        var selectedValue = $(this).val();
                        if (selectedValue !== '') {
                            $('.user-row').hide();
                            $('.user-row td:nth-child(' + (['Admin', 'ResponsableUE', 'Scolarite', 'TuteurUniversitaire', 'Etudiant'].indexOf(selectedValue) + 3) + ')').filter(function() {
                                return $(this).text() === 'oui';
                            }).parent().show();
                        } else {
                            $('.user-row').show();
                        }
                    });
                });
            </script>

        </div>
    </div>
</body>

</html>