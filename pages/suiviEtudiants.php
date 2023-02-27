<?php
include "../application_config/db_class.php";
$conn = Database::connect();
?>


<!DOCTYPE html>
<html>
<head>
	<title>Suivi étudiant</title>
	
<body>

<?php
$query =
'SELECT utilisateur.ID_Utilisateur, utilisateur.nom_Utilisateur, utilisateur.Mail_Utilisateur, utilisateur.Promo_Utilisateur, utilisateur.HuitClos_Utilisateur,
invite.Entreprise_Invite, invite.Ville_Invite, invite.Nom_Invite, invite.Mail_Invite FROM utilisateur 
 JOIN est_apprenti ON utilisateur.ID_Utilisateur = est_apprenti.ID_Utilisateur 
 JOIN invite ON est_apprenti.ID_Invite = invite.ID_Invite 
 JOIN habilitations ON utilisateur.ID_Utilisateur = habilitations.ID_Utilisateur 
WHERE habilitations.Etudiant_Habilitations LIKE "oui"

';
$result = $conn->query($query);
$etudiants = $result->fetchAll();

?>

<table>
	<thead>
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
	</thead>
	<tbody>
		<?php 
		foreach($etudiants as $etudiant)
		{ 
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

			if($tuteur == NULL)
			{
				$tuteur= ["nom_Utilisateur"=>"", "Mail_Utilisateur" => ""];
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
				foreach($mas as $ma)
				{
					echo $ma['Nom_Invite'];
				}
				?>
			</td>
			<td>
				<?php 
				foreach($mas as $ma)
				{
					echo $ma['Mail_Invite'];
				}
				?>
			</td>				
			<td><?php echo $etudiant['Mail_Invite']; ?></td>
			<td><?php echo $tuteur['nom_Utilisateur']; ?></td>
			<td><?php echo $tuteur['Mail_Utilisateur']; ?></td>
			<td><?php echo $etudiant['HuitClos_Utilisateur']; ?></td>
			<td>
				<span class="glyphicon glyphicon-pencil"></span>
			</td>
		</tr>
		<?php
		}
		?>
	</tbody>
</table>
	
</body>
