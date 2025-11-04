<?php

    session_start();
    include_once '../../01_includes/dbconnect.php';

    $adding_user_lastname = $_POST['nom'];
    $adding_user_firstname = $_POST['prenom'];
    $adding_user_fullname=$adding_user_lastname." ".$adding_user_lastname;
    $adding_user_mail = $_POST['email'];
    $adding_user_site = $_POST['site'];
    $adding_user_profil = $_POST['fonction'];
    $adding_user_password = '';

    $query_profil = mysqli_query($database_connect, "SELECT * FROM site_embauche_table WHERE intitule_site = '$adding_user_site'");

    if ($query_profil) {
        $row = mysqli_fetch_assoc($query_profil);
        $adding_user_password = $row['pswd_default'];
        $query_addUser = mysqli_query($database_connect, "INSERT INTO users_table (id, user_firstname, user_lastname, user_fullname, user_role, user_email, user_pswd)
            VALUES (NULL, '$adding_user_firstname', '$adding_user_lastname', '$adding_user_fullname', '$adding_user_profil', '$adding_user_mail', '$adding_user_password')"
        );

        if($query_addUser){
                // LOGGING
                $log_dir = __DIR__ . '/../../01_logs/';
                if (!is_dir($log_dir)) { mkdir($log_dir, 0777, true); }
                $log_file = $log_dir . 'logs_' . date('Ymd') . '.txt';
                $user = isset($_SESSION['user_firstname']) ? $_SESSION['user_firstname'] . ' ' . $_SESSION['user_lastname'] : 'ANONYMOUS';
                $action = 'ADD_USER';
                $state = 'success';
                $dateheure = date('Y-m-d H:i:s');
                $log_entry = "$user:$action:$state:$dateheure\n";
                file_put_contents($log_file, $log_entry, FILE_APPEND);

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
            header('location: ../../01_scripts/ADMIN_PAGES/admin_systemeadmin.php?add_user=success');

        }else{
            echo "Erreur dans l'ajout de l'utilisateur.Veuillez reprendre.";
            //header("Location: ../../01_scripts/ADMIN_PAGES/admin_addNewUser.php");
        }
        
    }else{
        // Vérifie le code d'erreur MySQL
        if (mysqli_errno($database_connect) == 1062) {
            echo "Cet t'utilisateur existe déjà.";
            //header('location: ../../01_scripts/ADMIN_PAGES/admin_systemeadmin.php?add_user=duplicate');
        } else {
            echo "Erreur dans l'ajout de l'utilisateur.Veuillez reprendre.";
            //header('location: ../../01_scripts/ADMIN_PAGES/admin_systemeadmin.php?add_user=fail');
        }    
    }

?>