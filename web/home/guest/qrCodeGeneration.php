<?php
include("../../../application_config/db_class.php");
include("../../../fonctions/functions.php");
session_start();

if(!isConnectedUser()){
    $_SESSION['success'] = 2;
    header("Location: login.php");
}

$db = Database::connect();
// Récupérer les utilisateurs
$sql = "SELECT * FROM invite";
$statement = $db->query($sql);

?>

<!DOCTYPE html>
<html>

<head>
    <?php
    include("../navigation/header.php");
    ?>
    <link rel="stylesheet" type="text/less" href="../../../css/generate_qr.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/less.js/4.1.1/less.min.js"></script>
</head>

<body>
    <div class="content">
        <div class="bar">
            <span class="sphere"></span>
        </div>
        <div id="content">
            <?php
            include('../navigation/navbar.php');

            // Résoudre le chemin absolu du fichier qrlib.php
            //$qr_lib_path = '/lib/phpqrcode/qrlib.php';

            // Inclure la bibliothèque PHP QR Code
            require_once '../../../lib/phpqrcode/qrlib.php';

            // Texte à encoder en QR code (URL dans le futur)
            $text = "ratio florian!";

            // Chemin du fichier où le QR code sera enregistré
            $filename_invitedm = '../../../images/QR_IMG/qrcode_invitedm.png';
            $filename_tuteurU = '../../../images/QR_IMG/qrcode_tuteurU.png';

            // Taille et niveau de correction d'erreur du QR code
            $size = 3;
            $level = 'F';

            // Générer le QR code
             QRcode::png($text, $filename_invitedm, $level, $size);
             QRcode::png($text, $filename_tuteurU, $level, $size);
            ?>

            <h4 class="qr-title">Liste des QR Codes invités</h4>


            <div class="action-button">
                <button class="btn me-md-3 bg btn-qr-code" onclick="window.print()">Imprimer</button>
                <a href="./guestManagement_scolarite.php" class="btn me-md-3 bg btn-qr-code">Retour</a>
            </div>

            <div class="qr-container" style="justify-content: center">
                <?php // Afficher le QR code
                echo '<div class="qr-code-wrapper">';
                echo '<img src="' . $filename_invitedm . '" />';
                echo '<p class="qr-code-info qr-info"> Invité de dernière minute </p>';
                echo '</div>';

                echo '<div class="qr-code-wrapper">';
                echo '';
                echo '<p class="qr-code-info qr-info">  </p>';
                echo '</div>';

                echo '<div class="qr-code-wrapper">';
                echo '<img src="' . $filename_tuteurU . '" />';
                echo '<p class="qr-code-info qr-info"> Tuteurs universitaires </p>';
                echo '</div>';
                ?>
            </div>

            <hr>

            <div class="qr-container">
                <?php
                // Parcourir les utilisateurs et générer les QR codes
                while ($row = $statement->fetch()) {
                    $text = 'http://localhost/JAYA/JAYA/web/home/soutenances/tuteurU/tuteurUniversitaire.php?nom='.$row['Nom_Invite'].'&entreprise='.$row['Entreprise_Invite'];

                    // Générer le nom du fichier en fonction de l'ID de l'utilisateur
                    $filename = '../../../images/QR_IMG/qrcode_' . $row['Nom_Invite'] . '-' . $row['Entreprise_Invite'] . '.png';

                    // Générer le QR code
                    QRcode::png($text, $filename, $level, $size);

                    // Afficher le QR code et les informations de l'utilisateur
                    echo '<div class="qr-code-wrapper">';
                    echo '<img src="' . $filename . '" />';
                    echo '<p class="qr-code-info qr-info">' . $row['Nom_Invite'] . '<br> (' . $row['Entreprise_Invite'] . ')' . '</p>';
                    echo '</div>';
                }
                ?>
            </div>

        </div>
    </div>
</body>

</html>