<?php

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    date_default_timezone_set('Europe/Paris');
    session_start();
    include_once '../01_includes/dbconnect.php';

    require '../01_includes/PHPMailer/src/Exception.php';
    require '../01_includes/PHPMailer/src/PHPMailer.php';
    require '../01_includes/PHPMailer/src/SMTP.php';



    $one_profil_id=$_SESSION['profil_modif_id'];
    $resultatP = mysqli_query($database_connect, "SELECT * FROM tasks_table WHERE id_employe = '$one_profil_id'");
    $profil_tasks_2 = $resultatP->fetch_assoc();

    $role = $_GET['role'];

    $b=$_SESSION['id_task'];
    $c=$_SESSION['id_employe'];

    //Recuperation infos du formulaire de modification checklist
    $attribution = $_POST['attribution'];

    $state_admin=0;
    $state_rh=0;
    $state_achat=0;
    $state_batiment=0;
    //$progression = 0;
    $admin_progression=0;
    $rh_progression=0;
    $achat_progression=0;
    $batiment_progression=0;

    if (isset($_POST['creation_mail'])) {
        $creation_mail = 'Terminée';
        $state_admin=$state_admin+1;
    }elseif ($profil_tasks_2['creation_mail']=="Pas besoin") {
        $creation_mail = 'Pas besoin';
        $state_admin=$state_admin+1;
    }else{
        $creation_mail = 'A faire';
    }

    $redirection_mail = 'Désactivé';

    if (isset($_POST['creation_rrf'])) {
        $creation_rrf = 'Terminée';
        $state_rh=$state_rh+1;
    }elseif ($profil_tasks_2['creation_rrf']=="Pas besoin") {
        $creation_rrf = 'Pas besoin';
        $state_rh=$state_rh+1;
    }else{
        $creation_rrf = 'A faire';
    }

    if (isset($_POST['creation_rlearning'])) {
        $creation_rlearning = 'Terminée';
        $state_rh=$state_rh+1;
    }elseif ($profil_tasks_2['creation_rlearning']=="Pas besoin") {
        $creation_rlearning = 'Pas besoin';
        $state_rh=$state_rh+1;
    }else{
        $creation_rlearning = 'A faire';
    }

    if (isset($_POST['creation_rcampus'])) {
        $creation_rcampus = 'Terminée';
        $state_rh=$state_rh+1;
    }elseif ($profil_tasks_2['creation_rcampus']=="Pas besoin") {
        $creation_rcampus = 'Pas besoin';
        $state_rh=$state_rh+1;
    }else{
        $creation_rcampus = 'A faire';
    }

    if (isset($_POST['creation_dcsnet'])) {
        $creation_dcsnet = 'Terminée';
        $state_admin=$state_admin+1;
        //$state=$state+1;
    }elseif ($profil_tasks_2['creation_dcsnet']=="Pas besoin") {
        $creation_dcsnet = 'Pas besoin';
        $state_admin=$state_admin+1;
    }else{
        $creation_dcsnet = 'A faire';
    }

    if (isset($_POST['creation_tocken'])) {
        $creation_tocken = 'Terminée';
        $state_admin=$state_admin+1;
    }elseif ($profil_tasks_2['creation_tocken']=="Pas besoin") {
        $creation_tocken = 'Pas besoin';
        $state_admin=$state_admin+1;
    }else{
        $creation_tocken = 'A faire';
    }

    if (isset($_POST['attribution_telephone'])) {
        $attribution_telephone = 'Terminée';
        $state_achat=$state_achat+1;
    }elseif ($profil_tasks_2['attribution_telephone']=="Pas besoin") {
        $attribution_telephone = 'Pas besoin';
        $state_achat=$state_achat+1;
    }else{
        $attribution_telephone = 'A faire';
    }

    if (isset($_POST['preaparation_pc'])) {
        $preaparation_pc = 'Terminée';
        $state_admin=$state_admin+1;
    }elseif ($profil_tasks_2['preaparation_pc']=="Pas besoin") {
        $preaparation_pc = 'Pas besoin';
        $state_admin=$state_admin+1;
    }else{
        $preaparation_pc = 'A faire';
    }

    if (isset($_POST['preparation_malette'])) {
        $preparation_malette = 'Terminée';
        $state_admin=$state_admin+1;
    }elseif ($profil_tasks_2['preparation_malette']=="Pas besoin") {
        $preparation_malette = 'Pas besoin';
        $state_admin=$state_admin+1;
    }else{
        $preparation_malette = 'A faire';
    }

    if (isset($_POST['preparation_imprimante'])) {
        $preparation_imprimante = 'Terminée';
        $state_admin=$state_admin+1;
    }elseif ($profil_tasks_2['preparation_imprimante']=="Pas besoin") {
        $preparation_imprimante = 'Pas besoin';
        $state_admin=$state_admin+1;
    }else{
        $preparation_imprimante = 'A faire';
    }

    if ($_POST['date_envoi']=="09-03-1999") {
        $date_envoi="1999-03-09";
    }elseif($_POST['date_envoi']!="09-03-1999") {
        $date_envoi=date('Y-m-d', strtotime($_POST['date_envoi']));
    }

    if (isset($_POST['attribution_vehicule'])) {
        $attribution_vehicule = 'Terminée';
        $state_achat=$state_achat+1;
    }elseif ($profil_tasks_2['attribution_vehicule']=="Pas besoin") {
        $attribution_vehicule = 'Pas besoin';
        $state_achat=$state_achat+1;
    }else{
        $attribution_vehicule = 'A faire';
    }

    if (isset($_POST['attribution_badge'])) {
        $attribution_badge = 'Terminée';
        $state_achat=$state_achat+1;
    }elseif ($profil_tasks_2['attribution_badge']=="Pas besoin") {
        $attribution_badge = 'Pas besoin';
        $state_achat=$state_achat+1;
    }else{
        $attribution_badge = 'A faire';
    }

    if (isset($_POST['attribution_cles'])) {
        $attribution_cles = 'Terminée';
        $state_batiment=$state_batiment+1;
    }elseif ($profil_tasks_2['attribution_cles']=="Pas besoin") {
        $attribution_cles = 'Pas besoin';
        $state_batiment=$state_batiment+1;
    }else{
        $attribution_cles = 'A faire';
    }

    if (isset($_POST['attribution_vetements'])) {
        $attribution_vetements = 'Terminée';
        $state_achat=$state_achat+1;
    }elseif ($profil_tasks_2['attribution_vetements']=="Pas besoin") {
        $attribution_vetements = 'Pas besoin';
        $state_achat=$state_achat+1;
    }else{
        $attribution_vetements = 'A faire';
    }

    if (isset($_POST['attribution_chaussures'])) {
        $attribution_chaussure = 'Terminée';
        $state_achat=$state_achat+1;
    }elseif ($profil_tasks_2['attribution_chaussures']=="Pas besoin") {
        $attribution_chaussure = 'Pas besoin';
        $state_achat=$state_achat+1;
    }else{
        $attribution_chaussure = 'A faire';
    }

    if (isset($_POST['validation'])) {
        $validation = 'Valide';
    }else {
        $validation = 'En attente';
    }

    $admin_progression=($state_admin*100)/15;
    $rh_progression=($state_rh*100)/15;
    $batiment_progression=($state_batiment*100)/15;
    $achat_progression=($state_achat*100)/15;

            /*$task_data = mysqli_query($database_connect, 
            "UPDATE tasks_table
            SET  id_task='$b',
            id_employe='$c',
            admin_progression='$admin_progression',
            rh_progression='$rh_progression',
            achat_progression='$achat_progression',
            batiment_progression='$batiment_progression' WHERE id_task='$b';"
        );*/



    if (strcasecmp($role, "Informaticien") === 0) {
        if (empty($_POST['attribution'])) {
            // Si aucune nouvelle valeur n'est envoyée, conserver l'ancienne valeur
            $attribue_a_query = mysqli_query($database_connect, "SELECT attribue_a FROM tasks_table WHERE id_task = '$b'");
            $attribue_a_result = $attribue_a_query->fetch_assoc();
            $technician_name = $attribue_a_result['attribue_a'];
        } else {
            // Sinon, récupérer le nom du technicien sélectionné
            $technician_query = mysqli_query($database_connect, "SELECT CONCAT(user_firstname, ' ', user_lastname) AS full_name FROM users_table WHERE id = '".$_POST['attribution']."'");
            $technician = $technician_query->fetch_assoc();
            $technician_name = $technician['full_name'];
        }

        $task_data = mysqli_query($database_connect, 
            "UPDATE tasks_table
            SET  id_task='$b',
            id_employe='$c',
            creation_mail='$creation_mail',
            redirection_mail='$redirection_mail',
            creation_dcsnet='$creation_dcsnet',
            creation_tocken='$creation_tocken',
            attribution_telephone='$attribution_telephone',
            preaparation_pc='$preaparation_pc',
            preparation_malette='$preparation_malette',
            preparation_imprimante='$preparation_imprimante',
            admin_progression='$admin_progression',
            attribue_a='$technician_name',
            nom_pc='',
            caracteristique='',
            date_envoi='$date_envoi' WHERE id_task='$b';"
        );

        $resultat_progress = mysqli_query($database_connect, "SELECT * FROM tasks_table WHERE id_task = '$b'");
        $tasks_progress = $resultat_progress->fetch_assoc();

        $progression = $tasks_progress['rh_progression'] + $tasks_progress['admin_progression']+ $tasks_progress['achat_progression'] + $tasks_progress['batiment_progression'];

        if ($progression == 100) {
            $date_cloture = date('Y-m-d');
        }else{
            $date_cloture = '1999-03-09';
        }

        $task_data = mysqli_query($database_connect, "UPDATE tasks_table 
        SET id_task='$b',id_employe='$c', progression='$progression' WHERE id_task='$b';");


    }elseif (strcasecmp($role, "RH") === 0) {

        $task_data = mysqli_query($database_connect, 
            "UPDATE tasks_table
            SET id_task='$b',
            id_employe='$c',
            creation_rrf='$creation_rrf',
            creation_rlearning='$creation_rlearning',
            creation_rcampus='$creation_rcampus',
            rh_progression='$rh_progression',
            etat_rh='$validation' WHERE id_task='$b';"
        );

        $resultat_progress = mysqli_query($database_connect, "SELECT * FROM tasks_table WHERE id_task = '$b'");
        $tasks_progress = $resultat_progress->fetch_assoc();

        $progression = $tasks_progress['rh_progression'] + $tasks_progress['admin_progression']+ $tasks_progress['achat_progression'] + $tasks_progress['batiment_progression'];

        if ($progression == 100) {
            $date_cloture = date('Y-m-d');
        }else{
            $date_cloture = '1999-03-09';
        }

        $task_data = mysqli_query($database_connect, "UPDATE tasks_table 
        SET id_task='$b',id_employe='$c', progression='$progression' WHERE id_task='$b';");

    }elseif (strcasecmp($role, "achat") === 0) {

        $task_data = mysqli_query($database_connect, 
            "UPDATE tasks_table
            SET id_task='$b',
            id_employe='$c',
            attribution_telephone='$attribution_telephone',
            attribution_vehicule='$attribution_vehicule',
            attribution_badge='$attribution_badge',
            attribution_vetements='$attribution_vetements',
            attribution_chaussures='$attribution_chaussure',
            achat_progression='$achat_progression' WHERE id_task='$b';"
        );

        $resultat_progress = mysqli_query($database_connect, "SELECT * FROM tasks_table WHERE id_task = '$b'");
        $tasks_progress = $resultat_progress->fetch_assoc();

        $progression = $tasks_progress['rh_progression'] + $tasks_progress['admin_progression']+ $tasks_progress['achat_progression'] + $tasks_progress['batiment_progression'];

        if ($progression == 100) {
            $date_cloture = date('Y-m-d');
        }else{
            $date_cloture = '1999-03-09';
        }

        $task_data = mysqli_query($database_connect, "UPDATE tasks_table 
        SET id_task='$b',id_employe='$c', progression='$progression' WHERE id_task='$b';");

    }elseif (strcasecmp($role, "batiment") === 0) {

        $task_data = mysqli_query($database_connect, 
            "UPDATE tasks_table
            SET id_task='$b',
            id_employe='$c',
            attribution_cles='$attribution_cles',
            batiment_progression='$batiment_progression' WHERE id_task='$b';"
        );

        $resultat_progress = mysqli_query($database_connect, "SELECT * FROM tasks_table WHERE id_task = '$b'");
        $tasks_progress = $resultat_progress->fetch_assoc();

        $progression = $tasks_progress['rh_progression'] + $tasks_progress['admin_progression']+ $tasks_progress['achat_progression'] + $tasks_progress['batiment_progression'];

        if ($progression == 100) {
            $date_cloture = date('Y-m-d');
        }else{
            $date_cloture = '1999-03-09';
        }

        $task_data = mysqli_query($database_connect, "UPDATE tasks_table 
        SET id_task='$b',id_employe='$c', progression='$progression' WHERE id_task='$b';");

    }

    // Vérification si la requête a réussi
    if($task_data){

        if (strcasecmp($role, "RH") === 0) {

            $date_arrive_query = mysqli_query($database_connect, "SELECT * FROM new_employee_table WHERE id_employe='$c'");
            $date_arrive_result = $date_arrive_query->fetch_assoc();
            $date_arrive = $date_arrive_result['date_embauche'];
            $date_arrive2 = date('d-m-Y', strtotime($date_arrive));
            $date_naissance = $date_arrive_result['date_naissance'];
            $date_naissance2 = date('d-m-Y', strtotime($date_naissance));

            if(isset($_POST['validation']) && $profil_tasks_2['attribue_a'] == "" && $profil_tasks_2['etat_rh'] != 'Valide'){
                
                // Chemin du fichier de configuration
                $configFile = '../01_configFiles/phpmailer_infoNotif.txt';

                // Lire le fichier et parser les paramètres
                $config = [];
                if (file_exists($configFile)) {
                    $lines = file($configFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                    
                    foreach ($lines as $line) {
                        // Ignorer les commentaires
                        if (strpos(trim($line), '#') === 0) continue;
                        
                        // Séparer clé/valeur
                        list($key, $value) = explode('=', $line, 2);
                        
                        // Nettoyer et stocker dans le tableau
                        $key = trim($key);
                        $value = trim($value, " \t\n\r\0\x0B'"); // Enlever les quotes et espaces
                        $config[$key] = $value;
                    }
                } else {
                    die("Fichier de configuration '01_configFiles/phpmailer_infoNotif.txt' introuvable !");
                }
           
                $mail = new PHPMailer(true);
            
                try {

                    $mail->CharSet = 'UTF-8';
                    // Configuration SMTP
                    $mail->isSMTP();
                    $mail->Host       = $config['host'];
                    $mail->SMTPAuth   = true;
                    $mail->Username   = $config['username'];
                    $mail->Password   = $config['password'];
                    $mail->SMTPSecure = $config['smtpsecure'];
                    $mail->Port       = $config['port'];
            
                    // Expéditeur
                    $mail->setFrom($config['username']);
            
                    // Destinataire unique
                    $mail->addAddress($config['receiveraddress']);
                    $mail->addAddress($config['receiveraddress2']);
                    $mail->addAddress($config['receiveraddress3']);
            
                    // Contenu
                    $mail->isHTML(true);
                    $mail->Subject = "Entrée du $date_arrive2 validée";
                    $mail->Body    = "<b>Bonjour à tous,</b><br>Je vous informe d'une entrée validée par le service RH :<br> ".stripslashes($date_arrive_result['user_lastname'])." ".stripslashes($date_arrive_result['user_firstname'])." ( ".$date_naissance2." ), "."en qualité de ".stripslashes($date_arrive_result['intitule_poste'])." sur le site de ".stripslashes($date_arrive_result['site_embauche']).", à compter du $date_arrive2.";
                    $mail->send();

                    header('location: RH_PAGES/rh_homepage.php?send_checklist=success');

                } catch (Exception $e) {
                    echo "Erreur : {$mail->ErrorInfo}<br>";
                   header('location: RH_PAGES/rh_homepage.php?send_checklist=error&message=Erreur: {'.$mail->ErrorInfo.'}<br>');
                }

                // Envoi de mail au service bâtiment si attribution des clés est "A faire"
                if ($attribution_cles == 'A faire') {
                    
                    // Récupérer les utilisateurs avec le profil "batiment"
                    $batiment_users_query = mysqli_query($database_connect, "SELECT user_email FROM users_table WHERE user_role = 'batiment'");
                    
                    if (mysqli_num_rows($batiment_users_query) > 0) {
                        $mail_batiment = new PHPMailer(true);
                        
                        try {
                            $mail_batiment->CharSet = 'UTF-8';
                            // Configuration SMTP
                            $mail_batiment->isSMTP();
                            $mail_batiment->Host       = $config['host'];
                            $mail_batiment->SMTPAuth   = true;
                            $mail_batiment->Username   = $config['username'];
                            $mail_batiment->Password   = $config['password'];
                            $mail_batiment->SMTPSecure = $config['smtpsecure'];
                            $mail_batiment->Port       = $config['port'];
                    
                            // Expéditeur
                            $mail_batiment->setFrom($config['username']);
                    
                            // Ajouter tous les utilisateurs bâtiment comme destinataires
                            while ($batiment_user = $batiment_users_query->fetch_assoc()) {
                                $mail_batiment->addAddress($batiment_user['user_email']);
                            }
                    
                            // Contenu
                            $mail_batiment->isHTML(true);
                            $mail_batiment->Subject = "Attribution de clés requise - Nouvelle entrée";
                            $mail_batiment->Body    = "<b>Bonjour,</b><br><br>Une attribution de clés est requise pour le nouveau collaborateur :<br> ".stripslashes($date_arrive_result['user_lastname'])." ".stripslashes($date_arrive_result['user_firstname'])." ( ".$date_naissance2." ), "."en qualité de ".stripslashes($date_arrive_result['intitule_poste'])." sur le site de ".stripslashes($date_arrive_result['site_embauche']).", à compter du $date_arrive2.<br><br>Merci de traiter cette demande dans les meilleurs délais.";
                            
                            $mail_batiment->send();
                            
                        } catch (Exception $e) {
                            // Log l'erreur mais ne pas interrompre le processus principal
                            error_log("Erreur envoi mail bâtiment : {$mail_batiment->ErrorInfo}");
                        }
                    }
                }

                // Envoi de mail au service achat si des éléments sont "A faire"
                $achat_elements_a_faire = [];
                if ($attribution_telephone == 'A faire') {
                    $achat_elements_a_faire[] = "Attribution d'un téléphone";
                }
                if ($attribution_vehicule == 'A faire') {
                    $achat_elements_a_faire[] = "Attribution d'un véhicule";
                }
                if ($attribution_badge == 'A faire') {
                    $achat_elements_a_faire[] = "Attribution d'un badge";
                }
                if ($attribution_vetements == 'A faire') {
                    $achat_elements_a_faire[] = 'Attribution de vêtements';
                }
                if ($attribution_chaussure == 'A faire') {
                    $achat_elements_a_faire[] = 'Attribution de chaussures';
                }

                if (!empty($achat_elements_a_faire)) {
                    
                    // Récupérer les utilisateurs avec le profil "achat"
                    $achat_users_query = mysqli_query($database_connect, "SELECT user_email FROM users_table WHERE user_role = 'achat'");
                    
                    if (mysqli_num_rows($achat_users_query) > 0) {
                        $mail_achat = new PHPMailer(true);
                        
                        try {
                            $mail_achat->CharSet = 'UTF-8';
                            // Configuration SMTP
                            $mail_achat->isSMTP();
                            $mail_achat->Host       = $config['host'];
                            $mail_achat->SMTPAuth   = true;
                            $mail_achat->Username   = $config['username'];
                            $mail_achat->Password   = $config['password'];
                            $mail_achat->SMTPSecure = $config['smtpsecure'];
                            $mail_achat->Port       = $config['port'];
                    
                            // Expéditeur
                            $mail_achat->setFrom($config['username']);
                    
                            // Ajouter tous les utilisateurs achat comme destinataires
                            while ($achat_user = $achat_users_query->fetch_assoc()) {
                                $mail_achat->addAddress($achat_user['user_email']);
                            }
                    
                            // Création de la liste des éléments à traiter
                            $liste_elements = '<ul>';
                            foreach ($achat_elements_a_faire as $element) {
                                $liste_elements .= '<li>' . $element . '</li>';
                            }
                            $liste_elements .= '</ul>';
                    
                            // Contenu
                            $mail_achat->isHTML(true);
                            $mail_achat->Subject = "Éléments achat requis - Nouvelle entrée";
                            $mail_achat->Body    = "<b>Bonjour,</b><br><br>Les éléments suivants sont à traiter par le service achat pour le nouveau collaborateur :<br><br><strong>Employé :</strong> ".stripslashes($date_arrive_result['user_lastname'])." ".stripslashes($date_arrive_result['user_firstname'])." ( ".$date_naissance2." )<br><strong>Poste :</strong> ".stripslashes($date_arrive_result['intitule_poste'])."<br><strong>Site :</strong> ".stripslashes($date_arrive_result['site_embauche'])."<br><strong>Date d'arrivée :</strong> $date_arrive2<br><br><strong>Éléments à traiter :</strong><br>" . $liste_elements . "<br>Merci de traiter ces demandes dans les meilleurs délais.";
                            
                            $mail_achat->send();
                            
                        } catch (Exception $e) {
                            // Log l'erreur mais ne pas interrompre le processus principal
                            error_log("Erreur envoi mail achat : {$mail_achat->ErrorInfo}");
                        }
                    }
                }

            }elseif(isset($_POST['validation']) && $profil_tasks_2['attribue_a'] != "" ){
    
            }
            header('location: RH_PAGES/rh_homepage.php?send_checklist=success');
        }elseif (strcasecmp($role, "Informaticien") === 0) {
            header('location: ADMIN_PAGES/admin_homepage.php?send_checklist=success');
        }elseif (strcasecmp($role, "Achat") === 0) {
            header('location: ACHAT_PAGES/achat_homepage.php?send_checklist=success');
        }elseif (strcasecmp($role, "Batiment") === 0) {
            header('location: BATIMENT_PAGES/batiment_homepage.php?send_checklist=success');
        }
      
        //echo $progression;

    }else{
    
        if (strcasecmp($role, "RH") === 0) {
            $error_message= mysqli_error($database_connect);
            header('location: RH_PAGES/rh_homepage.php?send_checklist=error&message='.urlencode($error_message));
            //echo $error_message;
        }elseif (strcasecmp($role, "Informaticien") === 0) {
            $error_message= mysqli_error($database_connect);
            header('location: ADMIN_PAGES/admin_homepage.php?send_checklist=error&message='.urlencode($error_message));
            //echo $error_message;
        }elseif (strcasecmp($role, "Achat") === 0) {
            $error_message= mysqli_error($database_connect);
            header('location: ACHAT_PAGES/achat_homepage.php?send_checklist=error&message='.urlencode($error_message));
            //echo $error_message;
        }elseif (strcasecmp($role, "Batiment") === 0) {
            $error_message= mysqli_error($database_connect);
            header('location: BATIMENT_PAGES/batiment_homepage.php?send_checklist=error&message='.urlencode($error_message));
            //echo $error_message;
        }
    }
    mysqli_close($database_connect);

?>
