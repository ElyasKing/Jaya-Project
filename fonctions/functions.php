<?php
// requete SQL pour les index 
function getStudentInformationForIndexes()
{
    $sql = "SELECT 
    u1.ID_Utilisateur AS ID_Etudiant,
    u1.Nom_Utilisateur AS Nom_Etudiant,
    u1.Mail_Utilisateur AS Mail_Etudiant,
    u1.Promo_Utilisateur,
    u1.HuitClos_Utilisateur,
    GROUP_CONCAT(DISTINCT i.Entreprise_Invite SEPARATOR ';') AS Entreprise_Invite,
    GROUP_CONCAT(DISTINCT i.Ville_Invite SEPARATOR ';') AS Ville_Invite,
    GROUP_CONCAT(DISTINCT i.Nom_Invite SEPARATOR ';') AS Nom_Invite,
    GROUP_CONCAT(DISTINCT i.Mail_Invite SEPARATOR ';') AS Mail_Invite,
    u2.Nom_Utilisateur AS Nom_Tuteur_Universitaire,
    u2.Mail_Utilisateur AS Mail_Tuteur_Universitaire
    FROM Utilisateur u1
    LEFT JOIN etudiant_tuteur et ON u1.id_Utilisateur = et.id_Etudiant
    LEFT JOIN Utilisateur u2 ON et.id_Tuteur = u2.id_Utilisateur
    LEFT JOIN est_apprenti ea ON u1.id_utilisateur = ea.id_utilisateur
    LEFT JOIN invite i ON ea.id_invite = i.id_invite
    LEFT JOIN habilitations h ON u1.ID_Utilisateur = h.ID_Utilisateur
    WHERE h.Etudiant_Habilitations='oui'
    GROUP BY u1.ID_Utilisateur;";

    return $sql;
}

// requete SQL pour les index 
function
getStudentInformation_TuteurUniversitaire($User_ID)
{
    $sql = "SELECT 
    u1.ID_Utilisateur AS ID_Etudiant,
    u1.Nom_Utilisateur AS Nom_Etudiant,
    u1.Mail_Utilisateur AS Mail_Etudiant,
    u1.Promo_Utilisateur,
    u1.HuitClos_Utilisateur,
    GROUP_CONCAT(DISTINCT i.Entreprise_Invite SEPARATOR ';') AS Entreprise_Invite,
    GROUP_CONCAT(DISTINCT i.Ville_Invite SEPARATOR ';') AS Ville_Invite,
    GROUP_CONCAT(DISTINCT i.Nom_Invite SEPARATOR ';') AS Nom_Invite,
    GROUP_CONCAT(DISTINCT i.Mail_Invite SEPARATOR ';') AS Mail_Invite,
    u2.Nom_Utilisateur AS Nom_Tuteur_Universitaire,
    u2.Mail_Utilisateur AS Mail_Tuteur_Universitaire
    FROM Utilisateur u1
    LEFT JOIN etudiant_tuteur et ON u1.id_Utilisateur = et.id_Etudiant
    LEFT JOIN Utilisateur u2 ON et.id_Tuteur = u2.id_Utilisateur
    LEFT JOIN est_apprenti ea ON u1.id_utilisateur = ea.id_utilisateur
    LEFT JOIN invite i ON ea.id_invite = i.id_invite
    LEFT JOIN habilitations h ON u1.ID_Utilisateur = h.ID_Utilisateur
    WHERE h.Etudiant_Habilitations='oui' and u2.ID_Utilisateur='" . $User_ID . "'
    GROUP BY u1.ID_Utilisateur;";

    return $sql;
}

// requete SQL pour les index 
function
getStudentInformation_Etudiant($User_ID)
{
    $sql = "SELECT 
    u1.ID_Utilisateur AS ID_Etudiant,
    u1.Nom_Utilisateur AS Nom_Etudiant,
    u1.Mail_Utilisateur AS Mail_Etudiant,
    u1.Promo_Utilisateur,
    u1.HuitClos_Utilisateur,
    GROUP_CONCAT(DISTINCT i.Entreprise_Invite SEPARATOR ';') AS Entreprise_Invite,
    GROUP_CONCAT(DISTINCT i.Ville_Invite SEPARATOR ';') AS Ville_Invite,
    GROUP_CONCAT(DISTINCT i.Nom_Invite SEPARATOR ';') AS Nom_Invite,
    GROUP_CONCAT(DISTINCT i.Mail_Invite SEPARATOR ';') AS Mail_Invite,
    u2.Nom_Utilisateur AS Nom_Tuteur_Universitaire,
    u2.Mail_Utilisateur AS Mail_Tuteur_Universitaire
    FROM Utilisateur u1
    LEFT JOIN etudiant_tuteur et ON u1.id_Utilisateur = et.id_Etudiant
    LEFT JOIN Utilisateur u2 ON et.id_Tuteur = u2.id_Utilisateur
    LEFT JOIN est_apprenti ea ON u1.id_utilisateur = ea.id_utilisateur
    LEFT JOIN invite i ON ea.id_invite = i.id_invite
    LEFT JOIN habilitations h ON u1.ID_Utilisateur = h.ID_Utilisateur
    WHERE h.Etudiant_Habilitations='oui' and u1.ID_Utilisateur='" . $User_ID . "'
    GROUP BY u1.ID_Utilisateur;";

    return $sql;
}

