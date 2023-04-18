<?php
    // requete SQL pour les index 
    function getStudentInformationForIndexes(){
        $sql = "SELECT DISTINCT
            u1.ID_Utilisateur AS ID_Etudiant,
            u1.Nom_Utilisateur AS Nom_Etudiant,
            u1.Mail_Utilisateur AS Mail_Etudiant,
            u1.Promo_Utilisateur,
            u1.HuitClos_Utilisateur,
            u2.Nom_Utilisateur AS Nom_Tuteur_Universitaire,
            u2.Mail_Utilisateur AS Mail_Tuteur_Universitaire,
            i.Entreprise_Invite,
            i.Ville_Invite,
            i.Nom_Invite,
            i.Mail_Invite
            FROM Utilisateur u1
        LEFT JOIN etudiant_tuteur et ON u1.id_Utilisateur = et.id_Etudiant
        LEFT JOIN Utilisateur u2 ON et.id_Tuteur = u2.id_Utilisateur
        LEFT JOIN est_apprenti ea ON u1.id_utilisateur = ea.id_utilisateur
        LEFT JOIN invite i ON ea.id_invite = i.id_invite
        LEFT JOIN habilitations h ON u1.ID_Utilisateur = h.ID_Utilisateur
        WHERE h.Etudiant_Habilitations='oui';
        GROUP BY u1.ID_Utilisateur;";

        return $sql;
    }

    // requete SQL pour recuperer les informations des étudiants en fonction d'un étudiant
    function getStudentInformationById($id){
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
    function getStudentProfessionalTutorById($id){
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
    function getStudentUniversityTutorById($id){
        $sql = "SELECT 
            utilisateur.ID_Utilisateur, 
            utilisateur.nom_Utilisateur, 
            utilisateur.Mail_Utilisateur 
        FROM utilisateur 
        INNER JOIN etudiant_tuteur ON utilisateur.ID_Utilisateur = etudiant_tuteur.ID_Tuteur
        WHERE etudiant_tuteur.Id_etudiant = 6;";

        return $sql;
    }
?>