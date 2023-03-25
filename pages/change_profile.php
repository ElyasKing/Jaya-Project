<?php
session_start();
?>

<!DOCTYPE html>
<html>

<head>
    <?php
    include("./header.php");
    ?>
</head>

<body>
    <div class="content">
        <div class="bar">
            <span class="sphere"></span>
        </div>
        <div id="content">
            <?php
            include("./navbar.php");
            ?>

            <div class="container-fluid space">
                <h2 class="center colored">Vos habilitations</h2>
                <hr>
                <br>
                <br>
                <div class="panel" id="panel">
                    <?php
                    $active_profile = $_SESSION['active_profile'];

                    /*Definir le profil actif le plus élvevé, ordre:
        * 1 - Administrateur
        * 2 - Responsable UE
        * 3 - Scolarite
        * 4 - Tuteur universitaire
        * 5 - Etudiant
        */
        if($_SESSION["user_is_admin"] == "oui"){
            $_SESSION['active_profile'] = "ADMINISTRATEUR";
        }
        elseif($_SESSION["user_is_admin"] == "non" && $_SESSION["user_is_respUE"] == "oui"){
            $_SESSION['active_profile'] = "RESPONSABLE UE";
        }
        elseif($_SESSION["user_is_admin"] == "non" && $_SESSION["user_is_respUE"] == "non" && 
            $_SESSION["user_is_scola"] == "oui"){
            $_SESSION['active_profile'] = "SCOLARITE";
        }
        elseif($_SESSION["user_is_admin"] == "non" && $_SESSION["user_is_respUE"] == "non" && 
            $_SESSION["user_is_scola"] == "non" && $_SESSION["user_is_tuteurU"] == "oui"){
            $_SESSION['active_profile'] = "TUTEUR UNIVERSITAIRE";
        }
        elseif($_SESSION["user_is_admin"] == "non" && $_SESSION["user_is_respUE"] == "non" && 
            $_SESSION["user_is_scola"] == "non" && $_SESSION["user_is_tuteurU"] == "non" &&
            $_SESSION["user_is_student"] == "oui"){
            $_SESSION['active_profile'] = "ETUDIANT";
        }

                    if ($active_profile == "ADMINISTRATEUR") {
                        if ($_SESSION["user_is_respUE"] == "oui") {
                    ?>
                            <form id="changeProfile1" action="index.php" method="post">
                                <input type="hidden" name="changeProfile" value="RESPONSABLE UE">
                            </form>
                            <a href="#" onclick='document.getElementById("changeProfile1").submit()'>
                                <p>RESPONSABLE UE</p>
                            </a>
                    <?php
                        }
                        if ($_SESSION["user_is_scola"] == "oui") {
                    ?>
                            <form id="changeProfile2" action="index.php" method="post">
                                <input type="hidden" name="changeProfile" value="SCOLARITE">
                            </form>
                            <a href="#" onclick='document.getElementById("changeProfile2").submit()'>
                                <p>SCOLARITE</p>
                            </a>
                    <?php
                        }
                        if ($_SESSION["user_is_tuteurU"] == "oui") {
                    ?>
                            <form id="changeProfile3" action="index.php" method="post">
                                <input type="hidden" name="changeProfile" value="TUTEUR UNIVERSITAIRE">
                            </form>
                            <a href="#" onclick='document.getElementById("changeProfile3").submit()'>
                                <p>TUTEUR UNIVERSITAIRE</p>
                            </a>
                    <?php
                        }
                        if ($_SESSION["user_is_student"] == "oui") {
                    ?>
                            <form id="changeProfile4" action="index.php" method="post">
                                <input type="hidden" name="changeProfile" value="ETUDIANT">
                            </form>
                            <a href="#" onclick='document.getElementById("changeProfile4").submit()'>
                                <p>ETUDIANT</p>
                            </a>
                    <?php
                        }
                    }

                    if ($active_profile == "SCOLARITE") {
                        if ($_SESSION["user_is_admin"] == "oui") {
                    ?>
                            <form id="changeProfile1" action="index.php" method="post">
                                <input type="hidden" name="changeProfile" value="ADMINISTRATEUR">
                            </form>
                            <a href="#" onclick='document.getElementById("changeProfile1").submit()'>
                                <p>ADMINISTRATEUR</p>
                            </a>
                    <?php
                        }
                        if ($_SESSION["user_is_respUE"] == "oui") {
                            ?>
                                    <form id="changeProfile2" action="index.php" method="post">
                                        <input type="hidden" name="changeProfile" value="RESPONSABLE UE">
                                    </form>
                                    <a href="#" onclick='document.getElementById("changeProfile2").submit()'>
                                        <p>RESPONSABLE UE</p>
                                    </a>
                            <?php
                                }
                        if ($_SESSION["user_is_tuteurU"] == "oui") {
                    ?>
                            <form id="changeProfile3" action="index.php" method="post">
                                <input type="hidden" name="changeProfile" value="TUTEUR UNIVERSITAIRE">
                            </form>
                            <a href="#" onclick='document.getElementById("changeProfile3").submit()'>
                                <p>TUTEUR UNIVERSITAIRE</p>
                            </a>
                    <?php
                        }
                        if ($_SESSION["user_is_student"] == "oui") {
                    ?>
                            <form id="changeProfile4" action="index.php" method="post">
                                <input type="hidden" name="changeProfile" value="ETUDIANT">
                            </form>
                            <a href="#" onclick='document.getElementById("changeProfile4").submit()'>
                                <p>ETUDIANT</p>
                            </a>
                    <?php
                        }
                    }
                    
                    if ($active_profile == "RESPONSABLE UE") {
                        if ($_SESSION["user_is_admin"] == "oui") {
                    ?>
                            <form id="changeProfile1" action="index.php" method="post">
                                <input type="hidden" name="changeProfile" value="ADMINISTRATEUR">
                            </form>
                            <a href="#" onclick='document.getElementById("changeProfile1").submit()'>
                                <p>ADMINISTRATEUR</p>
                            </a>
                    <?php
                        }
                        if ($_SESSION["user_is_scola"] == "oui") {
                    ?>
                            <form id="changeProfile2" action="index.php" method="post">
                                <input type="hidden" name="changeProfile" value="SCOLARITE">
                            </form>
                            <a href="#" onclick='document.getElementById("changeProfile2").submit()'>
                                <p>SCOLARITE</p>
                            </a>
                    <?php
                        }
                        if ($_SESSION["user_is_tuteurU"] == "oui") {
                    ?>
                            <form id="changeProfile3" action="index.php" method="post">
                                <input type="hidden" name="changeProfile" value="TUTEUR UNIVERSITAIRE">
                            </form>
                            <a href="#" onclick='document.getElementById("changeProfile3").submit()'>
                                <p>TUTEUR UNIVERSITAIRE</p>
                            </a>
                    <?php
                        }
                        if ($_SESSION["user_is_student"] == "oui") {
                    ?>
                            <form id="changeProfile4" action="index.php" method="post">
                                <input type="hidden" name="changeProfile" value="ETUDIANT">
                            </form>
                            <a href="#" onclick='document.getElementById("changeProfile4").submit()'>
                                <p>ETUDIANT</p>
                            </a>
                    <?php
                        }
                    }
                    
                    if ($active_profile == "TUTEUR UNIVERSITAIRE") {
                        if ($_SESSION["user_is_admin"] == "oui") {
                    ?>
                            <form id="changeProfile1" action="index.php" method="post">
                                <input type="hidden" name="changeProfile" value="ADMINISTRATEUR">
                            </form>
                            <a href="#" onclick='document.getElementById("changeProfile1").submit()'>
                                <p>ADMINISTRATEUR</p>
                            </a>
                    <?php
                        }
                        if ($_SESSION["user_is_respUE"] == "oui") {
                            ?>
                                    <form id="changeProfile2" action="index.php" method="post">
                                        <input type="hidden" name="changeProfile" value="RESPONSABLE UE">
                                    </form>
                                    <a href="#" onclick='document.getElementById("changeProfile2").submit()'>
                                        <p>RESPONSABLE UE</p>
                                    </a>
                            <?php
                                }
                        if ($_SESSION["user_is_scola"] == "oui") {
                    ?>
                            <form id="changeProfile3" action="index.php" method="post">
                                <input type="hidden" name="changeProfile" value="SCOLARITE">
                            </form>
                            <a href="#" onclick='document.getElementById("changeProfile3").submit()'>
                                <p>SCOLARITE</p>
                            </a>
                    <?php
                        }
                        if ($_SESSION["user_is_student"] == "oui") {
                    ?>
                            <form id="changeProfile4" action="index.php" method="post">
                                <input type="hidden" name="changeProfile" value="ETUDIANT">
                            </form>
                            <a href="#" onclick='document.getElementById("changeProfile4").submit()'>
                                <p>ETUDIANT</p>
                            </a>
                    <?php
                        }
                    }
                    
                    if ($active_profile == "ETUDIANT") {
                        if ($_SESSION["user_is_admin"] == "oui") {
                    ?>
                            <form id="changeProfile1" action="index.php" method="post">
                                <input type="hidden" name="changeProfile" value="ADMINISTRATEUR">
                            </form>
                            <a href="#" onclick='document.getElementById("changeProfile1").submit()'>
                                <p>ADMINISTRATEUR</p>
                            </a>
                    <?php
                        }
                        if ($_SESSION["user_is_respUE"] == "oui") {
                            ?>
                                    <form id="changeProfile2" action="index.php" method="post">
                                        <input type="hidden" name="changeProfile" value="RESPONSABLE UE">
                                    </form>
                                    <a href="#" onclick='document.getElementById("changeProfile2").submit()'>
                                        <p>RESPONSABLE UE</p>
                                    </a>
                            <?php
                                }
                        if ($_SESSION["user_is_scola"] == "oui") {
                    ?>
                            <form id="changeProfile3" action="index.php" method="post">
                                <input type="hidden" name="changeProfile" value="SCOLARITE">
                            </form>
                            <a href="#" onclick='document.getElementById("changeProfile3").submit()'>
                                <p>SCOLARITE</p>
                            </a>
                    <?php
                        }
                        if ($_SESSION["user_is_tuteurU"] == "oui") {
                            ?>
                                    <form id="changeProfile4" action="index.php" method="post">
                                        <input type="hidden" name="changeProfile" value="TUTEUR UNIVERSITAIRE">
                                    </form>
                                    <a href="#" onclick='document.getElementById("changeProfile4").submit()'>
                                        <p>TUTEUR UNIVERSITAIRE</p>
                                    </a>
                            <?php
                                }
                    }
                    ?>


                </div>
            </div>
        </div>
    </div>
</body>

</html>