<?php $active_profile = $_SESSION['active_profile']; ?>
<nav class="navbar navbar-dark navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
      <a class="navbar-brand" href="index.php"><img src="../images/JAYA-LOGO2.png" alt="logo_jaya" width="180"></a>
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <?php if ($active_profile == "ADMINISTRATEUR") { ?>
          <li class="nav-item">
            <a class="nav-link active" href="index.php">Accueil</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" href="accountManager_administrateur.php">Comptes</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" href="studentOralManagement_administrateur.php">Soutenances</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" href="schedule_administrateur.php">Planning</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" href="studentMonitoring_users.php">Suivi recap</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" href="applicationSettings.php">Paramètres</a>
          </li>
      </ul>
      <ul class="navbar-nav me-0 mb-2 mb-lg-0">
        <li class="nav-item">
          <?php
          echo "<a class='nav-link active' href='#'><img id='notification-icon' src='../images/notification-icon2.svg' alt='logo_jaya'></a>";
          echo "<a class='nav-link active' href='logout.php'><img id='logout-icon' src='../images/logout-icon2.svg' alt='logout-icon'></a>";
          ?>
        </li>
      </ul>
    <?php } ?>
    <?php if ($active_profile == "RESPONSABLE UE") { ?>
      <li class="nav-item">
        <a class="nav-link active" href="index.php">Accueil</a>
      </li>
      <li class="nav-item">
        <a class="nav-link active" href="accountManager_users.php">Compte</a>
      </li>
      <li class="nav-item">
        <a class="nav-link active" href="schedule_responsableUE.php">Planning</a>
      </li>
      <li class="nav-item">
        <a class="nav-link active" href="studentMonitoring_users.php">Suivi recap</a>
      </li>
      </ul>
      <ul class="navbar-nav me-0 mb-2 mb-lg-0">
        <li class="nav-item">
          <?php
          echo "<a class='nav-link active' href='#'><img id='notification-icon' src='../images/notification-icon2.svg' alt='logo_jaya'></a>";
          echo "<a class='nav-link active' href='logout.php'><img id='logout-icon' src='../images/logout-icon2.svg' alt='logout-icon'></a>";
          ?>
        </li>
      </ul>
    <?php } ?>
    <?php if ($active_profile == "SCOLARITE") { ?>
      <li class="nav-item">
        <a class="nav-link active" href="index.php">Accueil</a>
      </li>
      <li class="nav-item">
        <a class="nav-link active" href="accountManager_users.php">Compte</a>
      </li>
      <li class="nav-item">
        <a class="nav-link active" href="schedule_scolarite.php">Planning</a>
      </li>
      <li class="nav-item">
        <a class="nav-link active" href="guestManagement_scolarite.php">Invit&eacute;s</a>
      </li>
      </ul>
      <ul class="navbar-nav me-0 mb-2 mb-lg-0">
        <li class="nav-item">
          <?php
          echo "<a class='nav-link active' href='#'><img id='notification-icon' src='../images/notification-icon2.svg' alt='logo_jaya'></a>";
          echo "<a class='nav-link active' href='logout.php'><img id='logout-icon' src='../images/logout-icon2.svg' alt='logout-icon'></a>";
          ?>
        </li>
      </ul>
    <?php } ?>
    <?php if ($active_profile == "TUTEUR UNIVERSITAIRE") { ?>
      <li class="nav-item">
        <a class="nav-link active" href="index.php">Accueil</a>
      </li>
      <li class="nav-item">
        <a class="nav-link active" href="accountManager_users.php">Compte</a>
      </li>
      <li class="nav-item">
        <a class="nav-link active" href="studentOralManagement_tuteurUniversitaire.php">Soutenances</a>
      </li>
      <li class="nav-item">
        <a class="nav-link active" href="schedule_tuteurUniversitaire.php">Planning</a>
      </li>
      <li class="nav-item">
        <a class="nav-link active" href="studentMonitoring_tuteurUniversitaire.php">Suivi recap</a>
      </li>
      </ul>
      <ul class="navbar-nav me-0 mb-2 mb-lg-0">
        <li class="nav-item">
          <?php
          echo "<a class='nav-link active' href='#'><img id='notification-icon' src='../images/notification-icon2.svg' alt='logo_jaya'></a>";
          echo "<a class='nav-link active' href='logout.php'><img id='logout-icon' src='../images/logout-icon2.svg' alt='logout-icon'></a>";
          ?>
        </li>
      </ul>
    <?php } ?>
    <?php if ($active_profile == "ETUDIANT") { ?>
      <li class="nav-item">
        <a class="nav-link active" href="index.php">Accueil</a>
      </li>
      <li class="nav-item">
        <a class="nav-link active" href="accountManager_users.php">Compte</a>
      </li>
      </ul>
      <ul class="navbar-nav me-0 mb-2 mb-lg-0">
        <li class="nav-item">
          <?php
          echo "<a class='nav-link active' href='#'><img id='notification-icon' src='../images/notification-icon2.svg' alt='logo_jaya'></a>";
          echo "<a class='nav-link active' href='logout.php'><img id='logout-icon' src='../images/logout-icon2.svg' alt='logout-icon'></a>";
          ?>
        </li>
      </ul>
    <?php } ?>
    <?php if ($active_profile == "INVITE" || $active_profile == "ENSEIGNANT INVITE" || $active_profile == "PROFESSIONNEL INVITE") { ?>
      </ul>
      <ul class="navbar-nav me-0 mb-2 mb-lg-0">
        <li class="nav-item">
          <?php
          echo "<a class='nav-link active' href='logout.php'><img id='logout-icon' src='../images/logout-icon2.svg' alt='logout-icon'></a>";
          ?>
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
          if (($active_profile != "INVITE" || $active_profile != "ENSEIGNANT INVITE" || $active_profile != "PROFESSIONNEL INVITE") && ($_SESSION['change_profile_access'] > 1)) {
            echo "<tr><td><font size=-1><a class='nav-link active'>" . $active_profile . "</a></font></td></tr>";
          } elseif (($active_profile != "INVITE" || $active_profile != "ENSEIGNANT INVITE" || $active_profile != "PROFESSIONNEL INVITE") && ($_SESSION['change_profile_access'] <= 1)) {
            echo "<tr><td><font size=-1><a class='nav-link active'>" . $active_profile . "</a></font></td></tr>";
          } elseif ($active_profile == "INVITE" || $active_profile == "ENSEIGNANT INVITE" || $active_profile == "PROFESSIONNEL INVITE") {
            echo "<tr><td><font size=-1><a class='nav-link active'>" . $active_profile . "</a></font></td></tr>";
          }
          echo "</table>";
          echo "</div>";
        } else {
          echo "<div class='nav-link active'>";
          echo "<table>";
          echo "<tr><td><strong>" . $_SESSION['user_name'] . "</td></tr>";
          if (($active_profile != "INVITE" || $active_profile != "ENSEIGNANT INVITE" || $active_profile != "PROFESSIONNEL INVITE") && ($_SESSION['change_profile_access'] > 1)) {
            echo "<tr><td><font size=-1><a class='nav-link active' href='changeProfile.php'>" . $active_profile . "</a></font></td></tr>";
          } elseif (($active_profile != "INVITE" || $active_profile != "ENSEIGNANT INVITE" || $active_profile != "PROFESSIONNEL INVITE") && ($_SESSION['change_profile_access'] <= 1)) {
            echo "<tr><td><font size=-1><a class='nav-link active'>" . $active_profile . "</a></font></td></tr>";
          } elseif ($active_profile == "INVITE" || $active_profile == "ENSEIGNANT INVITE" || $active_profile == "PROFESSIONNEL INVITE") {
            echo "<tr><td><font size=-1><a class='nav-link active'>" . $active_profile . "</a></font></td></tr>";
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
<br>