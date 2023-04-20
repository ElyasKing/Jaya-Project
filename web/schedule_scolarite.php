<?php
include("../application_config/db_class.php");
session_start();
?>

<!DOCTYPE html>
<html>

<head>
    <?php
    include("header.php");
    ?>
</head>

<body>
    <div class="content">
        <div class="bar">
            <span class="sphere"></span>
        </div>
        <div id="content">
            <?php
            include("navbar.php");
            ?>

            <div class="container-fluid space">
                <h2 class="center colored">Planning</h2>
                <hr>
                <br>
                <br>
                <?php
                $db = Database::connect();
                
                $query = "SELECT 
                u1.ID_Utilisateur AS ID_Etudiant,
                u1.Nom_Utilisateur AS Nom_Etudiant,
                u1.Mail_Utilisateur AS Mail_Etudiant,
                u1.Annee_Utilisateur,
                u1.Promo_Utilisateur,
                u1.HuitClos_Utilisateur,
                u2.Nom_Utilisateur AS Nom_Tuteur_Universitaire,
                u2.Mail_Utilisateur AS Mail_Tuteur_Universitaire,
                i.Entreprise_Invite,
                i.Ville_Invite,
                i.Nom_Invite,
                i.Mail_Invite,
                u1.ID_Planning,
                p.Nom_Planning,
                TRIM(BOTH ', ' FROM CONCAT_WS(', ',
                    CASE WHEN SUM(h.Admin_Habilitations = 'oui') > 0 THEN 'Admin' ELSE NULL END,
                    CASE WHEN SUM(h.ResponsableUE_Habilitations = 'oui') > 0 THEN 'ResponsableUE' ELSE NULL END,
                    CASE WHEN SUM(h.Scolarite_Habilitations = 'oui') > 0 THEN 'Scolarite' ELSE NULL END,
                    CASE WHEN SUM(h.Etudiant_Habilitations = 'oui') > 0 THEN 'Etudiant' ELSE NULL END,
                    CASE WHEN SUM(h.TuteurUniversitaire_Habilitations = 'oui') > 0 THEN 'TuteurUniversitaire' ELSE NULL END
                )) AS Roles
            FROM
                Utilisateur u1
                    LEFT JOIN etudiant_tuteur et ON u1.id_Utilisateur = et.id_Etudiant
                    LEFT JOIN Utilisateur u2 ON et.id_Tuteur = u2.id_Utilisateur
                    LEFT JOIN est_apprenti ea ON u1.id_utilisateur = ea.id_utilisateur
                    LEFT JOIN invite i ON ea.id_invite = i.id_invite
                    LEFT JOIN habilitations h ON u1.ID_Utilisateur = h.ID_Utilisateur
                    LEFT JOIN planning p ON u1.ID_Planning = p.ID_Planning
            WHERE u1.ID_Planning IS NOT NULL
            GROUP BY u1.ID_Utilisateur";
                
                $statement = $db->query($query);
                $studentsList = $statement->fetchAll();

        
                $query = "SELECT ID_Planning, Nom_Planning FROM planning";
                $statement = $db->query($query);
                ?>

                <div class="panel" id="panel">
                    <div class="col-6 col-md-4 mx-auto">
                    <?php
                        echo "<select id='planningSelector' class='form-select' >";
                        while($row = $statement->fetch()){
                            echo "<option value='".$row['ID_Planning']."' >".$row['Nom_Planning']."</option>";
                            $tmp = $row['ID_Planning'];
                        }
                        echo "</select>";
                    ?>
                    </div>
                    <?php
                    $query = "SELECT * FROM
                    (SELECT ID_Planning, Nom_Planning FROM planning ) as lastID
                     ORDER BY ID_Planning DESC LIMIT 1;";
                    $statement = $db->query($query);
                    $lastIdPlanning = $statement->fetch();

                    // var_dump($lastIdPlanning);die;

                    ?>
                    <table id="planning" class="display" style="width:100%">
                        <thead>
                            <tr class="bg">
                                <th hidden></th>
                                <th>Etudiant</th>
                                <th>Entreprise</th>
                                <th>Ville</th>
                                <th>Promo</th>
                                <th>Huit clos</th>
                                <th>Date de la session de soutenance</th>
                                <th>Horaires de la session de soutenance</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            foreach($studentsList as $user) { 
                                if($user["ID_Planning"] == $lastIdPlanning[0]){
                                        echo "<tr>
                                        <td class='text-center' style='display:none;'>". $user['ID_Planning'] ."</td>
                                        <td class='text-center'>". $user['Nom_Etudiant'] ."</td>
                                        <td class='text-center'>". $user['Entreprise_Invite'] ."</td>
                                        <td class='text-center'>". $user['Ville_Invite'] ."</td>
                                        <td class='text-center'>". $user['Promo_Utilisateur'] ."</td>
                                        <td class='text-center'>". $user['HuitClos_Utilisateur'] ."</td>
                                        <td></td>
                                        <td></td>
                                        <td><a href='#id=". $user['ID_Etudiant'] ."'><i class='bi bi-pencil-fill'></i></a></td>
                                        </tr>";
                                    }else{
                                        echo "<tr>
                                        <td class='text-center' style='display:none;'>". $user['ID_Planning'] ."</td>
                                        <td class='text-center'>". $user['Nom_Etudiant'] ."</td>
                                        <td class='text-center'>". $user['Entreprise_Invite'] ."</td>
                                        <td class='text-center'>". $user['Ville_Invite'] ."</td>
                                        <td class='text-center'>". $user['Promo_Utilisateur'] ."</td>
                                        <td class='text-center'>". $user['HuitClos_Utilisateur'] ."</td>
                                        <td></td>
                                        <td></td>
                                        <td><a href='#id=". $user['ID_Etudiant'] ."'><i class='bi bi-pencil-fill'></i></a></td>
                                        </tr>";
                                    }
                                } 
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <form id="generate-planning-form" action="scheduleGeneration_scolarite.php" method="post">
                </form>
                <a href="#" onclick='if(confirm("Souhaitez-vous vraiment g&eacute;n&eacute;rer ou ajouter des plannings de soutenances ?")){document.getElementById("generate-planning-form").submit();}else{return false;};'><button type="button" id="btn-generer" class="btn me-md-2 bg">G&eacute;n&eacute;rer</button></a>
                
                <button type="button" id="btn-valider-scolarite" class="btn me-md-3 bg">Valider</button>
            </div>
        </div>
    </div>

