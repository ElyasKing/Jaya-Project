<?php
require_once 'stylizer.php';
?>

<nav class="navbar navbar-dark navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo01"
            aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
      <a class="navbar-brand" href="<?php echo $base_url; ?>/index.php"><img src="<?php echo $base_url_style; ?>images/JAYA-LOGO2.png" alt="logo_jaya"
                                                      width="180"></a>

      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <?php if (isset($_SESSION['nom']) || isset($_SESSION['entreprise'])) { ?>
      </ul>
      <ul class="navbar-nav me-0 mb-2 mb-lg-0">
        <li class="nav-item">
          <a class='nav-link active' href='#'><img id='notification-icon' src='<?php echo $base_url_style; ?>images/notification-icon2.svg' alt='logo_jaya'></a>
          <a class='nav-link active' href='<?php echo $base_url; ?>/home/logout.php'><img id='logout-icon' src='<?php echo $base_url_style; ?>images/logout-icon2.svg' alt='logout-icon'></a>
        </li>
      </ul>
      <?php Exit;} ?>
        <?php if ($_SESSION['active_profile'] == "ADMINISTRATEUR") { ?>
        <li class="nav-item">
          <a class="nav-link active" href="<?php echo $base_url; ?>/index.php">Accueil</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="<?php echo $base_url; ?>/home/accountManager/accountManager_administrateur.php">Comptes</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="<?php echo $base_url; ?>/home/soutenances/administrateur.php">Soutenances</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="<?php echo $base_url; ?>/home/schedule/schedule_administrateur.php">Planning</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="#">Suivi recap</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="<?php echo $base_url; ?>/applicationSettings.php">Paramètres</a>
        </li>
      </ul>
      <ul class="navbar-nav me-0 mb-2 mb-lg-0">
        <li class="nav-item">
          <a class='nav-link active' href='#'><img id='notification-icon' src='<?php echo $base_url_style; ?>images/notification-icon2.svg' alt='logo_jaya'></a>
          <a class='nav-link active' href='<?php echo $base_url; ?>/home/logout.php'><img id='logout-icon' src='<?php echo $base_url_style; ?>images/logout-icon2.svg' alt='logout-icon'></a>
        </li>
      </ul>
      <?php } ?>
      <?php if ($_SESSION['active_profile'] == "RESPONSABLE UE") { ?>
        <li class="nav-item">
          <a class="nav-link active" href="<?php echo $base_url; ?>/index.php">Accueil</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="<?php echo $base_url; ?>/home/accountManager/accountManager_users.php">Compte</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="<?php echo $base_url; ?>/home/schedule/schedule_responsableUE.php">Planning</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="#">Suivi recap</a>
        </li>
        </ul>
        <ul class="navbar-nav me-0 mb-2 mb-lg-0">
          <li class="nav-item">
            <a class='nav-link active' href='#'><img id='notification-icon' src="<?php echo $base_url_style; ?>images/notification-icon2.svg" alt='logo_jaya'></a>
            <a class='nav-link active' href='<?php echo $base_url; ?>/home/logout.php'><img id='logout-icon' src='<?php echo $base_url_style; ?>images/logout-icon2.svg' alt='logout-icon'></a>
          </li>
        </ul>
      <?php } ?>

      <?php if ($_SESSION['active_profile'] == "SCOLARITE") { ?>
        <li class="nav-item">
          <a class="nav-link active" href="<?php echo $base_url; ?>/index.php">Accueil</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="<?php echo $base_url; ?>/home/accountManager/accountManager_users.php">Compte</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="<?php echo $base_url; ?>/home/schedule/schedule_scolarite.php">Planning</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="<?php echo $base_url; ?>/home/guest/guestManagement_scolarite.php">Invités</a>
        </li>
        </ul>
        <ul class="navbar-nav me-0 mb-2 mb-lg-0">
          <li class="nav-item">
            <a class='nav-link active' href='#'><img id='notification-icon' src="<?php echo $base_url_style; ?>images/notification-icon2.svg" alt='logo_jaya'></a>
            <a class='nav-link active' href='<?php echo $base_url; ?>/home/logout.php'><img id='logout-icon' src='<?php echo $base_url_style; ?>images/logout-icon2.svg' alt='logout-icon'></a>
          </li>
        </ul>
      <?php } ?>

      <?php if ($_SESSION['active_profile'] == "TUTEUR UNIVERSITAIRE") { ?>
      <li class="nav-item">
        <a class="nav-link active" href="<?php echo $base_url; ?>/index.php">Accueil</a>
      </li>
      <li class="nav-item">
        <a class="nav-link active" href="<?php echo $base_url; ?>/home/accountManager/accountManager_users.php">Compte</a>
      </li>
      <li class="nav-item">
        <a class="nav-link active" href="<?php echo $base_url; ?>/home/soutenance/administrateur.php">Soutenances</a>
      </li>
      <li class="nav-item">
        <a class="nav-link active" href="<?php echo $base_url; ?>schedule/schedule_tuteurUniversitaire.php">Planning</a>
      </li>
        <li class="nav-item">
          <a class="nav-link active" href="#">Suivi recap</a>
        </li>
        </ul>
        <ul class="navbar-nav me-0 mb-2 mb-lg-0">
          <li class="nav-item">
            <a class='nav-link active' href='#'><img id='notification-icon' src='<?php echo $base_url_style; ?>images/notification-icon2.svg' alt='logo_jaya'></a>
            <a class='nav-link active' href='<?php echo $base_url; ?>/home/logout.php'><img id='logout-icon' src='<?php echo $base_url_style; ?>images/logout-icon2.svg' alt='logout-icon'></a>
          </li>
        </ul>
      <?php } ?>
      <?php if ($_SESSION['active_profile'] == "ETUDIANT") { ?>
        <li class="nav-item">
          <a class="nav-link active" href="<?php echo $base_url; ?>/index.php">Accueil</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="<?php echo $base_url; ?>/home/accountManager/accountManager_users.php">Compte</a>
        </li>
        </ul>
        <ul class="navbar-nav me-0 mb-2 mb-lg-0">
          <li class="nav-item">
            <a class='nav-link active' href='#'><img id='notification-icon' src='<?php echo $base_url_style; ?>images/notification-icon2.svg' alt='logo_jaya'></a>
            <a class='nav-link active' href='<?php echo $base_url; ?>/home/logout.php'><img id='logout-icon' src='<?php echo $base_url_style; ?>images/logout-icon2.svg' alt='logout-icon'></a>
          </li>
        </ul>
      <?php } ?>
      <?php if ($_SESSION['active_profile'] == "INVITE" || $_SESSION['active_profile'] == "ENSEIGNANT INVITE" || $_SESSION['active_profile'] == "PROFESSIONNEL INVITE") { ?>
        </ul>
        <ul class="navbar-nav me-0 mb-2 mb-lg-0">
          <li class="nav-item">
            <a class='nav-link active' href='#'><img id='notification-icon' src="<?php echo $base_url_style; ?>images/notification-icon2.svg" alt='logo_jaya'></a>
          </li>
        </ul>
      <?php } ?>
      <ul class="navbar-nav me-0 mb-2 mb-lg-0">
        <li class="nav-item">
