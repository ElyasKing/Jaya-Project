<?php
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
                <div class="panel" id="panel">
                    <table id="planning" class="display" style="width:100%">
                        <thead>
                            <tr class="bg">
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
                            
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <form id="generate-planning-form" action="check_generatePlanning.php" method="post">
                </form>
                <a href="#" onclick='if(confirm("Souhaitez-vous vraiment g&eacute;n&eacute;rer ou ajouter des plannings de soutenances ?")){document.getElementById("generate-planning-form").submit();}else{return false;};'><button type="button" id="btn-generer" class="btn me-md-2 bg">G&eacute;n&eacute;rer</button></a>
                
                <button type="button" id="btn-valider-scolarite" class="btn me-md-3 bg">Valider</button>
            </div>
        </div>
    </div>

<script>
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
    });
</script>

</body>
</html>