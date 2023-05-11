<?php
session_start();

// Vérifier si le formulaire a été soumis
if (isset($_POST['submit'])) {
  
  // Récupérer les données du formulaire
  $username = $_POST['username'];
  $email = $_POST['email'];

  // Se connecter à la base de données
  $connUser = new mysqli("localhost", "root", "", "jaya");
  if ($connUser->connect_error) {
    die("Connection failed: " . $connUser->connect_error);
  }
  
  // Déterminer la table où insérer les données en fonction des cases cochées
  if (isset($_POST['admin'])) {
    $table = "admin";
  }
  else if (isset($_POST['respo_ue'])) {
    $table = "responsable_ue";
  }
  else if (isset($_POST['scolarite'])) {
    $table = "scolarite";
  }
  else if (isset($_POST['tuteur'])) {
    $table = "tuteur_univ";
  }
  else if (isset($_POST['etudiant'])) {
    $table = "etudiant";
  }
  
  // Préparer la requête d'insertion dans la table correspondante
  $stmt = $connUser->prepare("INSERT INTO $table (username, email) VALUES (?, ?)");
  $stmt->bind_param("ss", $username, $email);
  $stmt->execute();

  // Vérifier si l'insertion a réussi
  if ($stmt->affected_rows > 0) {
    echo "Les données ont été insérées avec succès dans la table $table";
  } else {
    echo "Une erreur est survenue lors de l'insertion des données dans la table $table : " . $stmt->error;
  }

  // Fermer la requête
  $stmt->close();
  
}


?>

<!DOCTYPE html>
<html>

<head>
    <?php
    include("header.php");
    ?>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="content">
        <div class="bar">
            <span class="sphere"></span>
        </div>
        <div id="content">
            <?php
            include("navbar.php");
            ?>

            <div class="container-fluid">
                <h2 class="center colored">Créer un compte</h2>
                <hr>
                <br>
                <br>
                <form method="post" action="">
                    <div style="display: flex; align-items: center; margin: 0 300px;">
                        <p style="margin-right: 10px;">Utilisateur:</p>
                        <input type="text" name="username" style="flex-grow: 1;">
                    </div>
                    <br>
                    <div style="display: flex; align-items: center; margin: 0 300px;">
                        <p style="margin-right: 10px;">Adresse mail:</p>
                        <input type="email" name="email" style="flex-grow: 1;">
                    </div>
                    <br>
                    <div style="display: flex; align-items: center; margin: 0 300px;">
                        <p style="margin-right: 10px;">Administrateur:</p>
                        <label class="switch">
                            <input type="checkbox" name="admin">
                            <span class="slider"></span>
                        </label>
                        <p style="margin-left: 10px; margin-right: 10px;">Responsable d'UE:</p>
                        <label class="switch">
                            <input type="checkbox" name="respo_ue">
                            <span class="slider"></span>
                        </label>
                        <p style="margin-left: 10px; margin-right: 10px;">Scolarité:</p>
                        <label class="switch">
                            <input type="checkbox" name="scolarite">
                            <span class="slider"></span>
                        </label>
                    </div>
                    <br>
                    <div style="display: flex; align-items: center; margin: 0 300px;">
                        <p style="margin-right: 10px;">Tuteur universitaire:</p>
                        <label class="switch">
                            <input type="checkbox" name="tuteur">
                            <span class="slider"></span>
                        </label>
                        <p style="margin-left: 10px; margin-right: 10px;">Etudiant:</p>
                        <label class="switch">
                            <input type="checkbox" name="etudiant">
                            <span class="slider"></span>
                        </label>
                    </div>
                    <br>
                    <div class="center">
                        <button class="blue-button" type="submit" name="submit">Enregistrer</button>
                        <button class="blue-button" onclick="window.history.back();">Retour</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <style>
/* Switch styles */
.switch {
    position: relative;
    display: inline-block;
    width: 60px;
    height: 34px;
}

.switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ccc;
    transition: .4s;
    border-radius: 34px;
}

.slider:before {
    position: absolute;
    content: "";
    height: 26px;
    width: 26px;
    left: 4px;
    bottom: 4px;
    background-color: white;
    transition: .4s;
    border-radius: 50%;
}

input:checked + .slider {
    background-color: #2196F3;
}

input:focus + .slider {
    box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
    transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
    border-radius: 34px;
}

.slider.round:before {
    border-radius: 50%;
}

/* Style pour les boutons à bascule */
.toggle-btn {
    display: flex;
    align-items: center;
    margin-right: 20px;
}

.toggle-btn span {
    margin-left: 10px;
}

.container-fluid {
    margin-top: 30px;
}

.space {
    padding: 0 50px;
}

/* Style pour les boutons enregistrer et retour */
.btn-container {
    display: flex;
    justify-content: center;
    margin-top: 30px;
}

.btn-container button {
    margin: 0 10px;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    color: white;
    font-size: 16px;
    cursor: pointer;
}

.btn-save {
    background-color: #2196F3;
}

.btn-back {
    background-color: #808080;
}