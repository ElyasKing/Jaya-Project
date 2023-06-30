<?php
include("../../../application_config/db_class.php");
include("../../../fonctions/functions.php");
include("../navigation/header.php");

session_start();

?>

<!DOCTYPE html>
<html>

<head>
    <?php
        include("../../home/navigation/navbar.php");
    ?>
</head>

<body>
    <div class="content">
       

        <div class="container">
            <br>
            <br>
            <h4 class="text-center">Connexion Invité</h4>
            <br>
            <br>
            <form id="myForm" action="guestManagementCheckForm_guest.php" method="post" onsubmit="return checkForm(this);">
                <div class="row d-flex justify-content-center">
                    <div class="col-12 col-md-8 col-lg-6 col-xl-10">
                        <div class="card shadow-2-strong css-login">
                            <div class="card-body p-5">
                                <div class='row'>
                                    <div class="col">
                                        <label for="pw1" class="form-label">Nom/Prénom : </label>
                                        <input class="form-control" type="text" id="nom" name="nom" required>
                                    </div>
                                </div>
                                <div class='row'>
                                    <div class="col">
                                        <label for="pw1" class="form-label">Entreprise  : </label>
                                        <input class="form-control" type="text" id="entreprise" name="entreprise" required>
                                    </div>
                                </div>
                                <div class='row'>
                                    <div class="col">
                                        <label for="pw1" class="form-label">Ville entreprise  : </label>
                                        <input class="form-control" type="text" id="villeEntreprise" name="villeEntreprise" required>
                                    </div>
                                </div>
                                <div class='row'>
                                    <div class="col">
                                        <label for="pw1" class="form-label">Email  : </label>
                                        <input type="email" id="email" class="form-control" name="email" required>
                                    </div>
                                </div>
                                <div class='row'>
                                    <div class="col">
                                        <label for="pw1" class="form-label">Téléphone  : </label>
                                        <input class="form-control" type="text" id="tel" name="tel">
                                    </div>
                                </div>
                                <br>
                                <div class='row'>
                                    <div class="switch d-flex flex-wrap">
                                        <div class="mb-3 flex-grow-1">
                                            <label for="tuteurUniversitaire" class="form-label">Est enseignant</label>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" name="est_enseignant" id="est_enseignant">
                                            </div>
                                        </div>
                                        <div class="mb-3 flex-grow-1">
                                            <label for="etudiant" class="form-label">Est professionnel</label>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" name="est_professionnel" id="est_professionnel">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <button class="btn me-md-3 bg" type="submit">Enregistrer</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</body>

</html>

<script src="../../../js/toastr.min.js"></script>
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
if (isset($_SESSION['success'])) {
	$doublon = $_SESSION['success'];

	switch ($doublon) {
		case 1:
			echo '<script>toastr.error("Cette personne exite déjà !");</script>';
			break;
		default:
			// rien
	}
	$_SESSION['success'] = 0;
}
?>