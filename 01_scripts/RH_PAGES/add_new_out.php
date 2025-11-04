<?php

    session_start();
    include_once '../../01_includes/dbconnect.php';

    $date_sortie = $_POST['out_date'];
    $societe = mb_strtoupper($_POST['s_e'], 'UTF-8');
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $contrat = mb_strtoupper($_POST['contrat'], 'UTF-8');
    $fonction = mb_strtoupper($_POST['fonction'], 'UTF-8');
    $service = mb_strtoupper($_POST['service'], 'UTF-8');
    $type_service = $_POST['sortie_type'];

    // Vérification si out users existe dans la bdd
    $query_check = mysqli_query($database_connect, "SELECT * FROM new_employee_table WHERE user_firstname = '$prenom' AND user_lastname = '$nom' AND site_embauche = '$societe'");
    if (mysqli_num_rows($query_check) > 0) {
        // L'utilisateur existe déjà
        while($row = mysqli_fetch_assoc($query_check)){
           
            if ($row['besoin_mail'] == "Oui") {
                $recup_mail = "A faire";
            } elseif ($row['besoin_mail'] == "Non") {
                $recup_mail = "Terminé";
            }

            if ($row['besoin_tocken'] == "Oui") {
                $recup_tocken = "A faire";
            } elseif ($row['besoin_tocken'] == "Non") {
                $recup_tocken = "Terminé";
            }

            if ($row['besoin_dcs_net'] == "Oui") {
                $recup_dcs = "A faire";
            } elseif ($row['besoin_dcs_net'] == "Non") {
                $recup_dcs = "Terminé";
            }

            if ($row['besoin_portable_pro'] == "Oui") {
                $recup_telephone = "A faire";
            } elseif ($row['besoin_portable_pro'] == "Non") {
                $recup_telephone = "Terminé";
            }

            if ($row['besoin_pc'] == "Oui") {
                $recup_pc = "A faire";
            } elseif ($row['besoin_pc'] == "Non") {
                $recup_pc = "Terminé";
            }

            if ($row['besoin_malette_pc'] == "Oui") {
                $recup_malette = "A faire";
            } elseif ($row['besoin_malette_pc'] == "Non") {
                $recup_malette = "Terminé";
            }

            if ($row['besoin_imprimante'] == "Oui") {
                $recup_imp = "A faire";
            } elseif ($row['besoin_imprimante'] == "Non") {
                $recup_imp = "Terminé";
            }

            if ($row['besoin_vehicule'] == "Oui") {
                $recup_vehicule = "A faire";
            } elseif ($row['besoin_vehicule'] == "Non") {
                $recup_vehicule = "Terminé";
            }
            if ($row['besoin_badge_alarme'] == "Oui") {
                $recup_badge = "A faire";
            } elseif ($row['besoin_badge_alarme'] == "Non") {
                $recup_badge = "Terminé";
            }

            if ($row['besoin_cle_batiments'] == "Oui") {
                $recup_cle = "A faire";
            } elseif ($row['besoin_cle_batiments'] == "Non") {
                $recup_cle = "Terminé";
            }
        }

        $query_out = mysqli_query($database_connect, "INSERT INTO sorties_table (id_sortie, firstname, lastname, date_sortie, societe, services, contrat, fonction, type_sortie, 
            `out_mail`, `out_tocken`, `out_dcsnet`, `out_telephone`, `out_pc`, `out_malette`, `out_badge`, `out_cle`, `out_imprimante`, `out_vehicule`, `state_equipement`,`state_achat`, `state_batiment`)
            VALUES (NULL, '$nom', '$prenom', '$date_sortie', '$societe', '$service', '$contrat', '$fonction', '$type_service', 
            '$recup_mail', '$recup_tocken', '$recup_dcs', '$recup_telephone', '$recup_pc', '$recup_malette', '$recup_badge', '$recup_cle', '$recup_imp', '$recup_vehicule', 0, 0, 0)"
        );    


    }else{
        // L'utilisateur n'existe pas, on peut l'ajouter

        $query_out = mysqli_query($database_connect, "INSERT INTO sorties_table (id_sortie, firstname, lastname, date_sortie, societe, services, contrat, fonction, type_sortie, 
            `out_mail`, `out_tocken`, `out_dcsnet`, `out_telephone`, `out_pc`, `out_malette`, `out_badge`, `out_cle`, `out_imprimante`, `out_vehicule`, `state_equipement`, `state_achat`, `state_batiment`)
            VALUES (NULL, '$nom', '$prenom', '$date_sortie', '$societe', '$service', '$contrat', '$fonction', '$type_service', 
            'A verifier', 'A verifier', 'A verifier', 'A verifier', 'A verifier', 'A verifier', 'A verifier', 'A verifier', 'A verifier', 'A verifier', 0, 0, 0)"
        );
    }


    // LOGGING
    $log_dir = __DIR__ . '/../../01_logs/';
    if (!is_dir($log_dir)) { mkdir($log_dir, 0777, true); }
    $log_file = $log_dir . 'logs_' . date('Ymd') . '.txt';
    $user = isset($_SESSION['user_firstname']) ? $_SESSION['user_firstname'] . ' ' . $_SESSION['user_lastname'] : 'ANONYMOUS';
    $action = 'ADD_OUT';
    $state = $query_check ? 'success' : 'failed';
    $dateheure = date('Y-m-d H:i:s');
    $log_entry = "$user:$action:$state:$dateheure\n";
    file_put_contents($log_file, $log_entry, FILE_APPEND);

    if ($query_out) {
        header('location: ../../01_scripts/RH_PAGES/rh_outmanager.php?add_out=success');
    }else{
        // Vérifie le code d'erreur MySQL
        if (mysqli_errno($database_connect) == 1062) {
            header('location: ../../01_scripts/RH_PAGES/rh_outmanager.php?add_out=duplicate');
        } else {
            header('location: ../../01_scripts/RH_PAGES/rh_outmanager.php?add_out=fail');
        }    
    }

?>