<?php
$conn = Database::connect();
$annee = date('Y');

$sql = "SELECT 
    u1.ID_Utilisateur AS ID_Etudiant,
    u1.Nom_Utilisateur AS Nom_Etudiant,
    u1.Mail_Utilisateur AS Mail_Etudiant,
    u1.Promo_Utilisateur,
    u1.HuitClos_Utilisateur,
    u2.Nom_Utilisateur AS Nom_Tuteur_Universitaire,
    u2.Mail_Utilisateur AS Mail_Tuteur_Universitaire,
    i.Entreprise_Invite,
    i.Ville_Invite,
    i.Nom_Invite,
    i.Mail_Invite,
    TRIM(BOTH ', ' FROM CONCAT_WS(', ',
        CASE WHEN SUM(h.Admin_Habilitations = 'oui') > 0 THEN 'Admin' ELSE NULL END,
        CASE WHEN SUM(h.ResponsableUE_Habilitations = 'oui') > 0 THEN 'ResponsableUE' ELSE NULL END,
        CASE WHEN SUM(h.Scolarite_Habilitations = 'oui') > 0 THEN 'Scolarite' ELSE NULL END,
        CASE WHEN SUM(h.Etudiant_Habilitations = 'oui') > 0 THEN 'Etudiant' ELSE NULL END,
        CASE WHEN SUM(h.TuteurUniversitaire_Habilitations = 'oui') > 0 THEN 'TuteurUniversitaire' ELSE NULL END
    )) AS Roles
FROM
    Utilisateur u1
        LEFT JOIN
    etudiant_tuteur et ON u1.id_Utilisateur = et.id_Etudiant
        LEFT JOIN
    Utilisateur u2 ON et.id_Tuteur = u2.id_Utilisateur
        LEFT JOIN
    est_apprenti ea ON u1.id_utilisateur = ea.id_utilisateur
        LEFT JOIN
    invite i ON ea.id_invite = i.id_invite
        LEFT JOIN
    habilitations h ON u1.ID_Utilisateur = h.ID_Utilisateur
GROUP BY u1.ID_Utilisateur;
";


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
                    <td class="text-center"><?php echo $user['Entreprise_Invite']; ?></td>
                    <td class="text-center"><?php echo $user['Ville_Invite']; ?></td>
                    <td class="text-center"><?php echo $user['Nom_Invite']; ?></td>
                    <td class="text-center"><?php echo $user['Mail_Invite']; ?></td>
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


        $('#habilitation-filter').on('change', function () {
            var habilitation = $(this).val();
            table.column(10).search(habilitation ? '(^|, )' + habilitation + '(,|$)' : '', true, false).draw();
        });
    });
</script>
