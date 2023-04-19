<?php
$db = Database::connect();
$annee = date('Y');

$sql = getStudentInformationForIndexes();


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
                <th>Email Etudiant</th>
                <th>Promo</th>
                <th>Entreprise</th>
                <th>Ville</th>
                <th>Maitre d'apprentissage</th>
                <th>Email MA</th>
                <th>Tuteur universitaire</th>
                <th>Email TU</th>
                <th>Huit clos</th>
                <th style="display:none;">Roles</th>
            </tr>
        </thead>
        <tbody>
        <?php if(!empty($arr_users)) { ?>
            <?php foreach($arr_users as $user) { ?>
                <tr>
                    <td class="text-center"><?php echo $user['Nom_Etudiant']; ?></td>
                    <td class="text-center"><?php echo $user['Mail_Etudiant']; ?></td>
                    <td class="text-center"><?php echo $user['Promo_Utilisateur']; ?></td>
                    <td class="text-center"><?php echo lineFeedWithSeparator($user['Entreprise_Invite']); ?></td>
                    <td class="text-center"><?php echo lineFeedWithSeparator($user['Ville_Invite']); ?></td>
                    <td class="text-center"><?php echo lineFeedWithSeparator($user['Nom_Invite']); ?></td>
                    <td class="text-center"><?php echo lineFeedWithSeparator($user['Mail_Invite']); ?></td>
                    <td class="text-center"><?php echo $user['Nom_Tuteur_Universitaire']; ?></td>
                    <td class="text-center"><?php echo $user['Mail_Tuteur_Universitaire']; ?></td>
                    <td class="text-center"><?php echo $user['HuitClos_Utilisateur']; ?></td>
                    <td class="text-center" style="display:none;"><?php echo $user['Roles']; ?>
                </tr>
            <?php } ?>
        <?php } ?>
        </tbody>
    </table>
    </div>
</div>

<script>
    $(document).ready(function () {
        var table = $('#example').DataTable({
            stateSave: true,
            language : {
                url : "//cdn.datatables.net/plug-ins/1.13.2/i18n/fr-FR.json"
            },
            order: [[3, 'desc']],
            dom: 'Blfrtip',
            buttons: ['excel'],
        });

    });
</script>
