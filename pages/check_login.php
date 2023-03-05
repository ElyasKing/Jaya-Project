<?php
session_start();
include "../application_config/db_class.php";

$conn = Database::connect();


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $email = $_POST["email"];
    $password = $_POST["password"];
}

if((isset($email) && !empty($email)) && (isset($password) && !empty($password))){
    // Requête SQL pour récupérer les informations de l'utilisateur correspondant à l'email fourni
    $query =    "SELECT A.ID_UTILISATEUR,
                    Nom_Utilisateur, 
                    Mail_Utilisateur, 
                    MDP_Utilisateur, 
                    Admin_habilitations, 
                    ResponsableUE_Habilitations, 
                    Scolarite_Habilitations, 
                    TuteurUniversitaire_Habilitations, 
                    Etudiant_Habilitations 
                FROM utilisateur A 
                LEFT JOIN habilitations B ON A.ID_Utilisateur = B.ID_Utilisateur 
                WHERE A.Mail_Utilisateur = '" . $email . "'";
    $user = $conn->query($query)->fetch();

    $conn = Database::disconnect();

    // Vérifier si l'utilisateur existe
    // Vérifier si le mot de passe est correct
    if ($password == $user["MDP_Utilisateur"]) {

        //Enregistrer en session toute les infos utilisateur
        $_SESSION["user_id"] = $user["ID_UTILISATEUR"];
        $_SESSION["user_name"] = $user["Nom_Utilisateur"];
        $_SESSION["user_mail"] = $user["Mail_Utilisateur"];
        $_SESSION["user_is_admin"] = $user["Admin_habilitations"];
        $_SESSION["user_is_respUE"] = $user["ResponsableUE_Habilitations"];
        $_SESSION["user_is_scola"] = $user["Scolarite_Habilitations"];
        $_SESSION["user_is_tuteurU"] = $user["TuteurUniversitaire_Habilitations"];
        $_SESSION["user_is_student"] = $user["Etudiant_Habilitations"];

        //Definir une variable de session pour un acces au changement de profil si habilitations > 1
        $_SESSION['change_profile_access'] = 0;

        ($_SESSION["user_is_admin"] == "oui" ? $_SESSION['change_profile_access'] += 1 : $_SESSION['change_profile_access'] += 0 );
        ($_SESSION["user_is_respUE"] == "oui" ? $_SESSION['change_profile_access'] += 1 : $_SESSION['change_profile_access'] += 0 );
        ($_SESSION["user_is_scola"] == "oui" ? $_SESSION['change_profile_access'] += 1 : $_SESSION['change_profile_access'] += 0 );
        ($_SESSION["user_is_tuteurU"] == "oui" ? $_SESSION['change_profile_access'] += 1 : $_SESSION['change_profile_access'] += 0 );
        ($_SESSION["user_is_student"] == "oui" ? $_SESSION['change_profile_access'] += 1 : $_SESSION['change_profile_access'] += 0 );
        

        /*Definir le profil actif le plus élvevé, ordre:
        * 1 - Administrateur
        * 2 - Responsable UE
        * 3 - Scolarite
        * 4 - Tuteur universitaire
        * 5 - Etudiant
        */
        if($_SESSION["user_is_admin"] == "oui"){
            $_SESSION['change_profile_access'] += 1;
            $_SESSION['active_profile'] = "ADMINISTRATEUR";
        }
        elseif($_SESSION["user_is_admin"] == "non" && $_SESSION["user_is_respUE"] == "oui"){
            $_SESSION['change_profile_access'] += 1;
            $_SESSION['active_profile'] = "RESPONSABLE UE";
        }
        elseif($_SESSION["user_is_admin"] == "non" && $_SESSION["user_is_respUE"] == "non" && 
            $_SESSION["user_is_scola"] == "oui"){
            $_SESSION['change_profile_access'] += 1;
            $_SESSION['active_profile'] = "SCOLARITE";
        }
        elseif($_SESSION["user_is_admin"] == "non" && $_SESSION["user_is_respUE"] == "non" && 
            $_SESSION["user_is_scola"] == "non" && $_SESSION["user_is_tuteurU"] == "oui"){
            $_SESSION['change_profile_access'] += 1;
            $_SESSION['active_profile'] = "TUTEUR UNIVERSITAIRE";
        }
        elseif($_SESSION["user_is_admin"] == "non" && $_SESSION["user_is_respUE"] == "non" && 
            $_SESSION["user_is_scola"] == "non" && $_SESSION["user_is_tuteurU"] == "non" &&
            $_SESSION["user_is_student"] == "oui"){
            $_SESSION['change_profile_access'] += 1;
            $_SESSION['active_profile'] = "ETUDIANT";
        }
       
        // Authentification réussie, rediriger l'utilisateur vers l'accueil de l'application
        $_SESSION['flag'] = 0;
        header("Location: index.php");
    } else {
        // Authentification échouée, mot de passe incorrect
        $_SESSION['flag'] = 1;
        header("Location: login.php");
    }
}else{
    // Aucune saisie. Ne rien faire.
}
?>