<?php
session_start();
include("../application_config/db_class.php");
include("../application_config/get_connectUser.php");
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
                <h2 class="center colored">Mes notes</h2>
                <hr>
                <br>
                <br>
                <div class="panel" id="panel">
                    <table id="example" class="display" style="width:100%">
                        <thead>
                            <tr class="bg">
                                <th>col 1</th>
                                <th>col 2</th>
                                <th>col 3</th>
                                <th>col 4</th>
                                <th>col 5</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>col 1</td>
                                <td>col 2</td>
                                <td>col 3</td>
                                <td>col 4</td>
                                <td>col 5</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>




<script>
    $(document).ready(function() {
        $('#example').DataTable({
            stateSave: true,
            language: {
                url: "//cdn.datatables.net/plug-ins/1.13.2/i18n/fr-FR.json"
            },
            order: [
                [3, 'desc']
            ],
            dom: 'Blfrtip',
            buttons: ['excel'],

        });
    });
</script>
</body>
</html>