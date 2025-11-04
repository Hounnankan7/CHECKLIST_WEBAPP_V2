<?php

    session_start();
    include_once '../../01_includes/dbconnect.php';

    $modifyUser_query_id = $_GET['id_User'];
    $user_firstname = $_POST['user_prenom'];
    $user_lastname = $_POST['user_nom'];
    $user_fullname = $user_lastname.' '.$user_firstname;
    $user_role = $_POST['fonction'];
    $user_email = $_POST['user_email'];
    $user_pswd = $_POST['user_pswd'];

    $modifyUser_task = mysqli_query($database_connect, "UPDATE users_table SET user_firstname='$user_firstname',user_lastname='$user_lastname',
        user_fullname='$user_fullname',user_role='$user_role',user_email='$user_email',user_pswd='$user_pswd' WHERE id = '$modifyUser_query_id'"
    );

    if($modifyUser_task){

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
        echo "Erreur dans la modification des informations de l'utilisateur.Veuillez reprendre.";
        header("Location: ../../01_scripts/ADMIN_PAGES/admin_systemeadmin.php?id_Service=$modifyUser_query_id");
    }
?>