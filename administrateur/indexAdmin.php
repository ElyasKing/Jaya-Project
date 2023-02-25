<?php

?>

<div class="container-fluid space">
    <h2 class="center colored">Accueil</h2>
    <hr>
    <br>
    <br>
    <div class="panel" id="panel">
        <table id="admin" class="display" style="width:100%">
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

<script>
    $(document).ready(function(){
        $('#admin').DataTable({
            stateSave: true,
            autoWith: true,
            language: {
                "url": "//cdn.datatables.net/plug-ins/1.11.3/i18n/fr_fr.json"
            },
            columnDefs: [
                {
                    targets: [-1,-2],
                    orderable: false,
                    searchable: false
                }
            ],
            order: [
                [1, 'asc']
            ],
            dom: 'Blfrtip',
            buttons: [
                'excel'
            ]
        });
    });
</script>