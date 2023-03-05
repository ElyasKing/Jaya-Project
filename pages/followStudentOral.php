<?php

include "../application_config/db_class.php";
$conn = Database::connect();

$sql = "SELECT * FROM Utilisateur";
$result = $conn->query($sql);
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
                <th>Nombre de notes professionnels</th>
                <th>Nombre de notes enseignants</th>
                <th>Note calculée</th>
            </tr>
        </thead>
        <tbody>
            <?php if(!empty($arr_users)) { ?>
            <?php foreach($arr_users as $user) { ?>
                <tr>
                    <td class="text-center"><?php echo $user['Nom_Utilisateur']; ?></td>
                    <td class="text-center"><?php echo $user['Mail_Utilisateur']; ?></td>
                    <td class="text-center"><?php echo $user['Promo_Utilisateur']; ?></td>
                    <td class="text-center"><?php echo $user['HuitClos_Utilisateur']; ?></td>
                </tr>
            <?php } ?>
        <?php } ?>
        </tbody>
    </table>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#example').DataTable({
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