<script>
    window.addEventListener("load", function() {
        // Obtenez l'élément select
        const selectElement = document.getElementById("planningSelector");

        // Sélectionnez le deuxième élément de la liste déroulante
        selectElement.selectedIndex = 2;

        // Obtenez l'option sélectionnée
        let selectedOption = selectElement.options[selectElement.selectedIndex];

        // Cliquez sur l'option sélectionnée
        selectedOption.click();
    });

    $(document).ready(function () {
        $('#planning').DataTable({
            stateSave: true,
            language : {
                url : "//cdn.datatables.net/plug-ins/1.13.2/i18n/fr-FR.json"
            },
            order: [[3, 'desc']],
            dom: 'Blfrtip',
            buttons: ['excel'],

        });

        // Ajouter un écouteur d'événements pour détecter les changements dans le sélecteur
        planningSelector.addEventListener("change", function() {
            
            // Récupérer la valeur sélectionnée
            let selectedPlanning = planningSelector.value;

            // Récupérer toutes les lignes du tableau
            let rows = document.getElementById("planning").getElementsByTagName("tr");

            // Parcourir les lignes du tableau et cacher celles qui ne correspondent pas au planning sélectionné
            for (let i = 1; i < rows.length; i++) {
                let row = rows[i];
                let planningId = row.cells[0].textContent;
                if (planningId === selectedPlanning) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            }
            
            //location.reload();
        });

    });
</script>

</body>
</html>
