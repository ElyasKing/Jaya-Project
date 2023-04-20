<?php
$db = Database::connect();

$sql = getStudentInformation_Etudiant($_SESSION["user_id"]);


$result = $db->query($sql);
$arr_users = [];
if ($result->rowCount() > 0) {
    $arr_users = $result->fetchAll();
}

//var_dump($arr_users);

$sql = getStudentEvaluation_Etudiant($_SESSION["user_id"]);

$result = $db->query($sql);
$arr_evaluation = [];
if ($result->rowCount() > 0) {
    $arr_evaluation = $result->fetchAll();
}

//var_dump($arr_evaluation);

$noteOral = getStutdentGradeOral($_SESSION["user_id"]);


?>




<div class="container-fluid space">
    <h2 class="center colored">Accueil</h2>
    <hr>
    <br>
    <div class="container">
        <h4>Information personnelles</h4>
        <br>
        <div class="panel" id="panel1">
            <table id="table1" class="display" style="width:100%">
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
                    <?php if (!empty($arr_users)) { ?>
                        <?php foreach ($arr_users as $user) { ?>
                            <tr>
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
                            </tr>
                        <?php } ?>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <br>
        <h4>Informations évaluation</h4>
        <br>
        <div class="panel" id="panel2">
            <table id="table2" class="display" style="width:100%">
                <thead>
                    <tr class="bg">
                        <th>Poster rendu</th>
                        <th>Rapport rendu</th>
                        <th>Note de suivi</th>
                        <th>Note d'oral</th>
                        <th>Note Projet Professionnel</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($arr_evaluation)) { ?>
                        <?php foreach ($arr_evaluation as $note) { ?>
                            <tr>
                                <td class="text-center"><?= $note['Poster_NF']; ?></td>
                                <td class="text-center"><?= $note['Rapport_NF']; ?></td>
                                <td class="text-center"><?= $note['Note_Tuteur']; ?></td>
                                <td class="text-center"><?= $noteOral ?></td>
                                <td class="text-center"><?= $note['Note_Finale']; ?></td>
                            </tr>
                        <?php } ?>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        var table1 = $('#table1').DataTable({
            language: {
                url: "//cdn.datatables.net/plug-ins/1.13.2/i18n/fr-FR.json"
            },
            "ordering": false,
            "searching": false,
            "paging": false,
            "info": false,
            "lengthChange": false
        });

        var table2 = $('#table2').DataTable({
            language: {
                url: "//cdn.datatables.net/plug-ins/1.13.2/i18n/fr-FR.json"
            },
            "ordering": false,
            "searching": false,
            "paging": false,
            "info": false,
            "lengthChange": false
        });

    });
</script>