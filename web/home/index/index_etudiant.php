<?php
if (!isConnectedUser()) {
    $_SESSION['success'] = 2;
    header("Location: login.php");
}

$db = Database::connect();


//Premier tableau : informations liées à l'étudiant
$sql = getStudentInformation_Etudiant($_SESSION["user_id"]);


$result = $db->query($sql);
$arr_users = [];
if ($result->rowCount() > 0) {
    $arr_users = $result->fetchAll();
}

//Deuxième tableau : informations liées à la notation

$sql = "SELECT Validation_NF FROM decisions WHERE ValidationScolarite_Planning = 'oui'";
$result = $db->query($sql);
$arr_evaluation = [];
if ($result->rowCount() > 0) {
    $sql = getStudentEvaluation_Etudiant($_SESSION["user_id"]);

    $result = $db->query($sql);
    if ($result->rowCount() > 0) {
        $arr_evaluation = $result->fetchAll();
    }

    $noteOral = getStutdentGradeOral($_SESSION["user_id"]);
}

//Troisième tableau : Information liées au planning

//on vérifie si la scolarité a validé 
$sql = "SELECT ValidationScolarite_Planning FROM decisions WHERE ValidationScolarite_Planning = 'oui'";
$result = $db->query($sql);

$arr_schedule = [];
if ($result->rowCount() > 0) {
    $sql = getStudentSchedule_Etudiant($_SESSION["user_id"]);

    $result = $db->query($sql);
    if ($result->rowCount() > 0) {
        $arr_schedule = $result->fetchAll();

        if ($arr_schedule[0]['DateSession_Planning'] != NULL && $arr_schedule[0]['HeureDebutSession_Planning'] != NULL) {

            // on récupère la durée d'une soutenance pour obtenir le début et la fin
            $sql = "SELECT `Description_param` FROM `parametres` WHERE `ID_param`='3'";
            $result = $db->query($sql);
            $duree = $result->fetch();

            // on extrait les heures et les minutes de la durée
            preg_match('/(\d{2}):(\d{2})/', $duree['Description_param'], $matches);
            $heures = intval($matches[1]);
            $minutes = intval($matches[2]);

            // on crée un nouvel objet DateInterval avec les heures et les minutes extraites
            $duree = new DateInterval('PT' . $heures . 'H' . $minutes . 'M');

            $date = $arr_schedule[0]['DateSession_Planning'];
            $heure_debut = $arr_schedule[0]['HeureDebutSession_Planning'];


            // on calcule l'heure de fin en ajoutant la durée à l'heure de début
            $heure_fin = new DateTime($heure_debut);
            $heure_fin = $heure_fin->add($duree);
            $heure_fin = $heure_fin->format('H:i:s');



            $dateTime = new DateTime($date);
            setlocale(LC_TIME, 'fr_FR.utf8', 'fra');
            $date_fr = strftime('%d/%m/%Y', $dateTime->getTimestamp());
        }
    }
}
?>


<html>
<div class="container-fluid">
    <h2 class="center margin-title colored">Accueil</h2>
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
                            <tr>
                                <td class="text-center"><?= $user['Nom_Etudiant']; ?></td>
                                <td class="text-center"><abbr title="<?= $user['Mail_Etudiant']; ?>"><?= shortString($user['Mail_Etudiant'], 10); ?></abbr></td>
                                <td class="text-center"><?= $user['Promo_Utilisateur']; ?></td>
                                <td class="text-center"><?= lineFeedWithSeparator($user['Entreprise_Invite']); ?></td>
                                <td class="text-center"><?= lineFeedWithSeparator($user['Ville_Invite']); ?></td>
                                <td class="text-center"><?= lineFeedWithSeparator($user['Nom_Invite']); ?></td>
                                <td class="text-center"><abbr title="<?= $user['Mail_Invite']; ?>"><?= shortString(lineFeedWithSeparator($user['Mail_Invite']), 10); ?></abbr></td>
                                <td class="text-center"><?= $user['Nom_Tuteur_Universitaire']; ?></td>
                                <td class="text-center"><abbr title="<?= $user['Mail_Tuteur_Universitaire']; ?>"><?= shortString($user['Mail_Tuteur_Universitaire'], 10); ?></abbr></td>
                                <td class="text-center"><?= $user['HuisClos_Utilisateur']; ?></td>
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
                                <td class="text-center">
                                    <?php
                                    if ($note['Note_Tuteur'] === null) {
                                        echo '<span class="text-danger">DEF</span>';
                                    } else {
                                        echo $note['Note_Tuteur'];
                                    }
                                    ?>
                                </td>
                                <td class="text-center">
                                    <?php
                                    if ($noteOral === "DEF") {
                                        echo '<span class="text-danger">DEF</span>';
                                    } else {
                                        echo $noteOral;
                                    }
                                    ?>
                                </td>
                                <td class="text-center">
                                    <?php
                                    if ($note['Note_Finale'] === null || $note['Poster_NF'] === "non" || $note['Rapport_NF'] === "non"  || $noteOral === "DEF") {
                                        echo '<span class="text-danger">DEF</span>';
                                    } else {
                                        echo $note['Note_Finale'];
                                    }
                                    ?>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <br>
        <h4>Informations soutenance</h4>
        <br>
        <div class="center">
            <?php
            if ($arr_schedule == [] || $arr_schedule[0]['DateSession_Planning'] == NULL || $arr_schedule[0]['HeureDebutSession_Planning'] == NULL) {
                echo '
                    <div class="row d-flex justify-content-center">
                        <div class="col-12 col-md-8 col-lg-6 col-xl-10">
                            <div class="card shadow-2-strong css-login">
                                <div class="card-body p-5">
                                    <div class="row">
                                    <p class="text-center">Vous ne pourrez acceder aux plannings de soutenances qu\'une fois que la Scolarité les aura validé.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    ';
            }
            if ($arr_schedule <> [] && $arr_schedule[0]['DateSession_Planning'] != NULL && $arr_schedule[0]['HeureDebutSession_Planning'] != NULL) {
                echo '
        <div class="row d-flex justify-content-center">
            <div class="col-12 col-md-8 col-lg-6 col-xl-10">
                <div class="card shadow-2-strong css-login">
                    <div class="card-body p-5">
                        <div class="row">
                            <p class="text-center">Votre soutenances pour l\'UE Projet Professionnel aura lieu le ' . $date_fr . ' de ' . $heure_debut . ' à ' . $heure_fin . '</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    ';
            }
            ?>
        </div>
    </div>

</html>
<script>
    $(document).ready(function() {
        $(".bar").fadeOut(1000, function() {
            $('#content').fadeIn();
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

    });
</script>