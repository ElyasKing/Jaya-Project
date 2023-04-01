<?php
session_start();

// inclusion du fichier de configuration de la base de données
include "../application_config/db_class.php";

// inclusion des fichiers header et navbar
include 'header.php';
include 'navbar.php';

// création de la connexion à la base de données
$conn = Database::connect();

// récupération de l'année en cours
$annee = date('Y');

// récupération de l'id du tuteur
$userId = $_SESSION['user'];

?>

<!DOCTYPE html>
<html>

<head>
	<title>Suivi étudiant</title>
	<link rel="stylesheet" href="../css/MDB/css/datatables.min.css">
</head>

<body>

	<?php
	$query = 'SELECT utilisateur.ID_Utilisateur, utilisateur.nom_Utilisateur, utilisateur.Mail_Utilisateur, utilisateur.Promo_Utilisateur, utilisateur.HuitClos_Utilisateur, 
	invite.Entreprise_Invite, invite.Ville_Invite, invite.Nom_Invite, invite.Mail_Invite 
	FROM utilisateur 
	LEFT JOIN est_apprenti ON utilisateur.ID_Utilisateur = est_apprenti.ID_Utilisateur 
	LEFT JOIN invite ON est_apprenti.ID_Invite = invite.ID_Invite 
	JOIN habilitations ON utilisateur.ID_Utilisateur = habilitations.ID_Utilisateur 
	JOIN etudiant_tuteur ON utilisateur.ID_Utilisateur = etudiant_tuteur.Id_etudiant
	WHERE habilitations.Etudiant_Habilitations LIKE "oui" AND utilisateur.Annee_utilisateur LIKE "%' . $annee . '%" AND etudiant_tuteur.ID_Tuteur = '.$userId["id_Utilisateur"];
	
	$result = $conn->query($query);
	$etudiants = $result->fetchAll();

	?>

	<div class="container-fluid">
		<table id='dtVisuData' class="table table-hover table-borderless mt-5 table-striped table-responsive">
			<thead class="table-primary">
				<th>Etudiant</th>
				<th>Email étudiant</th>
				<th>Promo</th>
				<th>Entreprise</th>
				<th>Ville</th>
				<th>Maitre d'apprentissage</th>
				<th>Email MA</th>
				<th>Tuteur</th>
				<th>Email Tuteur</th>
				<th>Huit clos</th>
				<th></th>
			</thead>
			<tbody>
				<?php
				foreach ($etudiants as $etudiant) {
					$userId = $etudiant['ID_Utilisateur'];
					$queryTuteur = "SELECT utilisateur.nom_Utilisateur, utilisateur.Mail_Utilisateur FROM utilisateur 
			JOIN etudiant_tuteur ON utilisateur.ID_Utilisateur = etudiant_tuteur.ID_Tuteur
			WHERE etudiant_tuteur.Id_etudiant  LIKE  $userId ";
					$resultat = $conn->query($queryTuteur);
					$tuteur = $resultat->fetch();

					$queryMA = "SELECT invite.Nom_Invite, invite.Mail_Invite FROM invite 
			 JOIN est_apprenti ON invite.ID_Invite = est_apprenti.ID_Invite
			WHERE est_apprenti.Id_Utilisateur  LIKE  $userId ";
					$resultatMA = $conn->query($queryMA);
					$mas = $resultatMA->fetchAll();

					if ($tuteur == NULL) {
						$tuteur = ["nom_Utilisateur" => "", "Mail_Utilisateur" => ""];
					}
				?>
					<tr>
						<td><?php echo $etudiant['nom_Utilisateur']; ?></td>
						<td><?php echo $etudiant['Mail_Utilisateur']; ?></td>
						<td><?php echo $etudiant['Promo_Utilisateur']; ?></td>
						<td><?php echo $etudiant['Entreprise_Invite']; ?></td>
						<td><?php echo $etudiant['Ville_Invite']; ?></td>
						<td>
							<?php
							foreach ($mas as $ma) {
								echo $ma['Nom_Invite'];
							}
							?>
						</td>
						<td>
							<?php
							foreach ($mas as $ma) {
								echo $ma['Mail_Invite'];
							}
							?>
						</td>
						<td><?php echo $tuteur['nom_Utilisateur']; ?></td>
						<td><?php echo $tuteur['Mail_Utilisateur']; ?></td>
						<td><?php echo $etudiant['HuitClos_Utilisateur']; ?></td>
						<td>
							<a href="formUpdateEtudiant.php?id=<?php echo $etudiant['ID_Utilisateur'] ?>"><i class="bi bi-pencil-fill"></i></a>
						</td>

					</tr>
				<?php
				}
				?>
			</tbody>
		</table>
	</div>
	<script src="../css/MDB/js/datatables.min.js"></script>
	<script src="../css/MDB/js/app.js"></script>
</body>