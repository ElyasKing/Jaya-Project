<?php
include "../application_config/db_class.php";
include "../application_config/get_connectUser.php";
include 'header.php';
include 'navbar.php';

$conn = Database::connect();

$query =
    'SELECT utilisateur.ID_Utilisateur, utilisateur.nom_Utilisateur, utilisateur.Mail_Utilisateur, utilisateur.Promo_Utilisateur, 
    notes_suivi.Poster_NF, notes_suivi.Remarque_NF, notes_suivi.Rapport_NF, notes_suivi.Appreciation_NF, notes_suivi.noteFinale_NF, notes_suivi.orthographe FROM utilisateur
    LEFT JOIN notes_suivi ON utilisateur.ID_Utilisateur = notes_suivi.ID_Utilisateur 
    LEFT JOIN notes_soutenance ON utilisateur.ID_Utilisateur = notes_soutenance.ID_Utilisateur
    JOIN habilitations ON utilisateur.ID_Utilisateur = habilitations.ID_Utilisateur 
    WHERE habilitations.Etudiant_Habilitations LIKE "oui"';
$result = $conn->query($query);
$etudiants = $result->fetchAll();

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <title>Suivi des rendus</title>
    <link rel="stylesheet" href="../css/MDB/css/datatables.min.css">
    <link rel="stylesheet" href="../../css/style.css">
</head>

<body>
    <div class="container">
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
                foreach ($etudiants as $etudiant) {

                    $id = $etudiant['ID_Utilisateur'];

                    $queryNote = "SELECT notes_suivi.NoteFinale_NF FROM notes_suivi
                    INNER JOIN utilisateur ON notes_suivi.ID_Utilisateur = utilisateur.ID_Utilisateur
                    WHERE utilisateur.ID_Utilisateur LIKE $id ";
                    $resultat = $conn->query($queryNote);
                    $notes = $resultat->fetchAll();

                    $noteOral = 0;

                    foreach ($notes as $note) {
                        $noteOral = $noteOral + $note['NoteFinale_NF'];
                    }


                    $noteOralFinale = $noteOral - $etudiant['orthographe'];
                    $noteSuivi =  $etudiant['noteFinale_NF'];
                    $notePP = (2 * $noteOralFinale + $noteSuivi) / 3;
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
                    <td><?php echo '-' . $etudiant['orthographe']; ?></td>
                    <td><?php echo $noteSuivi ?></td>
                    <td><?php echo $noteOral ?></td>
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