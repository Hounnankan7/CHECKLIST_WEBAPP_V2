<?php

    session_start();
    include_once '../../01_includes/dbconnect.php';

    $modifySite_query_id = $_GET['id_Site'];
    $intitule_site = $_POST['intitule_site'];
    $pswd_default = $_POST['site_pswd'];


    $modifySite_task = mysqli_query($database_connect, "UPDATE site_embauche_table SET intitule_site='$intitule_site' ,pswd_default='$pswd_default' WHERE id = '$modifySite_query_id'");

    if($modifySite_task){

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
        echo 'Erreur dans la modification des informations du Site.Veuillez reprendre.';
        header("Location: ../../01_scripts/ADMIN_PAGES/admin_systemeadmin.php?id_Site=$modifySite_query_id");
    }
?>