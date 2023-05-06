-- Table utilisateur
INSERT INTO `utilisateur` (`Nom_Utilisateur`, `Mail_Utilisateur`, `MDP_Utilisateur`, `Promo_Utilisateur`, `Annee_Utilisateur`, `HuisClos_Utilisateur`, `ID_Planning`) VALUES
('Florian Borgne', 'florian.borgne@etud.u-picardie.fr', 'mdp', 'M2 MIAGE', '2022-2023', 'non', NULL),
('Elias Chahraiar', 'elias.chahraiar@etud.u-picardie.fr', 'mdp', 'M2 MIAGE', '2022-2023', 'non', NULL),
('Selaïman Vanlanduyt', 'selaiman.vanlanduyt@etud.u-picardie.fr', 'mdp', 'M2 MIAGE', '2022-2023', 'non', NULL),
('Joanna Reiss', 'joanna.reiss@etud.u-picardie.fr', 'mdp', 'M2 MIAGE', '2022-2023', 'oui', NULL),
('Jéremy Ruhlmann', 'jeremy.ruhlmann@etud.u-picardie.fr', 'mdp', 'M2 MIAGE', '2022-2023', 'oui', NULL),
('Anthony Oveermer', 'anthony.oveermer@etud.u-picardie.fr', 'mdp', 'M1 MIAGE', '2022-2023', 'non', NULL),
('Nino Belic', 'nino.belic@etud.u-picardie.fr', 'mdp', 'M1 MIAGE', '2022-2023', 'non', NULL),
('Florian Berroy', 'florian.berroy@etud.u-picardie.fr', 'mdp', 'M1 MIAGE', '2022-2023', 'oui', NULL),
('Quentin Bondoux', 'quentin.bondoux@etud.u-picardie.fr', 'mdp', 'M1 MIAGE', '2021-2022', 'non', NULL),
('Thibaud Dufour', 'thibaud.dufour@etud.u-picardie.fr', 'mdp', 'M1 MIAGE', '2021-2022', 'oui', NULL),
('Stéphanie Dertin', 'stephanie.dertin@u-picardie.fr', 'mdp', NULL, NULL, NULL, NULL),
('Catherine Barry', 'catherine.barry@u-picardie.fr', 'mdp', NULL, NULL, NULL, NULL),
('Anne Lapujade', 'anne.lapujade@u-picardie.fr', 'mdp', NULL, NULL, NULL, NULL),
('Jean-Luc Guérin', 'jean-luc.guerin@u-picardie.fr', 'mdp', NULL, NULL, NULL, NULL),
('Dominique Groux', 'dominique.groux@u-picardie.fr', 'mdp', NULL, NULL, NULL, NULL),
('Florence Leve', 'florence.leve@u-picardie.fr', 'mdp', NULL, NULL, NULL, NULL),
('Julien Leveneur', 'julien.leveneur@etud.u-picardie.fr', 'mdp', 'M1 MIAGE', '2022-2023', 'non', NULL);

-- Table etudiant_tuteur
INSERT INTO `etudiant_tuteur` (`ID_etudiant`, `ID_tuteur`) VALUES 
('1', '14'), 
('2', '12'), 
('3', '15'), 
('4', '13'), 
('5', '12'), 
('6', '12'), 
('7', '12'), 
('8', '13'), 
('9', '14'), 
('10', '15'), 
('17', '16');

-- Table habilitations
INSERT INTO `habilitations` (`Admin_Habilitations`, `ResponsableUE_Habilitations`, `Scolarite_Habilitations`, `TuteurUniversitaire_Habilitations`, `Etudiant_Habilitations`, `ID_Utilisateur`) VALUES 
('oui', 'oui', 'oui', 'oui', 'oui', '1'), 
('oui', 'oui', 'oui', 'oui', 'oui', '2'), 
('oui', 'oui', 'oui', 'oui', 'oui', '3'), 
('oui', 'oui', 'oui', 'oui', 'oui', '4'), 
('oui', 'oui', 'oui', 'oui', 'oui', '5'), 
('non', 'non', 'non', 'non', 'oui', '6'), 
('non', 'non', 'non', 'non', 'oui', '7'), 
('non', 'non', 'non', 'non', 'oui', '8'), 
('non', 'non', 'non', 'non', 'oui', '9'), 
('non', 'non', 'non', 'non', 'oui', '10'), 
('oui', 'non', 'oui', 'non', 'non', '11'), 
('oui', 'oui', 'non', 'oui', 'non', '12'), 
('oui', 'non', 'non', 'oui', 'non', '13'), 
('non', 'non', 'non', 'oui', 'non', '14'), 
('non', 'non', 'non', 'oui', 'non', '15'), 
('non', 'non', 'non', 'oui', 'non', '16'), 
('non', 'non', 'non', 'non', 'oui', '17');

-- Table parametres (dynamique)
INSERT INTO `parametres` (`Nom_param`, `Description_param`, `NbPoint_param`) VALUES
('Qualité du poster', NULL, 3),
('Gestion du temps', NULL, 4),
('Clarté du discours', NULL, 12);

-- Table invite
INSERT INTO `invite` (`Nom_Invite`, `Mail_Invite`, `Entreprise_Invite`, `Ville_Invite`, `Telephone_Invite`, `EstEnseignant_Invite`, `EstProfessionel_Invite`, `QRCode_Invite`) VALUES 
('Xavier Brassart', 'xavier.brassart@loreal.com', 'L\'Oréal', 'Roye', NULL, 'non', 'oui', NULL), 
('Yohann Gentelet', 'yohann@mariloo.com', 'Mariloo', 'Lille', NULL, 'non', 'oui', NULL), 
('Philippe Garnier', 'philippe.garnier@cabriepicardie.com', 'Crédit Agricole Brie Picardie', 'Amiens', NULL, 'non', 'oui', NULL), 
('John Doe', 'john.doe@IT-john.com', 'JOHN DOE', 'Paris', NULL, 'non', 'oui', NULL), 
('Bruce Wayne', 'bruce@wayne-enterprise.com', 'Wayne Enterprise', 'Gotham City', NULL, 'non', 'oui', NULL), 
('Alain Cournier', 'alain.cournier@u-picardie.fr', 'UPJV', 'Amiens', NULL, 'oui', 'non', NULL), 
('Gael Le Mahec', 'gael.lemahec@u-picardie.fr', 'UPJV', 'Amiens', NULL, 'oui', 'non', NULL);

-- Table est_apprenti
INSERT INTO `est_apprenti` (`ID_Utilisateur`, `ID_Invite`) VALUES 
('1', '1'), 
('2', '2'), 
('3', '3'), 
('4', '4'), 
('5', '5'), 
('17', '4');

-- Table notes_soutenances
INSERT INTO `notes_soutenance` (`NoteFinale_NS`, `Commentaire_NS`, `ID_UtilisateurEvalue`, `ID_InviteEvaluateur`, `ID_UtilisateurEvaluateur`) VALUES 
('17', 'ok', '3', NULL, '12'), 
('20', 'Florian présente bien', '1', '3', NULL), 
('3', 'couleur du poster -10', '2', NULL, '15'), 
('18', 'ok', '3', '5', NULL);