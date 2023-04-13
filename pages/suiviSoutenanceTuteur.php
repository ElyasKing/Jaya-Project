<?php
session_start();
include "../application_config/db_class.php";
include 'header.php';
include 'navbar.php';


$conn = Database::connect();

$query = 'SELECT notes_soutenance.NoteFinale_NS, notes_soutenance.Commentaire_NS, etudiant.Nom_Utilisateur as nom_etudiant, invite.Nom_Utilisateur as nom_invite,  
    tuteur.Nom_Utilisateur as nom_tuteur
    FROM notes_soutenance
JOIN utilisateur etudiant ON notes_soutenance.ID_UtilisateurEvalue = etudiant.ID_Utilisateur 
LEFT JOIN utilisateur invite ON notes_soutenance.ID_InviteEvaluateur = invite.ID_Utilisateur
LEFT JOIN utilisateur tuteur ON notes_soutenance.ID_UtilisateurEvaluateur =  tuteur.ID_Utilisateur
WHERE invite.ID_Utilisateur = ' . $_SESSION['user_id'] . ' OR tuteur.ID_Utilisateur = ' . $_SESSION['user_id'] . '
';
$result = $conn->query($query);
$notes = $result->fetchAll();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notes de soutenances</title>
</head>

<body>
    <div class="container">
        <div class="text-center mt-3">
            <h3 class="">Soutenances</h3>
            <p>Vous disposerez d'un délai de 30 minutes à la fin de la session de soutenance pour valider définitivement vos notes en cliquant sur le bouton "Je valide mes notes".
                Sans cette confirmation, les notes saisies ne seront pas prises en compte
            </p>
        </div>
        <table id='dtVisuData' class="table mt-5  table-responsive">
            <thead class="table-primary">
                <th>Etudiant</th>
                <th>Note saisie</th>
                <th>Appréciation</th>
                <th></th>
            </thead>
            <tbody>
                <?php
                foreach ($notes as $note) {
                ?>
                    <td>
                        <?php
                        echo $note['nom_etudiant'];
                        ?>
                    </td>
                    <td>
                        <?php
                        echo $note['NoteFinale_NS'];
                        ?>
                    </td>
                    <td>
                        <?php
                        echo $note['Commentaire_NS'];
                        ?>
                    </td>
                <?php
                }
                ?>
            </tbody>
        </table>
        <div class="text-right">
            <button class="btn btn-primary">
                Saisir une note de soutenance
            </button>
        </div>
    </div>
</body>

</html>