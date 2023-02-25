<nav id="navbar" class="container-fluid navbar-fixed-top">
    <div class="row">
        <div class="col-xs-6 col-md-4">
            <a href="index.php"><img class="img-navbar" src="images/JAYA-LOGO.png" alt="logo_jaya"></a>
        </div>
        <div class="col-xs-6 col-md-4">
        <!-- <img src="images/notification-icon.png" alt="notification-icon"> -->
        <!-- <a href="pages/login.php"><img class="img-navbar2" src="images/logOut-icon.png" alt="logOut-icon"></a> -->
        </div>
        
        <div id="navbar-userInfo" class="col-xs-6 col-md-4">
            
            <?php
                echo "<table>";
                echo "<tr><td><strong>".$connectedUser."</td></tr>";
                echo "<tr><td><font size=-1>".$userProfile."</font></td></tr>";
                echo "</table>";
            ?>
        </div>
    </div>
    <?php 
        if($userProfile == "ADMINISTRATEUR"){
    ?>
            <div id="nav-perso" class="row">
                <nav>
                    <ul class="nav nav-pills">
                    <li role="presentation"><a href="index.php" data-toggle="tab" id="accueil">Accueil</a></li>
                    <li role="presentation"><a href="#" data-toggle="tab" id="accueil">Comptes</a></li>
                    <li role="presentation"><a href="#" data-toggle="tab" id="accueil">Soutenances</a></li>
                    <li role="presentation"><a href="#" data-toggle="tab" id="accueil">Planning</a></li>
                    <li role="presentation"><a href="#" data-toggle="tab" id="accueil">Suivi recap</a></li>
                    <li role="presentation"><a href="#" data-toggle="tab" id="accueil">Paramètres</a></li>
                    </ul>
                </nav>
            </div>
    <?php
        }
    ?>
</nav>
