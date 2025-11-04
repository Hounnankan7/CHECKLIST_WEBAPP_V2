<?php

    session_start();
    include_once '../../01_includes/dbconnect.php';

    $adding_post_rnqsa = $_POST['rnqsa'];
    $adding_post_categorie = mb_strtoupper($_POST['categorie_name'], 'UTF-8');
    $adding_post_intitule = $_POST['poste_name'];

    $query_poste = mysqli_query($database_connect, "INSERT INTO fiche_poste_table (id, categorie, intitule_poste, fiche_rnqsa) 
        VALUES (NULL, '$adding_post_categorie', '$adding_post_intitule', '$adding_post_rnqsa')");
    

    // LOGGING
    $log_dir = __DIR__ . '/../../01_logs/';
    if (!is_dir($log_dir)) { mkdir($log_dir, 0777, true); }
    $log_file = $log_dir . 'logs_' . date('Ymd') . '.txt';
    $user = isset($_SESSION['user_firstname']) ? $_SESSION['user_firstname'] . ' ' . $_SESSION['user_lastname'] : 'ANONYMOUS';
    $action = 'ADD_POSTE';
    $state = $query_poste ? 'success' : 'failed';
    $dateheure = date('Y-m-d H:i:s');
    $log_entry = "$user:$action:$state:$dateheure\n";
    file_put_contents($log_file, $log_entry, FILE_APPEND);


    if ($query_poste) {
        header('location: ../../01_scripts/RH_PAGES/rh_add_poste.php?add_poste=success');
    }else{
        // Vérifie le code d'erreur MySQL
        if (mysqli_errno($database_connect) == 1062) {
            header('location: ../../01_scripts/RH_PAGES/rh_add_poste.php?add_poste=duplicate');
        } else {
            header('location: ../../01_scripts/RH_PAGES/rh_add_poste.php?add_poste=fail');
        }    
    }

?>