<?php
include("../fonctions/functions.php");
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
    include("./header.php");
    ?>
</head>

<body>
    <?php
    include("./navbar.php");
    ?>

    <div class="container-fluid">
        <h2 class="center colored">Vos habilitations</h2>
        <hr>
        <br>
        <br>
        <div class="row d-flex justify-content-center">
            <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                <div class="card shadow-2-strong css-login">
                    <div class="card-body p-5 text-center">
                        <p>Vous êtes actuellement connecté avec le profil : <strong><?= $_SESSION['active_profile'] ?></strong></p><br>
                        <p><strong>JAYA</strong> détecte un ou plusieurs profils disponibles.</p>
                        <p>Merci de sélectionner le profil à utiliser :</p>
                        <?php
                        $active_profile = $_SESSION['active_profile'];

                        /*Definir le profil actif le plus élvevé, ordre:
                    * 1 - Administrateur
                    * 2 - Responsable UE
                    * 3 - Scolarite
                    * 4 - Tuteur universitaire
                    * 5 - Etudiant
                    */
                        if (
                            $_SESSION["user_is_admin"] == "oui" & $_SESSION["user_is_respUE"] == "non" &&
                            $_SESSION["user_is_scola"] == "non" && $_SESSION["user_is_tuteurU"] == "non" &&
                            $_SESSION["user_is_student"] == "non"
                        ) {
                            $_SESSION['active_profile'] = "ADMINISTRATEUR";
                        } elseif ($_SESSION["user_is_admin"] == "non" && $_SESSION["user_is_respUE"] == "oui") {
                            $_SESSION['active_profile'] = "RESPONSABLE UE";
                        } elseif (
                            $_SESSION["user_is_admin"] == "non" && $_SESSION["user_is_respUE"] == "non" &&
                            $_SESSION["user_is_scola"] == "oui"
                        ) {
                            $_SESSION['active_profile'] = "SCOLARITE";
                        } elseif (
                            $_SESSION["user_is_admin"] == "non" && $_SESSION["user_is_respUE"] == "non" &&
                            $_SESSION["user_is_scola"] == "non" && $_SESSION["user_is_tuteurU"] == "oui"
                        ) {
                            $_SESSION['active_profile'] = "TUTEUR UNIVERSITAIRE";
                        } elseif (
                            $_SESSION["user_is_admin"] == "non" && $_SESSION["user_is_respUE"] == "non" &&
                            $_SESSION["user_is_scola"] == "non" && $_SESSION["user_is_tuteurU"] == "non" &&
                            $_SESSION["user_is_student"] == "oui"
                        ) {
                            $_SESSION['active_profile'] = "ETUDIANT";
                        }

                        if ($active_profile == "ADMINISTRATEUR") {
                            if ($_SESSION["user_is_respUE"] == "oui") {
                        ?>
                                <form id="changeProfile1" action="index.php" method="post">
                                    <input type="hidden" name="changeProfile" value="RESPONSABLE UE">
                                </form>
                                <a href="#" onclick='document.getElementById("changeProfile1").submit()'>
                                    <p class="noDecoration linkFormat">RESPONSABLE UE</p>
                                </a>
                            <?php
                            }
                            if ($_SESSION["user_is_scola"] == "oui") {
                            ?>
                                <form id="changeProfile2" action="index.php" method="post">
                                    <input type="hidden" name="changeProfile" value="SCOLARITE">
                                </form>
                                <a href="#" onclick='document.getElementById("changeProfile2").submit()'>
                                    <p class="noDecoration linkFormat">SCOLARITE</p>
                                </a>
                            <?php
                            }
                            if ($_SESSION["user_is_tuteurU"] == "oui") {
                            ?>
                                <form id="changeProfile3" action="index.php" method="post">
                                    <input type="hidden" name="changeProfile" value="TUTEUR UNIVERSITAIRE">
                                </form>
                                <a href="#" onclick='document.getElementById("changeProfile3").submit()'>
                                    <p class="noDecoration linkFormat">TUTEUR UNIVERSITAIRE</p>
                                </a>
                            <?php
                            }
                            if ($_SESSION["user_is_student"] == "oui") {
                            ?>
                                <form id="changeProfile4" action="index.php" method="post">
                                    <input type="hidden" name="changeProfile" value="ETUDIANT">
                                </form>
                                <a href="#" onclick='document.getElementById("changeProfile4").submit()'>
                                    <p class="noDecoration linkFormat">ETUDIANT</p>
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
                                    <p class="noDecoration linkFormat">ADMINISTRATEUR</p>
                                </a>
                            <?php
                            }
                            if ($_SESSION["user_is_respUE"] == "oui") {
                            ?>
                                <form id="changeProfile2" action="index.php" method="post">
                                    <input type="hidden" name="changeProfile" value="RESPONSABLE UE">
                                </form>
                                <a href="#" onclick='document.getElementById("changeProfile2").submit()'>
                                    <p class="noDecoration linkFormat">RESPONSABLE UE</p>
                                </a>
                            <?php
                            }
                            if ($_SESSION["user_is_tuteurU"] == "oui") {
                            ?>
                                <form id="changeProfile3" action="index.php" method="post">
                                    <input type="hidden" name="changeProfile" value="TUTEUR UNIVERSITAIRE">
                                </form>
                                <a href="#" onclick='document.getElementById("changeProfile3").submit()'>
                                    <p class="noDecoration linkFormat">TUTEUR UNIVERSITAIRE</p>
                                </a>
                            <?php
                            }
                            if ($_SESSION["user_is_student"] == "oui") {
                            ?>
                                <form id="changeProfile4" action="index.php" method="post">
                                    <input type="hidden" name="changeProfile" value="ETUDIANT">
                                </form>
                                <a href="#" onclick='document.getElementById("changeProfile4").submit()'>
                                    <p class="noDecoration linkFormat">ETUDIANT</p>
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
                                    <p class="noDecoration linkFormat">ADMINISTRATEUR</p>
                                </a>
                            <?php
                            }
                            if ($_SESSION["user_is_scola"] == "oui") {
                            ?>
                                <form id="changeProfile2" action="index.php" method="post">
                                    <input type="hidden" name="changeProfile" value="SCOLARITE">
                                </form>
                                <a href="#" onclick='document.getElementById("changeProfile2").submit()'>
                                    <p class="noDecoration linkFormat">SCOLARITE</p>
                                </a>
                            <?php
                            }
                            if ($_SESSION["user_is_tuteurU"] == "oui") {
                            ?>
                                <form id="changeProfile3" action="index.php" method="post">
                                    <input type="hidden" name="changeProfile" value="TUTEUR UNIVERSITAIRE">
                                </form>
                                <a href="#" onclick='document.getElementById("changeProfile3").submit()'>
                                    <p class="noDecoration linkFormat">TUTEUR UNIVERSITAIRE</p>
                                </a>
                            <?php
                            }
                            if ($_SESSION["user_is_student"] == "oui") {
                            ?>
                                <form id="changeProfile4" action="index.php" method="post">
                                    <input type="hidden" name="changeProfile" value="ETUDIANT">
                                </form>
                                <a href="#" onclick='document.getElementById("changeProfile4").submit()'>
                                    <p class="noDecoration linkFormat">ETUDIANT</p>
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
                                    <p class="noDecoration linkFormat">ADMINISTRATEUR</p>
                                </a>
                            <?php
                            }
                            if ($_SESSION["user_is_respUE"] == "oui") {
                            ?>
                                <form id="changeProfile2" action="index.php" method="post">
                                    <input type="hidden" name="changeProfile" value="RESPONSABLE UE">
                                </form>
                                <a href="#" onclick='document.getElementById("changeProfile2").submit()'>
                                    <p class="noDecoration linkFormat">RESPONSABLE UE</p>
                                </a>
                            <?php
                            }
                            if ($_SESSION["user_is_scola"] == "oui") {
                            ?>
                                <form id="changeProfile3" action="index.php" method="post">
                                    <input type="hidden" name="changeProfile" value="SCOLARITE">
                                </form>
                                <a href="#" onclick='document.getElementById("changeProfile3").submit()'>
                                    <p class="noDecoration linkFormat">SCOLARITE</p>
                                </a>
                            <?php
                            }
                            if ($_SESSION["user_is_student"] == "oui") {
                            ?>
                                <form id="changeProfile4" action="index.php" method="post">
                                    <input type="hidden" name="changeProfile" value="ETUDIANT">
                                </form>
                                <a href="#" onclick='document.getElementById("changeProfile4").submit()'>
                                    <p class="noDecoration linkFormat">ETUDIANT</p>
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
                                    <p class="noDecoration linkFormat">ADMINISTRATEUR</p>
                                </a>
                            <?php
                            }
                            if ($_SESSION["user_is_respUE"] == "oui") {
                            ?>
                                <form id="changeProfile2" action="index.php" method="post">
                                    <input type="hidden" name="changeProfile" value="RESPONSABLE UE">
                                </form>
                                <a href="#" onclick='document.getElementById("changeProfile2").submit()'>
                                    <p class="noDecoration linkFormat">RESPONSABLE UE</p>
                                </a>
                            <?php
                            }
                            if ($_SESSION["user_is_scola"] == "oui") {
                            ?>
                                <form id="changeProfile3" action="index.php" method="post">
                                    <input type="hidden" name="changeProfile" value="SCOLARITE">
                                </form>
                                <a href="#" onclick='document.getElementById("changeProfile3").submit()'>
                                    <p class="noDecoration linkFormat">SCOLARITE</p>
                                </a>
                            <?php
                            }
                            if ($_SESSION["user_is_tuteurU"] == "oui") {
                            ?>
                                <form id="changeProfile4" action="index.php" method="post">
                                    <input type="hidden" name="changeProfile" value="TUTEUR UNIVERSITAIRE">
                                </form>
                                <a href="#" onclick='document.getElementById("changeProfile4").submit()'>
                                    <p class="noDecoration linkFormat">TUTEUR UNIVERSITAIRE</p>
                                </a>
                        <?php
                            }
                        }
                        ?>

                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>