<?php
session_start();
include "../application_config/db_class.php";
include 'header.php';
include 'home/navigation/navbar.php';
$conn = Database::connect();
$annee = date('Y');

if (isset($_GET['success'])) {
    echo '<script>alert("Utilisateur modifié avec succès !");</script>';
}

if (isset($_GET['success_create'])) {
    echo '<script>alert("Utilisateur ajouté avec succès !");</script>';
}


$sql = "SELECT H.Id_Utilisateur, U.Nom_Utilisateur, '****' AS MDP_Utilisateur, H.Admin_Habilitations, H.ResponsableUE_Habilitations, H.Scolarite_Habilitations, 
H.TuteurUniversitaire_Habilitations, H.Etudiant_Habilitations 
FROM habilitations H 
JOIN utilisateur U 
ON U.Id_Utilisateur = H.Id_Utilisateur;";
$result = $conn->query($sql);
$arr_users = [];
if ($result->rowCount() > 0) {
    $arr_users = $result->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="en">
<body>
<div class="container-fluid">
    <h2 class="center colored">Comptes</h2>
    <hr>
    <br>
    <br>
    <div class="panel" id="panel">
        <label for="habilitation-filter">Filtrer par habilitation :</label>
        <select id="habilitation-filter">
            <option value="">Toutes les habilitations</option>
            <option value="Admin">Admin</option>
            <option value="ResponsableUE">Responsable UE</option>
            <option value="Scolarite">Scolarité</option>
            <option value="TuteurUniversitaire">Tuteur Universitaire</option>
            <option value="Etudiant">Étudiant</option>
        </select>
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
                <?php if(!empty($arr_users)) { ?>
                    <?php foreach($arr_users as $user) { ?>
                        <tr class="user-row">
                            <td class="text-center"><?php echo $user['Nom_Utilisateur']; ?></td>
                            <td class="text-center"><?php echo $user['MDP_Utilisateur']; ?></td>
                            <td class="text-center"><?php echo $user['Admin_Habilitations']; ?></td>
                            <td class="text-center"><?php echo $user['ResponsableUE_Habilitations']; ?></td>
                            <td class="text-center"><?php echo $user['Scolarite_Habilitations']; ?></td>
                            <td class="text-center"><?php echo $user['TuteurUniversitaire_Habilitations']; ?></td>
                            <td class="text-center"><?php echo $user['Etudiant_Habilitations']; ?></td>
                            <td>
                                <a href="formUpdateHabilitations.php?id=<?php echo $user['Id_Utilisateur'] ?>">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>
                                <button class="btn-delete" data-id="<?php echo $user['Id_Utilisateur'] ?>" data-nomutilisateur="<?php echo $user['Nom_Utilisateur'] ?>">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            </td>
                        </tr>
                    <?php } ?>
                            <?php } ?>
                                </tbody>
                            </table>
                            <a href="formCreateHabilitationUser.php" class="btn btn-primary">Créer un compte</a>
                        </div>
                    </div>

<script>
    $(document).ready(function () {
        $('#example').DataTable({
            stateSave: true,
            language: {
                url: "//cdn.datatables.net/plug-ins/1.13.2/i18n/fr-FR.json"
            },
            order: [[3, 'desc']],
            dom: 'Blfrtip',
            buttons: ['excel'],
        });

        $('.btn-delete').click(function () {
            var id = $(this).data('id');
            var user = $(this).data('nomutilisateur');
            if (confirm('Êtes-vous sûr de vouloir supprimer l\'utilisateur "' + user + '" ?')) {
                window.location.href = 'delete_user_habilitation.php?id=' + id;
            }
        });

        $('#habilitation-filter').on('change', function() {
            var selectedValue = $(this).val();
            if(selectedValue !== '') {
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

</body>
</html>