// requete SQL pour les index 
function
getStudentEvaluation_Etudiant($User_ID)
{
    $sql = "SELECT 
    ID_NF,
    NoteFinaleTuteur_NF AS Note_Tuteur,
    NoteFinaleUE_NF AS Note_Finale,
    Poster_NF,
    Rapport_NF
    FROM notes_suivi
    WHERE ID_Utilisateur='" . $User_ID . "' AND Validation_NF='oui'";


    return $sql;
}

//requete SQL pour obtenir la note oral de l'étudiant
function
getStutdentGradeOral($User_ID)
{
    //On va récupérer toutes les notes liées à l'étudiant 
    $sql = "SELECT `ID_NS`,`NoteFinale_NS`,`ID_UtilisateurEvaluateur`,`ID_InviteEvaluateur` FROM `notes_soutenance` WHERE `ID_UtilisateurEvalue`='" . $User_ID . "';";

    $db = Database::connect();

    $result = $db->query($sql);
    $arr_note = [];
    if ($result->rowCount() > 0) {
        $arr_note = $result->fetchAll();
    }
    // ID : 7 = coeff d'un professionnel, ID : 8 = coeff d'un enseignant (cf DB)
    $sql = "SELECT `Description_param` FROM `parametres` WHERE `ID_param`='7' OR `ID_param`='8';";
    $result = $db->query($sql);

    $rows = $result->fetchAll();
    if($rows[0]['Description_param'] <> "" || $rows[1]['Description_param']<>""){
        $coeff_pro = $rows[0]['Description_param'];
        $coeff_enseignant = $rows[1]['Description_param'];


        $noteFinale = 0;
        $division = 0;
        if (!empty($arr_note)) {
            foreach ($arr_note as $notes) {

                //si l'évaluateur est un enseignant
                if ($notes['ID_UtilisateurEvaluateur'] != NULL) {
                    $noteFinale = $noteFinale + $notes['NoteFinale_NS'] * $coeff_enseignant;
                    $division = $division + $coeff_enseignant;
                }
                //Cette fois c'est un invite qui évalue
                if ($notes['ID_InviteEvaluateur'] != NULL) {
                    //On vérifie donc si l'invité est un pro ou un enseignant en premier temps
                    $sql = "SELECT `EstEnseignant_Invite`,`EstProfessionel_Invite` FROM `invite` WHERE `ID_Invite`='" . $notes['ID_InviteEvaluateur'] . "';";
                    $result = $db->query($sql);
                    if ($result->rowCount() > 0) {
                        $row = $result->fetch();
                        $estEnseignant = $row['EstEnseignant_Invite'];
                        $estPro = $row['EstProfessionel_Invite'];
                    }
                    //Si l'invite est enseignant alors on applique la condition de calcul enseignant
                    if ($estEnseignant == "oui") {
                        $noteFinale = $noteFinale + $notes['NoteFinale_NS'] * $coeff_enseignant;
                        $division = $division + $coeff_enseignant;
                    }
                    //Dans le cas contraire on prend en compte les règles de calculs pro
                    if ($estPro == "oui") {
                        $noteFinale = $noteFinale + $notes['NoteFinale_NS'] * $coeff_pro;
                        $division = $division + $coeff_pro;
                    }
                }
            }
            //A la fin ne pas oublier de remettre la note sur 20 
            $noteFinale = $noteFinale / $division;

            return $noteFinale;
        }else return "";
    } else return "DEF";
}

// requete SQL pour obtenir les informations liées aiu planning de l'étudiant

