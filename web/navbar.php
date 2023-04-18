
<nav class="navbar navbar-dark navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
      <a class="navbar-brand" href="index.php"><img src="../images/JAYA-LOGO2.png" alt="logo_jaya" width="180"></a>
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <?php if($_SESSION['active_profile'] == "ADMINISTRATEUR"){ ?>
        <li class="nav-item">
          <a class="nav-link active" href="index.php">Accueil</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="habilitations.php">Comptes</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="#">Soutenances</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="#">Planning</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="suiviEtudiants.php">Suivi recap</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="parametreDynamique.php">Paramètres</a>
        </li>
      </ul>
      <ul class="navbar-nav me-0 mb-2 mb-lg-0">
        <li class="nav-item">
          <?php
            echo "<a class='nav-link active' href='index.php'><img id='notification-icon' src='../images/notification-icon2.svg' alt='logo_jaya'></a>";
            echo "<a class='nav-link active' href='logout.php'><img id='logout-icon' src='../images/logout-icon2.svg' alt='logout-icon'></a>";
          ?>
        </li>
      </ul>
        <?php } ?>
        <?php if($_SESSION['active_profile'] == "RESPONSABLE UE"){ ?>
        <li class="nav-item">
          <a class="nav-link active" href="index.php">Accueil</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="#">Comptes</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="#">Planning</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="#">Suivi recap</a>
        </li>
      </ul>
      <ul class="navbar-nav me-0 mb-2 mb-lg-0">
        <li class="nav-item">
          <?php
            echo "<a class='nav-link active' href='index.php'><img id='notification-icon' src='../images/notification-icon2.svg' alt='logo_jaya'></a>";
            echo "<a class='nav-link active' href='logout.php'><img id='logout-icon' src='../images/logout-icon2.svg' alt='logout-icon'></a>";
          ?>
        </li>
      </ul>
        <?php } ?>
        <?php if($_SESSION['active_profile'] == "SCOLARITE"){ ?>
        <li class="nav-item">
          <a class="nav-link active" href="index.php">Accueil</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="#">Comptes</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="planning_scolarite.php">Planning</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="#">Invit&eacute;s</a>
        </li>
      </ul>
      <ul class="navbar-nav me-0 mb-2 mb-lg-0">
        <li class="nav-item">
          <?php
            echo "<a class='nav-link active' href='index.php'><img id='notification-icon' src='../images/notification-icon2.svg' alt='logo_jaya'></a>";
            echo "<a class='nav-link active' href='logout.php'><img id='logout-icon' src='../images/logout-icon2.svg' alt='logout-icon'></a>";
          ?>
        </li>
      </ul>
        <?php } ?>
        <?php if($_SESSION['active_profile'] == "TUTEUR UNIVERSITAIRE"){ ?>
          <li class="nav-item">
          <a class="nav-link active" href="index.php">Accueil</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="#">Comptes</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="#">Soutenances</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="#">Planning</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="#">Suivi recap</a>
        </li>
        </ul>
      <ul class="navbar-nav me-0 mb-2 mb-lg-0">
        <li class="nav-item">
          <?php
            echo "<a class='nav-link active' href='index.php'><img id='notification-icon' src='../images/notification-icon2.svg' alt='logo_jaya'></a>";
            echo "<a class='nav-link active' href='logout.php'><img id='logout-icon' src='../images/logout-icon2.svg' alt='logout-icon'></a>";
          ?>
        </li>
      </ul>
        <?php } ?>
        <?php if($_SESSION['active_profile'] == "ETUDIANT"){ ?>
        <li class="nav-item">
          <a class="nav-link active" href="index.php">Accueil</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="#">Comptes</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="#">Planning</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="#">Invit&eacute;s</a>
        </li>
      </ul>
      <ul class="navbar-nav me-0 mb-2 mb-lg-0">
        <li class="nav-item">
          <?php
            echo "<a class='nav-link active' href='index.php'><img id='notification-icon' src='../images/notification-icon2.svg' alt='logo_jaya'></a>";
            echo "<a class='nav-link active' href='logout.php'><img id='logout-icon' src='../images/logout-icon2.svg' alt='logout-icon'></a>";
          ?>
        </li>
      </ul>
        <?php } ?>
        <?php if($_SESSION['active_profile'] == "INVITE" || $_SESSION['active_profile'] == "ENSEIGNANT INVITE" || $_SESSION['active_profile'] == "PROFESSIONNEL INVITE"){ ?>
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
            echo "<div class='nav-link active'>";
            echo "<table>";
            echo "<tr><td><strong>".$_SESSION['user_name']."</td></tr>";
            if(($_SESSION['active_profile'] != "INVITE" || $_SESSION['active_profile'] != "ENSEIGNANT INVITE" || $_SESSION['active_profile'] != "PROFESSIONNEL INVITE") && ($_SESSION['change_profile_access'] > 1)){
              echo "<tr><td><font size=-1><a class='nav-link active' href='changeProfile.php'>".$_SESSION['active_profile']."</a></font></td></tr>";
            }
            elseif(($_SESSION['active_profile'] != "INVITE" || $_SESSION['active_profile'] != "ENSEIGNANT INVITE" || $_SESSION['active_profile'] != "PROFESSIONNEL INVITE") && ($_SESSION['change_profile_access'] <= 1)){
              echo "<tr><td><font size=-1><a class='nav-link active'>".$_SESSION['active_profile']."</a></font></td></tr>";
            }
            elseif($_SESSION['active_profile'] == "INVITE" || $_SESSION['active_profile'] == "ENSEIGNANT INVITE" || $_SESSION['active_profile'] == "PROFESSIONNEL INVITE"){
              echo "<tr><td><font size=-1><a class='nav-link active'>".$_SESSION['active_profile']."</a></font></td></tr>";
            }

            echo "</table>";
            echo "</div>";
          ?>
        </li>
      </ul>
    </div>
  </div>
</nav>