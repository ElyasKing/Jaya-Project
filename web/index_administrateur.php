<?php
$db = Database::connect();

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
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($arr_users)) { ?>
                    <?php foreach ($arr_users as $user) {?>
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
                            <td><a href="indexStudentUpdate_administrateur.php?id=<?= $user['ID_Etudiant'] ?>"><button type="button" class="btn bg bi bi-pencil-fill"></button></a></td>
                        </tr>
                    <?php } ?>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
<br>
<div class="d-grid gap-2 d-md-flex justify-content-md-end">
    <button type="button" id="btn-importer-admin" class="btn me-md-3 bg btn-custom">Importer</button>
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
if (isset($_SESSION['success']) && $_SESSION['success'] == 1) {
	$success = $_SESSION['success'];

	if ($success == 1) {
		echo "<script>toastr.success(\"Utilisateur modifié avec succès !\");</script>";
	}
	$_SESSION['success'] = 0;
}
?>
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
            dom: 'Blfrtip',
            buttons: ['excel'],
        });
    });
</script>