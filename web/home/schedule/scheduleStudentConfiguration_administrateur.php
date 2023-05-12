<?php
include("../../../application_config/db_class.php");
include("../../../fonctions/functions.php");
session_start();

if (!isConnectedUser()) {
    $_SESSION['success'] = 2;
    header("Location: login.php");
}
?>

<!DOCTYPE html>
<html>

<head>
    <?php
    include("../navigation/header.php");
    $db = Database::connect();

    $query = "SELECT ID_Planning, Nom_Planning, DateSession_Planning, HeureDebutSession_Planning FROM planning WHERE ID_Planning ='".$_GET['planning']."'";
    $statement = $db->query($query);
    $scheduleName = $statement->fetch();

    $query = "SELECT Nom_Utilisateur, ID_planning FROM utilisateur WHERE ID_Utilisateur ='".$_GET['id']."'";
    $statement = $db->query($query);
    $studentName = $statement->fetch();

    $query = 'SELECT Description_param FROM parametres WHERE Nom_param = "Durée d\'une session de soutenances"';
    $statement = $db->query($query);
    $time = $statement->fetch();

    $query = "SELECT ID_Planning, Nom_Planning FROM planning";
    $statement = $db->query($query);
    ?>
</head>

<body>
    <div class="content">
            <?php
            include('../navigation/navbar.php');
            ?>
            <div class="container">
                <br>
                <br>
                <h4 class="text-center">Modifier la session d'un étudiant</h4>
                <br>
                <br>
                <form action="scheduleCheckStudentConfiguration_administrateur.php" method="post">
                    <div class="row d-flex justify-content-center">
                        <div class="col-12 col-md-8 col-lg-6 col-xl-10">
                            <div class="card shadow-2-strong css-login">
                                <div class="card-body p-5">
                                    <div class='row'>
                                        <div class="col">
                                            <input type="hidden" class="form-control" name="id_student" value="<?= $_GET['id'] ?>">
                                            <p class="form-label">Etudiant : <?= $studentName['Nom_Utilisateur'] ?></p>
                                        </div>
                                        <div class="col">
                                            <p class="form-label">Date de la session de soutenance :
                                                <?php
                                                    if ($scheduleName['DateSession_Planning'] != null) {
                                                        $date = DateTime::createFromFormat('Y-m-d', $scheduleName['DateSession_Planning']);
                                                        $formatted_date = $date->format('d/m/Y');
                                                        echo $formatted_date ;
                                                    } else {
                                                        echo "Aucune date n'a été définie.";
                                                    } 
                                                ?>
                                            </p>
                                        </div>
                                        <div class="col">
                                            <p class="form-label">Horaires de la session de soutenance :
                                                <?php
                                                    if ($scheduleName['HeureDebutSession_Planning'] != null) {
                                                        $f1 = str_replace(":", ".", $scheduleName['HeureDebutSession_Planning']);
                                                        $sessionStartTime = (float)$f1;
                                                        $sessionStartTime = substr($sessionStartTime, 0, 5);
                                                        $num_rounded = round($sessionStartTime, 2);
                                                        $sessionStartTime2 = number_format($num_rounded, 2, 'H', '.');
                    
                                                        $f2 = str_replace(":", ".", $time[0]);
                                                        $sessionEndTime = (float)$f2;
                                                        $sessionTimes =  $sessionStartTime + $sessionEndTime;
                                                        $num_rounded = round($sessionTimes, 2);
                                                        $sessionTimes = number_format($num_rounded, 2, 'H', '.');
                    
                                                        echo $sessionStartTime2 . "-" . $sessionTimes;
                                                    } else {
                                                        echo "Aucun horaire n'a été défini.";
                                                    }
                                                ?>
                                            </p>
                                        </div>
                                    </div>
                                    <div class='row'>
                                        <div class="col">
                                            <label for="session" class="form-label">Session de soutenance :</label>
                                            <?php
                                            echo "<select id='planningSelector' name='session' class='form-select' >";
                                            while ($row = $statement->fetch()) {
                                                echo "<option value='" . $row['ID_Planning'] . "'";
                                                if($studentName['ID_planning'] == $row['ID_Planning']){
                                                    echo "selected >"; 
                                                }else{
                                                    echo ">";
                                                }
                                                echo $row['Nom_Planning'] . "</option>";
                                            }
                                            echo "</select>";
                                            ?>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="text-center">
                                        <button class="btn me-md-3 bg" type="submit">Modifier</button>
                                        <a type="button" href="schedule_administrateur.php" class="btn me-md-3 bg">Retour</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
</body>

</html>