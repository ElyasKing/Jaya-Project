<?php
session_start();
include "../application_config/db_class.php";
include 'header.php';
//include 'navbar.php';
$conn = Database::connect();
$annee = date('Y');

ini_set("xdebug.var_display_max_children", '-1');
ini_set("xdebug.var_display_max_data", '-1');
ini_set("xdebug.var_display_max_depth", '-1');

$idUser = $_SESSION['user_id'];

$query = "SELECT utilisateur.ID_Utilisateur, utilisateur.nom_Utilisateur, utilisateur.Mail_Utilisateur, utilisateur.Promo_Utilisateur, utilisateur.HuitClos_Utilisateur,
invite.Entreprise_Invite, invite.Ville_Invite, invite.Nom_Invite, invite.Mail_Invite, notes_soutenance.NoteFinale_NS, notes_suivi.Poster_NF, notes_suivi.Remarque_NF, 
notes_suivi.Rapport_NF, notes_suivi.Appreciation_NF, notes_suivi.noteFinale_NF, notes_suivi.Orthographe_NF FROM utilisateur 
LEFT JOIN est_apprenti ON utilisateur.ID_Utilisateur = est_apprenti.ID_Utilisateur 
LEFT JOIN invite ON est_apprenti.ID_Invite = invite.ID_Invite 
LEFT JOIN notes_soutenance ON utilisateur.ID_Utilisateur = notes_soutenance.ID_UtilisateurEvalue
LEFT JOIN notes_suivi ON utilisateur.ID_Utilisateur = notes_suivi.ID_Utilisateur 
WHERE utilisateur.ID_Utilisateur = $idUser";
$resultat = $conn->query($query);
$etudiant = $resultat->fetch();

$queryTuteur = "SELECT utilisateur.nom_Utilisateur, utilisateur.Mail_Utilisateur FROM utilisateur 
			JOIN etudiant_tuteur ON utilisateur.ID_Utilisateur = etudiant_tuteur.ID_Tuteur
			WHERE etudiant_tuteur.Id_etudiant  LIKE  $idUser ";
$resultat = $conn->query($queryTuteur);
$tuteur = $resultat->fetch();

$queryMA = "SELECT invite.Nom_Invite, invite.Mail_Invite FROM invite 
			 JOIN est_apprenti ON invite.ID_Invite = est_apprenti.ID_Invite
			WHERE est_apprenti.Id_Utilisateur  LIKE  $idUser ";
$resultatMA = $conn->query($queryMA);
$mas = $resultatMA->fetchAll();

$queryNote = "SELECT notes_soutenance.NoteFinale_NS, notes_soutenance.ID_InviteEvaluateur, notes_soutenance.ID_UtilisateurEvaluateur FROM notes_soutenance
                    INNER JOIN utilisateur ON notes_soutenance.ID_UtilisateurEvalue = utilisateur.ID_Utilisateur
                    WHERE utilisateur.ID_Utilisateur LIKE $idUser ";
$resultat = $conn->query($queryNote);
$notes = $resultat->fetchAll();


$queryCoeffProf = 'SELECT description_param FROM parametres
WHERE NbPoint_param IS NULL AND Nom_Param = "Coefficient - note professionel"';
$resultatprof = $conn->query($queryCoeffProf);
$coeffProf = $resultatprof->fetch();

$queryCoeffEns = 'SELECT description_param FROM parametres
WHERE NbPoint_param IS NULL AND Nom_Param = "Coefficient - note enseignant"';
$resultatens = $conn->query($queryCoeffEns);
$coeffEns = $resultatens->fetch();

$queryCoeffOral = 'SELECT description_param FROM parametres
WHERE NbPoint_param IS NULL AND Nom_Param = "Coefficient - note orale"';
$resultatoral = $conn->query($queryCoeffOral);
$coeffOral = $resultatoral->fetch();

$queryCoeffSuivi = 'SELECT description_param FROM parametres
WHERE NbPoint_param IS NULL AND Nom_Param = "Coefficient - note de suivi"';
$resultatsuivi = $conn->query($queryCoeffSuivi);
$coeffSuivi = $resultatsuivi->fetch();

$noteOral = 0;
$coeff = 0;
$noteOralFinale = 0;

if ($notes != NULL) {

    foreach ($notes as $note) {

        if ($note['ID_InviteEvaluateur'] == null) {
            $noteOral = ($noteOral + $note['NoteFinale_NS']) * $coeffEns['description_param'];
            $coeff = ($coeff + 1) * $coeffEns['description_param'];
        } else {
            $noteOral = ($noteOral + $note['NoteFinale_NS']) * $coeffProf['description_param'];
            $coeff = ($coeff + 1) * $coeffProf['description_param'];
        }
    }


    $noteOral = $noteOral / $coeff;
    $noteOralFinale = $noteOral - $etudiant['Orthographe_NF'];
    $noteOralFinale = round($noteOralFinale, 2);
}

$notePP = ($coeffOral['description_param'] * $noteOralFinale + $coeffSuivi['description_param'] *  $etudiant['noteFinale_NF']) / ($coeffOral['description_param'] + $coeffSuivi['description_param']);
$notePP = round($notePP, 2);

?>

<div class="container-fluid space">
    <h2 class="center colored">Accueil</h2>

    <h5 class="mt-2">Informations personnelles</h5>
    <div class="panel mt-1" id="panel">
        <table id="example" class="table-responsive " style="width:100%">
            <thead>
                <tr class="bg">
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
                </tr>
            </thead>
            <tbody>
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

                </tr>
            </tbody>
        </table>

        <h5 class="mt-3">Informations évaluation</h5>
        <div class="col-auto mt-2">
            <table class="table table-responsive">
                <thead>
                    <tr class="bg">
                        <th>Poster rendu</th>
                        <th>Rapport rendu</th>
                        <th>Note de suivi</th>
                        <th>Note d'oral</th>
                        <th>Note projet professionnel</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?php echo $etudiant['Poster_NF']; ?></td>
                        <td><?php echo $etudiant['Rapport_NF']; ?></td>
                        <td><?php echo $etudiant['noteFinale_NF']; ?></td>
                        <td><?php echo $noteOralFinale; ?></td>
                        <td><?php echo $notePP; ?></td>
                    </tr>
                </tbody>
            </table>

            <h5 class="mt-3">Informations soutenance</h5>
        </div>
    </div>
</div>