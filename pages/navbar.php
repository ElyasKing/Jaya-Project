<nav class="navbar navbar-dark navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
      <a class="navbar-brand" href="index.php"><img src="../images/JAYA-LOGO2.png" alt="logo_jaya" width="180"></a>
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" href="index.php">Accueil</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="accountsManagerAdmin.php">Comptes</a>
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
        <li class="nav-item">
          <a class="nav-link active" href="#">Paramètres</a>
        </li>
      </ul>
      <ul class="navbar-nav me-0 mb-2 mb-lg-0">
        <li class="nav-item">
          <?php
            echo "<a class='nav-link active' href='index.php'><img id='notification-icon' src='../images/notification-icon2.svg' alt='logo_jaya'></a>";
            echo "<a class='nav-link active' href='login.php'><img id='logout-icon' src='../images/logout-icon2.svg' alt='logout-icon'></a>";
          ?>
        </li>
      </ul>
      <ul class="navbar-nav me-0 mb-2 mb-lg-0">
        <li class="nav-item">
          <?php
            echo "<div class='nav-link active'>";
            echo "<table>";
            echo "<tr><td><strong>".$connectedUser."</td></tr>";
            echo "<tr><td><font size=-1><a class='nav-link active' href='./changeProfile.php'>".$userProfile."</a></font></td></tr>";
            echo "</table>";
            echo "</div>";
          ?>
        </li>
      </ul>
    </div>
  </div>
</nav>