function getStudentSchedule_Etudiant($User_ID)
{
    $sql = "SELECT p.ID_Planning, p.DateSession_Planning, p.HeureDebutSession_Planning 
    FROM planning p
    INNER JOIN utilisateur u ON u.ID_Planning = p.ID_Planning
    WHERE u.ID_Utilisateur = '" . $User_ID . "'
    AND p.ValidationScolarite_Planning = 'oui';";

    return $sql;
}

// requete SQL pour recuperer les informations des étudiants en fonction d'un étudiant
function getStudentInformationById($id)
{
    $sql = "SELECT 
            utilisateur.ID_Utilisateur, 
            utilisateur.nom_Utilisateur, 
            utilisateur.Mail_Utilisateur, 
            utilisateur.Promo_Utilisateur, 
            utilisateur.HuitClos_Utilisateur,
            invite.Entreprise_Invite, 
            invite.Ville_Invite, 
            invite.Nom_Invite, 
            invite.Mail_Invite 
            FROM utilisateur 
        LEFT JOIN est_apprenti ON utilisateur.ID_Utilisateur = est_apprenti.ID_Utilisateur 
        LEFT JOIN invite ON est_apprenti.ID_Invite = invite.ID_Invite 
        LEFT JOIN habilitations ON utilisateur.ID_Utilisateur = habilitations.ID_Utilisateur 
        WHERE utilisateur.ID_Utilisateur = $id";

    return $sql;
}

// recuperer les tuteurs entreprises d'un étudiant
function getStudentProfessionalTutorById($id)
{
    $sql = "SELECT 
            invite.ID_Invite, 
            invite.Nom_Invite, 
            invite.Mail_Invite 
        FROM invite 
        INNER JOIN est_apprenti ON invite.ID_Invite = est_apprenti.ID_Invite        
        WHERE est_apprenti.Id_Utilisateur = $id;";

    return $sql;
}

// recuperer les tuteurs entreprises d'un étudiant
function getStudentUniversityTutorById($id)
{
    $sql = "SELECT 
            utilisateur.ID_Utilisateur, 
            utilisateur.nom_Utilisateur, 
            utilisateur.Mail_Utilisateur 
        FROM utilisateur 
        INNER JOIN etudiant_tuteur ON utilisateur.ID_Utilisateur = etudiant_tuteur.ID_Tuteur
        WHERE etudiant_tuteur.Id_etudiant = $id;";

    return $sql;
}

//fonction utilisée pour les retours à la ligne dans les data-table
function lineFeedWithSeparator($element)
{
    return str_replace(';', '<br>', $element);
}

// recuperer les informations de compte d'un utilisateur
function getAccountInformationsById($id)
{
    $sql = "SELECT 
            H.Id_Utilisateur,
            U.Nom_Utilisateur, 
            '****' AS MDP_Utilisateur, 
            H.Admin_Habilitations, 
            H.ResponsableUE_Habilitations, 
            H.Scolarite_Habilitations, 
            H.TuteurUniversitaire_Habilitations, 
            H.Etudiant_Habilitations 
        FROM habilitations H 
        JOIN utilisateur U ON U.Id_Utilisateur = H.Id_Utilisateur
        WHERE H.ID_Utilisateur = $id;";

    return $sql;
}

// Fonction de génération de mot de passe aléatoire
function generatePassword()
{
    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $password = array();
    $alphaLength = strlen($alphabet) - 1;
    for ($i = 0; $i < 8; $i++) {
        $n = rand(0, $alphaLength);
        $password[] = $alphabet[$n];
    }
    return implode($password);
}

// Parametres : recuperer les parametres en fonction du type
function getSettings($settingType)
{
    if ($settingType == "fixe") {
        $sql = "SELECT 
                ID_param, 
                Nom_param,
                Description_param, 
                NbPoint_param 
            FROM parametres
            WHERE NbPoint_param IS NULL";
    } elseif ($settingType == "dynamique") {
        $sql = "SELECT 
                ID_param, 
                Nom_param,
                Description_param, 
                NbPoint_param 
            FROM parametres
            WHERE NbPoint_param IS NOT NULL";
    }

    return $sql;
}

// Requête SQL pour récupérer les informations de tous les étudiants à insérer dans la liste 

function getStudentForOral($User_ID)
{
$query = "SELECT U.Nom_Utilisateur 
FROM utilisateur U 
LEFT JOIN habilitations H ON U.ID_Utilisateur = H.ID_Utilisateur 
WHERE H.Etudiant_Habilitations = 'oui' AND U.ID_Utilisateur NOT IN (SELECT ID_UtilisateurEvalue 
                                                                    FROM notes_soutenance 
                                                                    WHERE ID_UtilisateurEvaluateur = '" . $User_ID . "');";

return $query;
}
