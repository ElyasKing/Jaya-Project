<?php
session_start();
include "../application_config/db_class.php";
include 'header.php';
include 'navbar.php';
$conn = Database::connect();
$annee = date('Y');
?>


<link rel="stylesheet" type="text/less" href="../css/generate_qr.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/less.js/4.1.1/less.min.js"></script>

<?php

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


<div id="container">

    <div id="title">
    <h3>Liste des QR Codes invités</h3>
    </div>

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