<?php
$chaine = $_SERVER['REQUEST_URI'];
$souchaine = "changeProfile.php";
if (strpos($chaine, $souchaine)) {
  echo "<div class='nav-link active'>";
  echo "<table>";
  echo "<tr><td><strong>" . $_SESSION['user_name'] . "</td></tr>";
  if (($_SESSION['active_profile'] != "INVITE" || $_SESSION['active_profile'] != "ENSEIGNANT INVITE" || $_SESSION['active_profile'] != "PROFESSIONNEL INVITE") && ($_SESSION['change_profile_access'] > 1)) {
    echo "<tr><td><font size=-1><a class='nav-link active'>" . $_SESSION['active_profile'] . "</a></font></td></tr>";
  } elseif (($_SESSION['active_profile'] != "INVITE" || $_SESSION['active_profile'] != "ENSEIGNANT INVITE" || $_SESSION['active_profile'] != "PROFESSIONNEL INVITE") && ($_SESSION['change_profile_access'] <= 1)) {
    echo "<tr><td><font size=-1><a class='nav-link active'>" . $_SESSION['active_profile'] . "</a></font></td></tr>";
  } elseif ($_SESSION['active_profile'] == "INVITE" || $_SESSION['active_profile'] == "ENSEIGNANT INVITE" || $_SESSION['active_profile'] == "PROFESSIONNEL INVITE") {
    echo "<tr><td><font size=-1><a class='nav-link active'>" . $_SESSION['active_profile'] . "</a></font></td></tr>";
  }
  echo "</table>";
  echo "</div>";
} else {
  echo "<div class='nav-link active'>";
  echo "<table>";
  echo "<tr><td><strong>" . $_SESSION['user_name'] . "</td></tr>";
  if (($_SESSION['active_profile'] != "INVITE" || $_SESSION['active_profile'] != "ENSEIGNANT INVITE" || $_SESSION['active_profile'] != "PROFESSIONNEL INVITE") && ($_SESSION['change_profile_access'] > 1)) {
    echo "<tr><td><font size=-1><a class='nav-link active' href=$base_url/changeProfile.php>" . $_SESSION['active_profile'] . "</a></font></td></tr>";
  } elseif (($_SESSION['active_profile'] != "INVITE" || $_SESSION['active_profile'] != "ENSEIGNANT INVITE" || $_SESSION['active_profile'] != "PROFESSIONNEL INVITE") && ($_SESSION['change_profile_access'] <= 1)) {
    echo "<tr><td><font size=-1><a class='nav-link active'>" . $_SESSION['active_profile'] . "</a></font></td></tr>";
  } elseif ($_SESSION['active_profile'] == "INVITE" || $_SESSION['active_profile'] == "ENSEIGNANT INVITE" || $_SESSION['active_profile'] == "PROFESSIONNEL INVITE") {
    echo "<tr><td><font size=-1><a class='nav-link active'>" . $_SESSION['active_profile'] . "</a></font></td></tr>";
  }
  echo "</table>";
  echo "</div>";
}
?>
        </li>
      </ul>
    </div>
  </div>
</nav>