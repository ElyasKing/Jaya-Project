<?php
$conn = Database::connect();
$annee = date('Y');

$sql = getStudentInformationForIndexes();


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
                <th> </th>
            </tr>
        </thead>
        <tbody>
        <?php if(!empty($arr_users)) { ?>
            <?php foreach($arr_users as $user) { ?>
                <tr <?php if(empty($user['Promo_Utilisateur']) || empty($user['Entreprise_Invite']) ||  
                            empty($user['Ville_Invite']) || empty($user['Nom_Invite']) || empty($user['Mail_Invite']) ||  empty($user['Nom_Tuteur_Universitaire']) || 
                            empty($user['Mail_Tuteur_Universitaire']) || empty($user['HuitClos_Utilisateur'])) { echo "style='background-color: rgba(255, 255, 0, 0.199);'";} ?> >
                    <td class="text-center"><?= $user['Nom_Etudiant']; ?></td>
                    <td class="text-center"><?= $user['Mail_Etudiant']; ?></td>
                    <td class="text-center"><?= $user['Promo_Utilisateur']; ?></td>
                    <td class="text-center"><?= lineFeedWithSeparator($user['Entreprise_Invite']); ?></td>
                    <td class="text-center"><?= lineFeedWithSeparator($user['Ville_Invite']); ?></td>
                    <td class="text-center"><?= lineFeedWithSeparator($user['Nom_Invite']); ?></td>
                    <td class="text-center"><?= lineFeedWithSeparator($user['Mail_Invite']); ?></td>
                    <td class="text-center"><?= $user['Nom_Tuteur_Universitaire']; ?></td>
                    <td class="text-center"><?= $user['Mail_Tuteur_Universitaire']; ?></td>
                    <td class="text-center"><?= $user['HuitClos_Utilisateur']; ?></td>
                    <td class="text-center" style="display:none;"><?= $user['Roles']; ?>
                    <td><a href="indexStudentUpdate_administrateur.php?id=<?= $user['ID_Etudiant'] ?>"><i class="bi bi-pencil-fill"></i></a></td>
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