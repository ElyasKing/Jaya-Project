<?php
include("../application_config/db_class.php");
include("../fonctions/functions.php");
session_start();

if(!isConnectedUser()){
    $_SESSION['success'] = 2;
    header("Location: login.php");
}
?>

<!DOCTYPE html>
<html>

<head>
    <?php
    include("header.php");
    ?>
    <link rel="stylesheet" type="text/less" href="../css/generate_qr.scss">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/less.js/4.1.1/less.min.js"></script>
</head>

<body>
    <div class="content">
        <div class="bar">
            <span class="sphere"></span>
        </div>
        <div id="content">
            <?php
            include("navbar.php");

            // Résoudre le chemin absolu du fichier qrlib.php
            $qr_lib_path = '../lib/phpqrcode/qrlib.php';

            // Inclure la bibliothèque PHP QR Code
            require_once $qr_lib_path;

            // Texte à encoder en QR code (URL dans le futur)
            $text = "ratio florian!";

            // Chemin du fichier où le QR code sera enregistré
            $filename = '../images/QR_IMG/qrcode.png';

            // Taille et niveau de correction d'erreur du QR code
            $size = 5;
            $level = 'H';

            // Générer le QR code
            QRcode::png($text, $filename, $level, $size);
            ?>

            <div class="container">
                <br>
                <br>
                <h4 class="text-center">Liste des QR Codes invités</h4>
                <div id="qr-container">
                    <?php // Afficher le QR code
                    echo '<img src="' . $filename . '" />';
                    echo '<img src="' . $filename . '" />';
                    echo '<img src="' . $filename . '" />';
                    echo '<img src="' . $filename . '" />';
                    echo '<img src="' . $filename . '" />';
                    echo '<img src="' . $filename . '" />';
                    echo '<img src="' . $filename . '" />';
                    echo '<img src="' . $filename . '" />';
                    echo '<img src="' . $filename . '" />';
                    ?>
                </div>

                <div id="action-button">
                    <button onclick="window.print()">Imprimer</button>
                    <button>Retour</button>
                </div>
            </div>

        </div>
    </div>
</body>

</html>