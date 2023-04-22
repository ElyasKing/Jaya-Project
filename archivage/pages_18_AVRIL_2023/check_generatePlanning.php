<?php
   include("../application_config/db_class.php");
   include("../fonctions/functions.php");
   session_start();

   $db = Database::connect();

   //recup la valeur du paramètre fixe "nb d'étudiant par session de soutenance"
    $query = "SELECT Nom_param, Description_param FROM parametres WHERE NbPoint_param IS NULL";
    $statement = $db->query($query);

    while ($row = $statement->fetch()){
        if($row['Nom_param'] == "Nombre d'étudiants par session de soutenance"){
            $nbStudentIntoSession = $row['Description_param'];
        }
    }

    /* Pour creer les plannings.
    *Si "Nombre d'étudiants par session de soutenance" = 1 et que 4 étudiants:
    *--> 1 étudiant M2_MIAGE, 2022-2023, non huis-clos;
    *--> 1 étudiant M1_MIAGE, 2022-2023, non huis-clos;
    *--> 1 étudiant M2_MIAGE, 2022-2023, huis-clos;
    *--> 1 étudiant M1_MIAGE, 2022-2023, huis-clos;
    *--> 1 étudiant M2_MIAGE, 2021-2022, non huis-clos; (pas pris en compte car année précédente)
    *--> 1 étudiant M1_MIAGE, 2021-2022, non huis-clos; (pas pris en compte car année précédente)
    */

    //quelle est l'année en cours ?
    $date = getdate();
    $currentYear = $date['year'];
    $lastYear = $currentYear - 1;
    $currentStudentYear = $lastYear."-".$currentYear;
    

    /*recuperer les utilisateurs ayant pour habilitation "étudiant" 
    * et appartenant à la promo de l'année en cours $currentStudentYear
    *les étudiants doivent n'être affectés a aucun planning
    */
    $query = "SELECT DISTINCT 
                u1.id_Utilisateur,
                u1.Nom_Utilisateur AS Nom_Etudiant,
                u1.Annee_Utilisateur,
                i.Entreprise_Invite, 
                i.Ville_Invite,
                u1.Promo_Utilisateur, 
                u1.HuisClos_Utilisateur 
            FROM Utilisateur u1 
            LEFT JOIN etudiant_tuteur et ON u1.id_Utilisateur = et.id_Etudiant 
            INNER JOIN est_apprenti ea ON u1.id_utilisateur = ea.id_utilisateur 
            INNER JOIN invite i ON ea.id_invite = i.id_invite
            WHERE u1.Annee_Utilisateur = '$currentStudentYear'
            AND u1.ID_Planning IS NULL";
    
    $statement = $db->query($query);
    $studentsList = $statement->fetchAll();

    // echo "<pre>";
    // var_dump($studentsList);
    // echo "</pre>";
    
    
    //création de tableau pour trier les étudiants (huis-clos = 1 planning individuel, non-huis-clos, Promotion)
    $studentsList_M1_huisClos = array();
    $studentsList_M2_huisClos = array();
    $studentsList_M1_nonHuisClos = array();
    $studentsList_M2_nonHuisClos = array();

    //tris des étudiants pour les classer dans les tableaux adéquats
    for($i=0; $i < count($studentsList); $i++){
        if($studentsList[$i]["HuisClos_Utilisateur"] == "oui"){ // huis clos ?
            if($studentsList[$i]["Promo_Utilisateur"] == "M1_MIAGE"){ // M1 ?
                $studentsList_M1_huisClos[] = $studentsList[$i]; // alors go tableau M1 huis clos.
            }
            elseif($studentsList[$i]["Promo_Utilisateur"] == "M2_MIAGE"){ // M2 ?
                $studentsList_M2_huisClos[] = $studentsList[$i]; // alors go tableau M2 huis clos.
            }
        }elseif($studentsList[$i]["HuisClos_Utilisateur"] == "non"){ // non huis clos ?
            if($studentsList[$i]["Promo_Utilisateur"] == "M1_MIAGE"){ // M1 ?
                $studentsList_M1_nonHuisClos[] = $studentsList[$i]; // alors go tableau M1 non huis clos.
            }
            elseif($studentsList[$i]["Promo_Utilisateur"] == "M2_MIAGE"){ // M2 ?
                $studentsList_M2_nonHuisClos[] = $studentsList[$i]; // alors go tableau M2 non huis clos.
            }
        }
    }

    // echo "<pre>";
    // var_dump($studentsList_M1_huisClos);
    // echo "</pre>";
    // echo "<pre>";
    // var_dump($studentsList_M2_huisClos);
    // echo "</pre>";
    // echo "<pre>";
    // var_dump($studentsList_M1_nonHuisClos);
    // echo "</pre>";
    // echo "<pre>";
    // var_dump($studentsList_M2_nonHuisClos);
    // echo "</pre>";
    // die;

    //création des plannings pour chaque tableaux.
        //M1 huis clos --> 1 planning par étudiant.
    if(count($studentsList_M1_huisClos) > 0){
        // for($i=0; $i < count($studentsList_M1_huisClos); $i++){ 
        //     $planningNameTemp = "Planning-M1_huis_clos-N°".$i;
        //     $query = "INSERT INTO planning (Nom_Planning, DateSession_Planning, HeureDebutSession_Planning)
        //               VALUES ('".$planningNameTemp."',NULL,NULL)"; //creer le "Planning-M1_huis_clos-N°XXX" en base
        //     $statement = $db->prepare($query);
        //     $statement->execute();
        //     $idPlanning = $db->lastInsertId();
    
        //     $query = "UPDATE utilisateur SET ID_Planning = ".$idPlanning." WHERE ID_Utilisateur = ".$studentsList_M1_huisClos[$i]['id_Utilisateur']."";
        //     $statement = $db->prepare($query); //lier le planning à l'étudiant.
        //     $statement->execute();
        // }

        $currentPlanningIndex = 1;

        // vérifier le dernier index de planification existant dans la base de données
        $query = "SELECT MAX(RIGHT(Nom_Planning, LOCATE('-', REVERSE(Nom_Planning))-1)) AS last_planning_index FROM planning WHERE Nom_Planning LIKE '%M1_huis_clos%'";
        $statement = $db->prepare($query);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        $lastPlanningIndex = $result['last_planning_index'];

        // si le dernier index est défini, redémarrer currentPlanningIndex à la valeur suivante
        if($lastPlanningIndex != NULL) {
            $currentPlanningIndex = $lastPlanningIndex + 1;
        }

        foreach ($studentsList_M1_huisClos as $student) {
            // Vérifier si l'étudiant est déjà associé à un planning
            $query = "SELECT ID_Utilisateur FROM utilisateur WHERE ID_Utilisateur = ".$student['id_Utilisateur']." AND ID_Planning IS NULL";
            $statement = $db->prepare($query);
            $statement->execute();
            $result = $statement->fetch(PDO::FETCH_ASSOC);
        
            // Si l'étudiant n'est pas encore associé à un planning, en créer un et l'y lier
            if($result) {
                $planningNameTemp = "Planning-M1_huis_clos-N°".$currentPlanningIndex;
                $query = "INSERT INTO planning (Nom_Planning, DateSession_Planning, HeureDebutSession_Planning)
                        VALUES ('".$planningNameTemp."',NULL,NULL)"; // Créer le "Planning-M1_huis_clos-N°XXX" en base
                $statement = $db->prepare($query);
                $statement->execute();
                $idPlanning = $db->lastInsertId();
        
                $query = "UPDATE utilisateur SET ID_Planning = ".$idPlanning." WHERE ID_Utilisateur = ".$student['id_Utilisateur']."";
                $statement = $db->prepare($query);
                $statement->execute();
        
                $currentPlanningIndex++;
            }
        }
    }
    

        //M2 huis clos --> 1 planning par étudiant.
    if(count($studentsList_M2_huisClos) > 0){
        $currentPlanningIndex = 1;

        // vérifier le dernier index de planification existant dans la base de données
        $query = "SELECT MAX(RIGHT(Nom_Planning, LOCATE('-', REVERSE(Nom_Planning))-1)) AS last_planning_index FROM planning WHERE Nom_Planning LIKE '%M2_huis_clos%'";
        $statement = $db->prepare($query);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        $lastPlanningIndex = $result['last_planning_index'];

        // si le dernier index est défini, redémarrer currentPlanningIndex à la valeur suivante
        if($lastPlanningIndex != NULL) {
            $currentPlanningIndex = $lastPlanningIndex + 1;
        }

        foreach ($studentsList_M2_huisClos as $student) {
            // Vérifier si l'étudiant est déjà associé à un planning
            $query = "SELECT ID_Utilisateur FROM utilisateur WHERE ID_Utilisateur = ".$student['id_Utilisateur']." AND ID_Planning IS NULL";
            $statement = $db->prepare($query);
            $statement->execute();
            $result = $statement->fetch(PDO::FETCH_ASSOC);
        
            // Si l'étudiant n'est pas encore associé à un planning, en créer un et l'y lier
            if($result) {
                $planningNameTemp = "Planning-M2_huis_clos-N°".$currentPlanningIndex;
                $query = "INSERT INTO planning (Nom_Planning, DateSession_Planning, HeureDebutSession_Planning)
                        VALUES ('".$planningNameTemp."',NULL,NULL)"; // Créer le "Planning-M1_huis_clos-N°XXX" en base
                $statement = $db->prepare($query);
                $statement->execute();
                $idPlanning = $db->lastInsertId();
        
                $query = "UPDATE utilisateur SET ID_Planning = ".$idPlanning." WHERE ID_Utilisateur = ".$student['id_Utilisateur']."";
                $statement = $db->prepare($query);
                $statement->execute();
        
                $currentPlanningIndex++;
            }
        }
    }

    //M1 non huis clos.
    if(count($studentsList_M1_nonHuisClos) > 0){
        $nbPlanningExpected = ceil(count($studentsList_M1_nonHuisClos)/$nbStudentIntoSession); //nb de planning M1 non huis clos attendus.
        $cptStudentIntoPlanning = 0; // cpt nombre d'étud par session
        $currentPlanningIndex = 1;

        // vérifier le dernier index de planification existant dans la base de données
        $query = "SELECT MAX(RIGHT(Nom_Planning, LOCATE('-', REVERSE(Nom_Planning))-1)) AS last_planning_index FROM planning WHERE Nom_Planning LIKE '%M1_non_huis_clos%'";
        $statement = $db->prepare($query);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        $lastPlanningIndex = $result['last_planning_index'];

        // si le dernier index est défini, redémarrer currentPlanningIndex à la valeur suivante
        if($lastPlanningIndex != NULL) {
            $currentPlanningIndex = $lastPlanningIndex + 1;
        }

        for($i=0; $i < $nbPlanningExpected; $i++){ //TQ on a pas atteint le nb de planning attendus
            $planningNameTemp = "Planning-M1_non_huis_clos-N°".$currentPlanningIndex;
            $query = "INSERT INTO planning (Nom_Planning, DateSession_Planning, HeureDebutSession_Planning)
                    VALUES ('".$planningNameTemp."',NULL,NULL)"; //creer le "Planning-M1_huis_clos-N°XXX" en base
            $statement = $db->prepare($query);
            $statement->execute();
            $idPlanning = $db->lastInsertId();

            for($j=0; $j < $nbStudentIntoSession; $j++) {
                if(count($studentsList_M1_nonHuisClos) == 0) {
                    break; //plus d'étudiants à assigner
                }
                $student = array_shift($studentsList_M1_nonHuisClos);

                // vérifier si l'étudiant est déjà associé à un planning
                $query = "SELECT ID_Utilisateur FROM utilisateur WHERE ID_Utilisateur = ".$student['id_Utilisateur']." AND ID_Planning IS NULL";
                $statement = $db->prepare($query);
                $statement->execute();
                $result = $statement->fetch(PDO::FETCH_ASSOC);

                // si l'étudiant n'est pas encore associé à un planning, le lier au nouveau planning
                if($result) {
                    $query = "UPDATE utilisateur SET ID_Planning = ".$idPlanning." WHERE ID_Utilisateur = ".$student['id_Utilisateur']."";
                    $statement = $db->prepare($query);
                    $statement->execute();
                    $cptStudentIntoPlanning++;
                }
            }
            $currentPlanningIndex++;
        }
    }

        //M2 non huis clos
    if(count($studentsList_M2_nonHuisClos) > 0){
        $nbPlanningExpected = ceil(count($studentsList_M2_nonHuisClos)/$nbStudentIntoSession); //nb de planning M1 non huis clos attendus.
        $cptStudentIntoPlanning = 0; // cpt nombre d'étud par session
        $currentPlanningIndex = 1;

        // vérifier le dernier index de planification existant dans la base de données
        $query = "SELECT MAX(RIGHT(Nom_Planning, LOCATE('-', REVERSE(Nom_Planning))-1)) AS last_planning_index FROM planning WHERE Nom_Planning LIKE '%M2_non_huis_clos%'";
        $statement = $db->prepare($query);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        $lastPlanningIndex = $result['last_planning_index'];

        // si le dernier index est défini, redémarrer currentPlanningIndex à la valeur suivante
        if($lastPlanningIndex != NULL) {
            $currentPlanningIndex = $lastPlanningIndex + 1;
        }

        for($i=0; $i < $nbPlanningExpected; $i++){ //TQ on a pas atteint le nb de planning attendus
            $planningNameTemp = "Planning-M2_non_huis_clos-N°".$currentPlanningIndex;
            $query = "INSERT INTO planning (Nom_Planning, DateSession_Planning, HeureDebutSession_Planning)
                    VALUES ('".$planningNameTemp."',NULL,NULL)"; //creer le "Planning-M1_huis_clos-N°XXX" en base
            $statement = $db->prepare($query);
            $statement->execute();
            $idPlanning = $db->lastInsertId();

            for($j=0; $j < $nbStudentIntoSession; $j++) {
                if(count($studentsList_M2_nonHuisClos) == 0) {
                    break; //plus d'étudiants à assigner
                }
                $student = array_shift($studentsList_M2_nonHuisClos);

                // vérifier si l'étudiant est déjà associé à un planning
                $query = "SELECT ID_Utilisateur FROM utilisateur WHERE ID_Utilisateur = ".$student['id_Utilisateur']." AND ID_Planning IS NULL";
                $statement = $db->prepare($query);
                $statement->execute();
                $result = $statement->fetch(PDO::FETCH_ASSOC);

                // si l'étudiant n'est pas encore associé à un planning, le lier au nouveau planning
                if($result) {
                    $query = "UPDATE utilisateur SET ID_Planning = ".$idPlanning." WHERE ID_Utilisateur = ".$student['id_Utilisateur']."";
                    $statement = $db->prepare($query);
                    $statement->execute();
                    $cptStudentIntoPlanning++;
                }
            }
            $currentPlanningIndex++;
        }

        // $nbPlanningExpected = roundSup(count($studentsList_M2_nonHuisClos)/$nbStudentIntoSession); //nb de planning M1 non huis clos attendus.
        // $cptStudentIntoPlanning = $nbStudentIntoSession; // cpt nombre d'étud par session

        // for($i=0; $i < $nbPlanningExpected; $i++){ //TQ on a pas atteint le nb de planning attendus
        //     $planningNameTemp = "Planning-M2_non_huis_clos-N°".$i;
        //     $query = "INSERT INTO planning (Nom_Planning, DateSession_Planning, HeureDebutSession_Planning)
        //             VALUES ('".$planningNameTemp."',NULL,NULL)"; //creer le "Planning-M1_huis_clos-N°XXX" en base
        //     $statement = $db->prepare($query);
        //     $statement->execute();

        //     $query = "SELECT ID_Planning FROM planning WHERE Nom_Planning = '".$planningNameTemp."'"; //recuperer l'id du planning créé.
        //     $statement = $db->query($query);
        //     $idPlanning = $statement->fetch();

        //     foreach($studentsList_M2_nonHuisClos as $student){
        //         $query = "UPDATE utilisateur SET ID_Planning = ".$idPlanning." WHERE ID_Utilisateur = ".$student['id_Utilisateur']."";
        //         $statement = $db->prepare($query);
        //         $statement->execute();
        //     }

            // if(count($studentsList_M2_nonHuisClos) > $nbStudentIntoSession){
            //     while ($cptStudentIntoPlanning != 0){ //plusieurs etudiants M1 possibles par planning si non huis clos en fonction du $nbStudentIntoSession recuperé
            //         $query = "UPDATE utilisateur SET ID_Planning = ".$idPlanning." WHERE ID_Utilisateur = ".$studentsList_M2_nonHuisClos[0]['id_Utilisateur']."";
            //         $statement = $db->prepare($query); //lier le planning à l'étudiant.
            //         $statement->execute();

            //         //ajouter le premier etudiant du tableau et passer au suivant en suppr le premier
            //         unset($studentsList_M2_nonHuisClos[array_search($studentsList_M2_nonHuisClos[0], $studentsList_M2_nonHuisClos)]);

            //         $cptStudentIntoPlanning--;

            //         //aies-je encore assez d'étudiant pour continuer la boucle ?
            //         if(count($studentsList_M2_nonHuisClos) < $nbStudentIntoSession){
            //             break;
            //         }
            //     }

            //     //je suis sortis de ma boucle while et je mets les étudiants restants dans mon planning restant
            //     if(count($studentsList_M2_nonHuisClos) < $nbStudentIntoSession){
            //         foreach($studentsList_M2_nonHuisClos as $student){
            //             $query = "UPDATE utilisateur SET ID_Planning = ".$idPlanning." WHERE ID_Utilisateur = ".$student['id_Utilisateur']."";
            //             $statement = $db->prepare($query); //lier le planning à l'étudiant.
            //             $statement->execute();
            //         }
            //         $studentsList_M2_nonHuisClos = array();
            //     }

            // }else{
            //     foreach($studentsList_M2_nonHuisClos as $student){
            //         $query = "UPDATE utilisateur SET ID_Planning = ".$idPlanning." WHERE ID_Utilisateur = ".$student['id_Utilisateur']."";
            //         $statement = $db->prepare($query); //lier le planning à l'étudiant.
            //         $statement->execute();
            //     }
            //     $studentsList_M2_nonHuisClos = array();
            // }
      //  }
    }

    header("Location: planning_scolarite.php");

    $db = Database::disconnect();

?>