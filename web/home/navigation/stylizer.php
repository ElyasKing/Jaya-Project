<?php
// Définition de la variable $base_url
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? 'https://' : 'http://';
$base_url = $protocol . $_SERVER['HTTP_HOST'] . '/Jaya/web';
$base_url_style = $protocol . $_SERVER['HTTP_HOST'] . '/Jaya/';

// Importation des styles
//echo "Style importé avec succès!";
?>
<!-- CSS PERSO -->
<link rel="stylesheet" href="<?php echo $base_url_style; ?>css/style.css">
<link rel="stylesheet" href="<?php echo $base_url_style; ?>css/toastr.min.css">
