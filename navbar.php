<div id="navbar" class="container-fluid navbar-fixed-top">
    <div class="row">
        <div class="col-xs-6 col-md-4">
            <a href="index.php"><img class="img-navbar" src="images/JAYA-LOGO.png" alt="logo_jaya"></a>
        </div>
        <div class="col-xs-6 col-md-4">
            <h2></h2>
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
            <!-- <div id="nav-perso" class="row">
                <nav>
                    <ul class="nav nav-pills">
                    <li role="presentation"><a href="index.php" data-toggle="tab" id="accueil">Accueil</a></li>
                    et le reste des onglets
                    </ul>
                </nav>
            </div> -->
    <?php
        }
    ?>
</div>