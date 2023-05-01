<?php
if(!isConnectedUser()){
    $_SESSION['success'] = 2;
    header("Location: login.php");
}

$db = Database::connect();

$sql = getStudentInformation_TuteurUniversitaire($_SESSION["user_id"]);


$result = $db->query($sql);
$arr_users = [];
if ($result->rowCount() > 0) {
    $arr_users = $result->fetchAll();
}
?>



<div class="container-fluid space">
    <h2 class="center colored">Accueil</h2>
    <hr>
    <br>
    <br>
    <div class="panel" id="panel">
        <table id="example" class="display" style="width:100%">
            <thead>
                <tr class="bg">
                    <th>Etudiant</th>
                    <th>Email étudiant</th>
                    <th>Promo</th>
                    <th>Entreprise</th>
                    <th>Ville</th>
                    <th>Tuteur entreprise</th>
                    <th>Email(s) tuteur(s) entreprise</th>
                    <th>Tuteur universitaire</th>
                    <th>Email tuteur universitaire</th>
                    <th>Huis clos</th>
                    <th style="display:none;">Roles</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($arr_users)) { ?>
                    <?php foreach ($arr_users as $user) { ?>
                        <tr <?php if (
                                empty($user['Promo_Utilisateur']) || empty($user['Entreprise_Invite']) ||
                                empty($user['Ville_Invite']) || empty($user['Nom_Invite']) || empty($user['Mail_Invite']) ||  empty($user['Nom_Tuteur_Universitaire']) ||
                                empty($user['Mail_Tuteur_Universitaire']) || empty($user['HuisClos_Utilisateur'])
                            ) {
                                echo 'class="tr-bgColor"';
                            } ?>>
                            <td class="text-center"><?= $user['Nom_Etudiant']; ?></td>
                            <td class="text-center"><abbr title="<?= $user['Mail_Etudiant']; ?>"><?= shortString($user['Mail_Etudiant'],10); ?></abbr></td>
                            <td class="text-center"><?= $user['Promo_Utilisateur']; ?></td>
                            <td class="text-center"><?= lineFeedWithSeparator($user['Entreprise_Invite']); ?></td>
                            <td class="text-center"><?= lineFeedWithSeparator($user['Ville_Invite']); ?></td>
                            <td class="text-center"><?= lineFeedWithSeparator($user['Nom_Invite']); ?></td>
                            <td class="text-center"><abbr title="<?= $user['Mail_Invite']; ?>"><?= shortString(lineFeedWithSeparator($user['Mail_Invite']),10); ?></abbr></td>
                            <td class="text-center"><?= $user['Nom_Tuteur_Universitaire']; ?></td>
                            <td class="text-center"><abbr title="<?= $user['Mail_Tuteur_Universitaire']; ?>"><?= shortString($user['Mail_Tuteur_Universitaire'],10); ?></abbr></td>
                            <td class="text-center"><?= $user['HuisClos_Utilisateur']; ?></td>
                            <td class="text-center" style="display:none;"><?= $user['Roles']; ?>
                        </tr>
                    <?php } ?>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

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
            dom: 'lfrtip'
        });
    });
</script>