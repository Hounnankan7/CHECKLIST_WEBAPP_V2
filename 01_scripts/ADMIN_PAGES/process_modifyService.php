<?php

    session_start();
    include_once '../../01_includes/dbconnect.php';

    $modifyService_query_id = $_GET['id_Service'];
    $intitule_Service = $_POST['intitule_service'];

    $modifyService_task = mysqli_query($database_connect, "UPDATE services_table SET intitule_service='$intitule_Service' WHERE id = '$modifyService_query_id'");

    // LOGGING
    $log_dir = __DIR__ . '/../../01_logs/';
    if (!is_dir($log_dir)) { mkdir($log_dir, 0777, true); }
    $log_file = $log_dir . 'logs_' . date('Ymd') . '.txt';
    $user = isset($_SESSION['user_firstname']) ? $_SESSION['user_firstname'] . ' ' . $_SESSION['user_lastname'] : 'ANONYMOUS';
    $action = 'MODIFY_SERVICE';
    $state = $modifyService_task ? 'success' : 'failed';
    $dateheure = date('Y-m-d H:i:s');
    $log_entry = "$user:$action:$state:$dateheure\n";
    file_put_contents($log_file, $log_entry, FILE_APPEND);

    if($modifyService_task){

        echo '<!DOCTYPE html>
            <html lang="fr">
            <head>
            <meta charset="UTF-8">
            <title>Traitement terminé</title>
            </head>
                <body>
                    <p>Formulaire envoyé avec succès ! La fenêtre va se fermer automatiquement.</p>
                    <script>
                        // Ferme la fenêtre après 2 secondes (2000 millisecondes)
                        setTimeout(function() {
                            // Si la fenêtre parente existe, on la rafraîchit
                            if (window.opener && !window.opener.closed) {
                                window.opener.location.reload();
                            }
                            window.close();
                        }, 2000);
                    </script>
                </body>
            </html>
        ';

    }else{
        echo 'Erreur dans la modification des informations du Service.Veuillez reprendre.';
        header("Location: ../../01_scripts/ADMIN_PAGES/admin_systemeadmin.php?id_Service=$modifyService_query_id");
    }
?>