<?php
session_start();
include "../application_config/db_class.php";
//include "../application_config/get_connectUser.php";
include 'header.php';
include 'home/navigation/navbar.php';

$conn = Database::connect();


if (isset($_POST['submit'])) {
    $filtreAnnee = $_POST['annee'];

    $query =
        'SELECT DISTINCT utilisateur.ID_Utilisateur, utilisateur.nom_Utilisateur, utilisateur.Mail_Utilisateur, utilisateur.Promo_Utilisateur, 
notes_suivi.Poster_NF, notes_suivi.Remarque_NF, notes_suivi.Rapport_NF, notes_suivi.Appreciation_NF, notes_suivi.noteFinale_NF, notes_suivi.Orthographe_NF FROM utilisateur
LEFT JOIN notes_suivi ON utilisateur.ID_Utilisateur = notes_suivi.ID_Utilisateur 
LEFT JOIN notes_soutenance ON utilisateur.ID_Utilisateur = notes_soutenance.ID_UtilisateurEvalue
LEFT JOIN habilitations ON utilisateur.ID_Utilisateur = habilitations.ID_Utilisateur 
WHERE habilitations.Etudiant_Habilitations LIKE "oui" AND utilisateur.Annee_Utilisateur LIKE "' . $filtreAnnee . '"';
    $result = $conn->query($query);
    $etudiants = $result->fetchAll();
} else {
    $query =
        'SELECT DISTINCT utilisateur.ID_Utilisateur, utilisateur.nom_Utilisateur, utilisateur.Mail_Utilisateur, utilisateur.Promo_Utilisateur, 
    notes_suivi.Poster_NF, notes_suivi.Remarque_NF, notes_suivi.Rapport_NF, notes_suivi.Appreciation_NF, notes_suivi.noteFinale_NF, notes_suivi.Orthographe_NF FROM utilisateur
    LEFT JOIN notes_suivi ON utilisateur.ID_Utilisateur = notes_suivi.ID_Utilisateur 
    LEFT JOIN notes_soutenance ON utilisateur.ID_Utilisateur = notes_soutenance.ID_UtilisateurEvalue
    LEFT JOIN habilitations ON utilisateur.ID_Utilisateur = habilitations.ID_Utilisateur 
    WHERE habilitations.Etudiant_Habilitations LIKE "oui"';
    $result = $conn->query($query);
    $etudiants = $result->fetchAll();
}


$queryAnnee = 'SELECT DISTINCT Annee_Utilisateur FROM utilisateur
left JOIN habilitations ON utilisateur.ID_Utilisateur = habilitations.ID_Utilisateur 
WHERE habilitations.Etudiant_Habilitations LIKE "oui"
';
$resultat = $conn->query($queryAnnee);
$annees = $resultat->fetchAll();



?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <title>Suivi des rendus</title>
    <link rel="stylesheet" href="../css/MDB/css/datatables.min.css">
    <link rel="stylesheet" href="../../css/style.css">
</head>

<body>
    <div class="container mt-3">
        <form action="<?= $_SERVER['PHP_SELF']; ?>" method="post">
            <select name="annee">
                <?php
                foreach ($annees as $annee) {
                ?>
                    <option value="<?= $annee['Annee_Utilisateur'] ?>"><?= $annee['Annee_Utilisateur'] ?></option>
                <?php
                }
                ?>
            </select>
            <button type="submit" class="btn btn-primary" name="submit">Envoyer</button>

        </form>
        <table id='dtVisuData' class="table table-borderless mt-5  table-responsive">
            <thead class="table-primary">
                <th>Etudiant</th>
                <th>Promo</th>
                <th>Poster</th>
                <th>Remarque</th>
                <th>Rapport</th>
                <th>Appréciation</th>
                <th>Orthographe</th>
                <th>Note de suivi</th>
                <th>Note d'oral</th>
                <th>Note de PP</th>
                <th></th>
            </thead>
            <tbody>
                <?php

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


                foreach ($etudiants as $etudiant) {

                    $id = $etudiant['ID_Utilisateur'];

                    $queryNote = "SELECT notes_soutenance.NoteFinale_NS, notes_soutenance.ID_InviteEvaluateur, notes_soutenance.ID_UtilisateurEvaluateur FROM notes_soutenance
                    INNER JOIN utilisateur ON notes_soutenance.ID_UtilisateurEvalue = utilisateur.ID_Utilisateur
                    WHERE utilisateur.ID_Utilisateur LIKE $id ";
                    $resultat = $conn->query($queryNote);
                    $notes = $resultat->fetchAll();

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
                    $noteSuivi =  $etudiant['noteFinale_NF'];




                    $notePP = ($coeffOral['description_param'] * $noteOralFinale + $coeffSuivi['description_param'] *  $noteSuivi) / ($coeffOral['description_param'] + $coeffSuivi['description_param']);
                    $notePP = round($notePP, 2);

                    if ($etudiant['Poster_NF'] == 'non' || $etudiant['Rapport_NF'] == 'non') {
                        echo '<tr class="table-danger">';
                    } else {
                        echo '<tr>';
                    }

                ?>

                    <td><?php echo $etudiant['nom_Utilisateur']; ?></td>
                    <td><?php echo $etudiant['Promo_Utilisateur']; ?></td>
                    <td><?php echo $etudiant['Poster_NF']; ?></td>
                    <td><?php echo $etudiant['Remarque_NF']; ?></td>
                    <td><?php echo $etudiant['Rapport_NF']; ?></td>
                    <td><?php echo $etudiant['Appreciation_NF']; ?></td>
                    <td><?php echo '-' . $etudiant['Orthographe_NF']; ?></td>
                    <td><?php echo $noteSuivi ?></td>
                    <td><?php echo $noteOralFinale ?></td>
                    <td><?php echo $notePP ?></td>
                    <td>
                        <a href="formUpdateRendus.php?id=<?php echo $etudiant['ID_Utilisateur'] ?>">
                            <i class="bi bi-pencil-fill"></i></a>

                    </td>

                    </tr>

                <?php
                }
                ?>
            </tbody>
        </table>
    </div>
</body>

<script src="../css/MDB/js/datatables.min.js"></script>
<script src="../css/MDB/js/app.js"></script>

</html>