<?php
    session_start();
    include_once '../01_includes/dbconnect.php';
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>SUIVI CHECK LIST</title>

        <!-- Telechargement de bootstrap et bootstrap icon -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
        <script src="https://kit.fontawesome.com/857f401658.js" crossorigin="anonymous"></script>
        <link rel="shortcut icon" type="image/png" href="../01_assets/faviconV2.png"/>

        <style>

            /* Centrage des icônes au-dessus des liens */
            .navbar-nav .nav-item .nav-link {
                display: flex;
                flex-direction: column;
                align-items: center;
                text-align: center;
                margin-top: 14px;
            }

            /* Ajout d'un espacement entre les icônes et le texte */
            .navbar-nav .nav-item .nav-link i {
                margin-bottom: 10px; /* Ajustez la valeur selon vos besoins */
            }

            /* Ajout d'un espacement entre les icônes et le texte */
            .navbar-nav .nav-item .nav-link i {
                margin-bottom: 10px; /* Ajustez la valeur selon vos besoins */
            }
    
            section {
                max-width: 80%;
                padding: 20px;
                background: white;
                border-radius: 10px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                margin-top: 30px;
                margin-left: 10%;
                margin-right: 10%;
            }
            
            footer{
                width: 100%;
                padding: 10px;
                background-color: #343a40;
                color: white;
                text-align: center;
                bottom: 0;
                margin-top: 40px;
            }

        </style>

    </head>

    <body>

        <?php 
            // Chemin du fichier de configuration
            $configFile = '../01_configFiles/databaseLink.txt';

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
                die("Fichier de configuration '01_configFiles/databaseLink.txt' introuvable !");
            }

            $role_actuel=$_GET['role'];
            $_SESSION['profil_modif_id']=$_GET['id_profil'];
            $id_profil_open=$_GET['id_profil'];

            $_SESSION['sub_id']=$id_profil_open;

            if (isset($_GET['id_profil'])) {
                $resultat_tasks = mysqli_query($database_connect, "SELECT * FROM tasks_table WHERE id_employe = '$id_profil_open'");
                $resultat_infoEmploye = mysqli_query($database_connect, "SELECT * FROM new_employee_table WHERE id_employe = '$id_profil_open'");
                $profil_tasks = $resultat_tasks->fetch_assoc();
                $profil = $resultat_infoEmploye->fetch_assoc();
            }
    
            

            if (strcasecmp($role_actuel, "RH") === 0) {
                $resultatR = mysqli_query($database_connect, "SELECT * FROM users_table WHERE user_role = 'RH'");
            }elseif (strcasecmp($role_actuel, "Informaticien") === 0) {
                $resultatR = mysqli_query($database_connect, "SELECT * FROM users_table WHERE user_role = 'Informaticien'");
            }    

            $_SESSION['id_task']=$profil_tasks['id_task'];
            $_SESSION['id_employe']=$profil_tasks['id_employe'];
            $_SESSION['progression']=$profil_tasks['progression'];    
        ?>

        <!--Entete de la page-->
        <header class="sticky-top">
            <?php
                //Afficher la barre de menu en fonction du role de l'utilisateur connecter
                if (strcasecmp($role_actuel, "rh") === 0) {

                    echo '<nav class="navbar navbar-expand-lg navbar-dark bg-dark">';
                        echo '<div class="container-fluid">';
                            
                            echo '<a class="navbar-brand" href="#" style="width: 20%;"><img class="logo" src="../01_assets/LOGO_BLANC.png" alt="JCMI" title="JCMI" width=60% height=30%></a>';

                            echo '<ul class="navbar-nav">';
                                echo '<li class="nav-item"><a class="nav-link active" href="RH_PAGES/rh_add_poste.php"><i class="fa-solid fa-file-pen fa-xl"></i> Fiches RNQSA</a></li>';
                                echo '<li class="nav-item"><a class="nav-link active" href="RH_PAGES/rh_add_checklist.php"><i class="fa-solid fa-person-through-window fa-xl"></i> Ajouter Entrée</a></li>';
                                echo '<li class="nav-item"><a class="nav-link active" href="RH_PAGES/rh_homepage.php"><i class="fa-solid fa-person-walking-arrow-right fa-xl"></i> Suivi Entrées</a></li>';
                                echo '<li class="nav-item"><a class="nav-link active" href="RH_PAGES/rh_closedentry.php"><i class="fa-solid fa-list-check fa-xl"></i> Entrées Terminées</a></li>';
                                echo '<li class="nav-item"><a class="nav-link active" href="RH_PAGES/rh_outmanager.php"><i class="fa-solid fa-person-walking-arrow-right fa-flip-horizontal fa-xl"></i> Gestion des Sorties</a></li>';
                                echo '<li class="nav-item"><a class="nav-link active" href="deconnexion.php"><i class="fa-solid fa-light fa-power-off fa-xl"></i> Déconnexion</a></li>';
                            echo '</ul>';

                        echo '</div>';
                    echo '</nav>';

                } elseif (strcasecmp($role_actuel, "Informaticien") === 0) {
                    $db_link = $config['db_link'];

                    echo '<nav class="navbar navbar-expand-lg navbar-dark bg-dark">';
                        echo '<div class="container-fluid">';
                            
                            echo '<a class="navbar-brand" href="#" style="width: 20%;"><img class="logo" src="../01_assets/LOGO_BLANC.png" alt="JCMI" title="JCMI" width=60% height=30%></a>';

                            echo '<ul class="navbar-nav">';
                                echo '<li class="nav-item"><a class="nav-link active" href="ADMIN_PAGES/admin_systemeadmin.php"><i class="fa-solid fa-user-gear fa-xl"></i>Administration Système</a></li>';
                                echo '<li class="nav-item"><a class="nav-link active" href="ADMIN_PAGES/admin_homepage.php"><i class="fa-solid fa-person-walking-arrow-right fa-xl"></i> Suivi Entrées</a></li>';
                                echo '<li class="nav-item"><a class="nav-link active" href="ADMIN_PAGES/admin_closedentry.php"><i class="fa-solid fa-list-check fa-xl"></i> Entrées Terminées</a></li>';
                                echo '<li class="nav-item"><a class="nav-link active" href="ADMIN_PAGES/admin_outmanager.php"><i class="fa-solid fa-person-walking-arrow-right fa-flip-horizontal fa-xl"></i>Sorties & Retours Matériels</a></li>';
                                echo '<li class="nav-item"><a class="nav-link active" href="'.$db_link.'" target="_blank"><i class="fa-solid fa-database fa-xl"></i>Administration BDD</a></li>';
                                echo '<li class="nav-item"><a class="nav-link active" href="deconnexion.php"><i class="fa-solid fa-light fa-power-off fa-xl"></i> Déconnexion</a></li>';
                            echo '</ul>';

                        echo '</div>';
                    echo '</nav>';

                } elseif (strcasecmp($role_actuel, "achat") === 0) {
                    echo '<header class="sticky-top">';
                        echo '<nav class="navbar navbar-expand-lg navbar-dark bg-dark">';
                            echo '<div class="container-fluid">';
                                
                                echo '<a class="navbar-brand" href="#" style="width: 20%;"><img class="logo" src="../01_assets/LOGO_BLANC.png" alt="JCMI" title="JCMI" width=60% height=30%></a>';

                                echo '<ul class="navbar-nav">';
                                    echo '<li class="nav-item"><a class="nav-link active" href="ACHAT_PAGES/achat_add_checklist.php"><i class="fa-solid fa-person-through-window fa-xl"></i> Ajouter Entrée</a></li>';
                                    echo '<li class="nav-item"><a class="nav-link active" href="ACHAT_PAGES/achat_homepage.php"><i class="fa-solid fa-person-walking-arrow-right fa-xl"></i> Suivi Entrées</a></li>';
                                    echo '<li class="nav-item"><a class="nav-link active" href="ACHAT_PAGES/achat_closedentry.php"><i class="fa-solid fa-list-check fa-xl"></i> Entrées Terminées</a></li>';
                                    echo '<li class="nav-item"><a class="nav-link active" href="ACHAT_PAGES/achat_outmanager.php"><i class="fa-solid fa-person-walking-arrow-right fa-flip-horizontal fa-xl"></i> Suivi Sorties</a></li>';
                                    echo '<li class="nav-item"><a class="nav-link active" href="deconnexion.php"><i class="fa-solid fa-light fa-power-off fa-xl"></i> Déconnexion</a></li>';
                                echo '</ul>';

                            echo '</div>';
                        echo '</nav>';
                    echo '</header>';

                } elseif (strcasecmp($role_actuel, "batiment") === 0) {
                    echo '<header class="sticky-top">';
                    echo '<nav class="navbar navbar-expand-lg navbar-dark bg-dark">';
                        echo '<div class="container-fluid">';
                            
                            echo '<a class="navbar-brand" href="#" style="width: 20%;"><img class="logo" src="../01_assets/LOGO_BLANC.png" alt="JCMI" title="JCMI" width=60% height=30%></a>';
        
                            echo '<ul class="navbar-nav">';
                                echo '<li class="nav-item"><a class="nav-link active" href="BATIMENT_PAGES/batiment_add_checklist.php"><i class="fa-solid fa-person-through-window fa-xl"></i> Ajouter Entrée</a></li>';
                                echo '<li class="nav-item"><a class="nav-link active" href="BATIMENT_PAGES/batiment_homepage.php"><i class="fa-solid fa-person-walking-arrow-right fa-xl"></i> Suivi Entrées</a></li>';
                                echo '<li class="nav-item"><a class="nav-link active" href="BATIMENT_PAGES/batiment_closedentry.php"><i class="fa-solid fa-list-check fa-xl"></i> Entrées Terminées</a></li>';
                                echo '<li class="nav-item"><a class="nav-link active" href="BATIMENT_PAGES/batiment_out_manager.php"><i class="fa-solid fa-person-walking-arrow-right fa-flip-horizontal fa-xl"></i> Suivi Sorties</a></li>';
                                echo '<li class="nav-item"><a class="nav-link active" href="deconnexion.php"><i class="fa-solid fa-light fa-power-off fa-xl"></i> Déconnexion</a></li>';
                            echo '</ul>';
        
                        echo '</div>';
                    echo '</nav>';
                echo '</header>';
                }
            ?> 
        </header>

        <div class="user_connected" style="background:#EF7837;">
            <p style="color: white;margin-left:12px;margin-right:12px;padding-top:5px;padding-bottom:5px;font-size:18px;"> 
                <?php 
                    $createdBy = $_SESSION['user_firstname'] . ' ' . $_SESSION['user_lastname'];
                    echo 'Utilisateur connecté : <b>' . $createdBy . '</b>'; 
                ?>
            </p>
        </div>

        

        <section>
            <?php
                echo '<div style="margin-left: 12px;">';
                    echo "<h4 style='margin-top:20px;margin-bottom:20px;'><u>RENSEIGNEMENTS SUR LA PERSONNE</u></h4>";

                    echo '<div class="row g-3" style="padding-top:20px; padding-bottom:8px">';
                        echo '<div class="col-sm-3">';
                            echo "<p><u>Nom & Prénom</u> : " . $profil['user_firstname'] . " " . $profil['user_lastname'] . "</p>";
                        echo "</div>";
                        echo '<div class="col-sm-3">';
                            echo "<p><u>Service</u> : ". $profil['services'] . "</p>";
                        echo "</div>";
                        echo '<div class="col-sm-4">';
                            echo "<p><u>Site Embauche</u> : ". $profil['site_embauche'] . "</p>";
                        echo "</div>";

                    echo "</div>";

                    echo '<div class="row g-3" style="padding-top:10px; padding-bottom:8px">';
                        echo '<div class="col-sm-3">';
                            echo "<p><u>Date d'Embauche</u> : ". $profil['date_embauche'] . "</p>";
                        echo "</div>";
                        echo '<div class="col-sm-4">';
                            echo "<p><u>Technicien</u> : ". $profil_tasks['attribue_a'] . "</p>";
                        echo "</div>";
                    echo "</div>";
                    $size = intval($profil_tasks['progression']);
                    echo '<p><u>Progression </u>: </p>';
                    echo '<div style="width: 60%; margin-left: 0;margin-bottom:20px;" class="progress" role="progressbar" aria-label="Warning striped example" aria-valuenow="'.$size.'" aria-valuemin="0" aria-valuemax="100">';
                        echo '<div class="progress-bar progress-bar-striped bg-success" style="width: ' . $size .'%">'.$size.'%</div>';
                    echo '</div>';

                    /*/echo "<div class='card' style='width: 60%;'>";
                        echo '<div class="card-header">';
                            echo "Commentaires Checklist PDF";
                        echo '</div>';
                        echo "<div class='card-body'>";
                            echo $profil['commentaire'];
                        echo "</div>";
                    echo "</div>";*/

        // Zone d'échange de commentaires liée à la tâche
        $id_task = $_SESSION['id_task'];
        $commentaires = [];
        // Connexion à la base de données déjà établie via $database_connect
        if ($id_task) {
            $sql_comments = "SELECT * FROM commentaires WHERE profil_id = '" . mysqli_real_escape_string($database_connect, $id_task) . "' ORDER BY date_creation DESC";
            $result_comments = mysqli_query($database_connect, $sql_comments);
            if ($result_comments && mysqli_num_rows($result_comments) > 0) {
                while ($row = mysqli_fetch_assoc($result_comments)) {
                    $commentaires[] = $row;
                }
            }
        }

        echo '<div class="card" style="width:60%;height:20%;">';
        echo '<div class="card-header">COMMENTAIRES</div>';
        echo '<div class="card-body" style="height:120px;overflow-y:auto;">';
        if (!empty($commentaires)) {
            foreach ($commentaires as $com) {
                echo '<div style="border-bottom:2px solid #eee; margin-bottom:8px; padding-bottom:8px;">';
                echo '<b>' . htmlspecialchars($com['utilisateur']) . '</b> <span style="color:gray; font-size:12px;">(' . date('d/m/Y H:i', strtotime($com['date_creation'])) . ')</span><br>';
                echo nl2br(htmlspecialchars($com['commentaire']));
                echo '</div>';
            }
        } else {
            echo '<i>Aucun commentaire pour cette tâche.</i>';
        }
        echo '</div>';
        echo '<div class="card-footer">';
        echo '<form method="POST" action="">';
        echo '<div class="mb-3">';
        echo '<label for="nouveau_commentaire" class="form-label">Ajouter un commentaire :</label>';
        echo '<textarea class="form-control" id="nouveau_commentaire" name="nouveau_commentaire" rows="2" required></textarea>';
        echo '</div>';
        echo '<button type="submit" class="btn btn-primary" style="width:100%;">Envoyer</button>';
        echo '</form>';
        echo '</div>';
        echo '</div>';

        // Traitement de l'ajout de commentaire
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nouveau_commentaire']) && !empty(trim($_POST['nouveau_commentaire']))) {
            $contenu = mysqli_real_escape_string($database_connect, trim($_POST['nouveau_commentaire']));
            $utilisateur = $_SESSION['user_firstname'] . ' ' . $_SESSION['user_lastname'];
            $sql_insert = "INSERT INTO commentaires (profil_id, utilisateur, commentaire, date_creation, parent_id) VALUES ('" . mysqli_real_escape_string($database_connect, $id_task) . "', '" . mysqli_real_escape_string($database_connect, $utilisateur) . "', '" . $contenu . "', NOW(), 0)";
            mysqli_query($database_connect, $sql_insert);
            // Rafraîchir la page pour afficher le nouveau commentaire
            echo '<meta http-equiv="refresh" content="0">';
        }

                echo "</div>";


                echo "<h4 style='margin-top:20px;margin-bottom:20px;'><u>TACHES</u></h4>";
                echo "<p style='margin-top:20px;'>Toute les taches ayant eu comme réponse 'Non' dans le formulaire et qui n'ont pas besoin d'etre faites sont en rouge.</p>";

                /*if(strcasecmp($role_actuel, "rh") === 0){
                    require_once 'oneProfil_PAGES/oneProfil_rh.php';
                }elseif(strcasecmp($role_actuel, "informaticien") === 0){
                    require_once 'oneProfil_PAGES/oneProfil_admin.php';
                }elseif(strcasecmp($role_actuel, "batiment") === 0){
                    require_once 'oneProfil_PAGES/oneProfil_batiment.php';
                }elseif(strcasecmp($role_actuel, "achat") === 0){
                    require_once 'oneProfil_PAGES/oneProfil_achat.php';
                }*/

                echo "<form class='container' style='margin:0px; padding-top:20px; padding-bottom:20px' id='forrmulaire_suivi' method='POST' action='modif_suivi.php?role=$role_actuel'>";


                    if(strcasecmp($role_actuel, "rh") === 0){

                        echo "<div class='form-check'>";

                            if (strcasecmp($profil_tasks['etat_rh'],"En attente") === 0) {
                                echo '<label class="form-check-label" for="validation">Validation Checklist RH </label>';
                                echo "<input type='checkbox' class='form-check-input' id='validation' name='validation' value='valeur1'>";    
                            }else{
                                echo '<label class="form-check-label" for="validation">Validation Checklist RH </label>';
                                echo "<input type='checkbox' class='form-check-input' id='validation' name='validation' value='valeur2' checked>";    
                            }

                        echo "</div>";

                        echo "<div class='col-sm-3'>";
                            echo "<label for='attribution' class='form-label'>Check-List attribué à :</label>";
                            echo "<select class='form-select' id='attribution' name='attribution' required disabled>";
                            echo "<option value=''>Choisir...</option>";
                            if ($profil_tasks['attribue_a']=="") {
                                if ($resultatR->num_rows>0) {
                                    while ($rowd= $resultatR->fetch_assoc()) {
                                        echo "<option value='" . $rowd['id']."'>". $rowd['user_firstname'] . " " . $rowd['user_lastname'] . "</option>";
                                    }    
                                }else{
                                    echo "<option value=''>Aucun technicien trouvé</option>";
                                }
                            }else{
                                echo "<option value='' selected >". $profil_tasks['attribue_a'] ."</option>";
                                if ($resultatR->num_rows>0) {
                                    while ($rowd= $resultatR->fetch_assoc()) {

                                        if ($profil_tasks['attribue_a'] == $rowd['user_firstname'] . " " . $rowd['user_lastname']) {
                                            
                                        }else{
                                            echo "<option value='" . $rowd['id']."'>". $rowd['user_firstname'] . " " . $rowd['user_lastname'] . "</option>";
                                        }
                                    }    
                                }else{
                                    echo "<option value=''>Aucun technicien trouvé</option>";
                                }
                            }
                            echo "</select>";
                            echo '<div class="invalid-feedback">';
                                    echo 'Faite un choix valable.';
                            echo '</div>';
                        echo '</div>';

                        echo "<div class='form-check'>";
                            if ($profil_tasks['creation_mail'] == "A faire") {
                                echo '<label class="form-check-label" for="creation_mail">Creation de mail </label>';
                                echo "<input type='checkbox' class='form-check-input' id='creation_mail' name='creation_mail' value='valeur1' disabled>";
                            }elseif ($profil_tasks['creation_mail'] == "Terminée") {
                                echo '<label class="form-check-label" for="creation_mail">Creation de mail </label>';
                                echo "<input type='checkbox' class='form-check-input' id='creation_mail' name='creation_mail' value='valeur2' checked disabled>";
                            }
                            elseif ($profil_tasks['creation_mail'] == "Pas besoin") {
                                echo '<label style="color:red;" class="form-check-label" for="creation_mail">Creation de mail </label>';
                                echo "<input type='checkbox' class='form-check-input' id='creation_mail' name='creation_mail' value='valeur2' checked disabled>";
                            }
                        echo "</div>";

                        /*echo "<div class='form-check'>";
                            if ($profil_tasks['redirection_mail'] == "A faire") {
                                echo '<label class="form-check-label" for="redirection_mail">Redirection de mail </label>';
                                echo "<input type='checkbox' class='form-check-input' id='redirection_mail' name='redirection_mail' value='valeur1' disabled>";
                            }elseif ($profil_tasks['redirection_mail'] == "Terminée") {
                                echo '<label class="form-check-label" for="redirection_mail">Redirection de mail </label>';
                                echo "<input type='checkbox' class='form-check-input' id='redirection_mail' name='redirection_mail' value='valeur2' checked disabled>";
                            }elseif ($profil_tasks['redirection_mail'] == "Pas besoin") {
                                echo '<label style="color:red;" class="form-check-label" for="redirection_mail">Redirection de mail </label>';
                                echo "<input type='checkbox' class='form-check-input' id='redirection_mail' name='redirection_mail' value='valeur2' checked disabled>";
                            }
                        echo "</div>";*/

                        echo "<div class='form-check'>";
                            if ($profil_tasks['creation_rrf'] == "A faire") {
                                echo '<label class="form-check-label" for="creation_rrf">Création dans RRF/DMD </label>';
                                echo "<input type='checkbox' class='form-check-input' id='creation_rrf' name='creation_rrf' value='valeur1'>";
                            }elseif ($profil_tasks['creation_rrf'] == "Terminée") {
                                echo '<label class="form-check-label" for="creation_rrf">Création dans RRF/DMD </label>';
                                echo "<input type='checkbox' class='form-check-input' id='creation_rrf' name='creation_rrf' value='valeur2' checked>";
                            }
                            elseif ($profil_tasks['creation_rrf'] == "Pas besoin") {
                                echo '<label style="color:red;" class="form-check-label" for="creation_rrf">Création dans RRF/DMD </label>';
                                echo "<input type='checkbox' class='form-check-input' id='creation_rrf' name='creation_rrf' value='valeur2' checked disabled>";
                            }
                        echo "</div>";


                        echo "<div class='form-check'>";
                            if ($profil_tasks['creation_rlearning'] == "A faire") {
                                echo '<label class="form-check-label" for="creation_rlearning">Création de RLearning </label>';
                                echo "<input type='checkbox' class='form-check-input' id='creation_rlearning' name='creation_rlearning' value='valeur1'>";
                            }elseif ($profil_tasks['creation_rlearning'] == "Terminée") {
                                echo '<label class="form-check-label" for="creation_rlearning">Création de RLearning </label>';
                                echo "<input type='checkbox' class='form-check-input' id='creation_rlearning' name='creation_rlearning' value='valeur2' checked>";
                            }elseif ($profil_tasks['creation_rlearning'] == "Pas besoin") {
                                echo '<label style="color:red;" class="form-check-label" for="creation_rlearning">Création de RLearning </label>';
                                echo "<input type='checkbox' class='form-check-input' id='creation_rlearning' name='creation_rlearning' value='valeur2' checked disabled>";
                            }
                        echo "</div>";

                        echo "<div class='form-check'>";
                            if ($profil_tasks['creation_rcampus'] == "A faire") {
                                echo '<label class="form-check-label" for="creation_rcampus">Création de RCampus </label>';
                                echo "<input type='checkbox' class='form-check-input' id='creation_rcampus' name='creation_rcampus' value='valeur1'>";
                            }elseif ($profil_tasks['creation_rcampus'] == "Terminée") {
                                echo '<label class="form-check-label" for="creation_rcampus">Création de RCampus </label>';
                                echo "<input type='checkbox' class='form-check-input' id='creation_rcampus' name='creation_rcampus' value='valeur2' checked>";
                            }elseif ($profil_tasks['creation_rcampus'] == "Pas besoin") {
                                echo '<label style="color:red;" class="form-check-label" for="creation_rcampus">Création de RCampus </label>';
                                echo "<input type='checkbox' class='form-check-input' id='creation_rcampus' name='creation_rcampus' value='valeur2' checked disabled>";
                            }
                        echo "</div>";


                        echo "<div class='form-check'>";
                            if ($profil_tasks['creation_dcsnet'] == "A faire") {
                                echo '<label class="form-check-label" for="creation_dcsnet">Création DCS Net </label>';
                                echo "<input type='checkbox' class='form-check-input' id='creation_dcsnet' name='creation_dcsnet' value='valeur1' disabled>";
                            }elseif ($profil_tasks['creation_dcsnet'] == "Terminée") {
                                echo '<label class="form-check-label" for="creation_dcsnet">Création DCS Net </label>';
                                echo "<input type='checkbox' class='form-check-input' id='creation_dcsnet' name='creation_dcsnet' value='valeur2' checked disabled>";
                            }elseif ($profil_tasks['creation_dcsnet'] == "Pas besoin") {
                                echo '<label style="color:red;" class="form-check-label" for="creation_dcsnet">Création DCS Net </label>';
                                echo "<input type='checkbox' class='form-check-input' id='creation_dcsnet' name='creation_dcsnet' value='valeur2' checked disabled>";
                            }
                        echo "</div>";

                        echo "<div class='form-check'>";
                            if ($profil_tasks['creation_tocken'] == "A faire") {
                                echo '<label class="form-check-label" for="creation_tocken">Création de tocken </label>';
                                echo "<input type='checkbox' class='form-check-input' id='creation_tocken' name='creation_tocken' value='valeur1' disabled>";
                            }elseif ($profil_tasks['creation_tocken'] == "Terminée") {
                                echo '<label class="form-check-label" for="creation_tocken">Création de tocken </label>';
                                echo "<input type='checkbox' class='form-check-input' id='creation_tocken' name='creation_tocken' value='valeur2' checked disabled>";
                            }elseif ($profil_tasks['creation_tocken'] == "Pas besoin") {
                                echo '<label style="color:red;" class="form-check-label" for="creation_tocken">Création de tocken </label>';
                                echo "<input type='checkbox' class='form-check-input' id='creation_tocken' name='creation_tocken' value='valeur2' checked disabled>";
                            }
                        echo "</div>";

                        echo "<div class='form-check'>";
                            if ($profil_tasks['attribution_telephone'] == "A faire") {
                                echo '<label class="form-check-label" for="attribution_telephone">Attribution d\'une ligne téléphonique </label>';
                                echo "<input type='checkbox' class='form-check-input' id='attribution_telephone' name='attribution_telephone' value='valeur1' disabled>";
                            }elseif ($profil_tasks['attribution_telephone'] == "Terminée") {
                                echo '<label class="form-check-label" for="attribution_telephone">Attribution d\'une ligne téléphonique </label>';
                                echo "<input type='checkbox' class='form-check-input' id='attribution_telephone' name='attribution_telephone' value='valeur2' checked disabled>";
                            }elseif ($profil_tasks['attribution_telephone'] == "Pas besoin") {
                                echo '<label style="color:red;" class="form-check-label" for="attribution_telephone">Attribution d\'une ligne téléphonique </label>';
                                echo "<input type='checkbox' class='form-check-input' id='attribution_telephone' name='attribution_telephone' value='valeur2' checked disabled>";
                            }
                        echo "</div>";

                        echo "<div class='form-check'>";
                            if ($profil_tasks['preaparation_pc'] == "A faire") {
                                echo '<label class="form-check-label" for="preaparation_pc">Attribution d\'un Ordinateur </label>';
                                echo "<input type='checkbox' class='form-check-input' id='preaparation_pc' name='preaparation_pc' value='valeur1' disabled>";
                            }elseif ($profil_tasks['preaparation_pc'] == "Terminée") {
                                echo '<label class="form-check-label" for="preaparation_pc">Attribution d\'un Ordinateur </label>';
                                echo "<input type='checkbox' class='form-check-input' id='preaparation_pc' name='preaparation_pc' value='valeur2' checked disabled>";
                            }elseif ($profil_tasks['preaparation_pc'] == "Pas besoin") {
                                echo '<label style="color:red;" class="form-check-label" for="preaparation_pc">Attribution d\'un Ordinateur </label>';
                                echo "<input type='checkbox' class='form-check-input' id='preaparation_pc' name='preaparation_pc' value='valeur2' checked disabled>";
                            }
                        echo "</div>";

                        echo "<div class='form-check'>";
                            if ($profil_tasks['preparation_malette'] == "A faire") {
                                echo '<label class="form-check-label" for="preparation_malette">Attribution d\'une malette </label>';
                                echo "<input type='checkbox' class='form-check-input' id='preparation_malette' name='preparation_malette' value='valeur1' disabled>";
                            }elseif ($profil_tasks['preparation_malette'] == "Terminée") {
                                echo '<label class="form-check-label" for="preparation_malette">Attribution d\'une malette </label>';
                                echo "<input type='checkbox' class='form-check-input' id='preparation_malette' name='preparation_malette' value='valeur2' checked disabled>";
                            }elseif ($profil_tasks['preparation_malette'] == "Pas besoin") {
                                echo '<label style="color:red;" class="form-check-label" for="preparation_malette">Attribution d\'une malette </label>';
                                echo "<input type='checkbox' class='form-check-input' id='preparation_malette' name='preparation_malette' value='valeur2' checked disabled>";
                            }
                        echo "</div>";

                        echo "<div class='form-check'>";
                            if ($profil_tasks['preparation_imprimante'] == "A faire") {
                                echo '<label class="form-check-label" for="preparation_imprimante">Attribution d\'une imprimante </label>';
                                echo "<input type='checkbox' class='form-check-input' id='preparation_imprimante' name='preparation_imprimante' value='valeur1' disabled>";
                            }elseif ($profil_tasks['preparation_imprimante'] == "Terminée") {
                                echo '<label class="form-check-label" for="preparation_imprimante">Attribution d\'une imprimante </label>';
                                echo "<input type='checkbox' class='form-check-input' id='preparation_imprimante' name='preparation_imprimante' value='valeur2' checked disabled>";
                            }elseif ($profil_tasks['preparation_imprimante'] == "Pas besoin") {
                                echo '<label style="color:red;" class="form-check-label" for="preparation_imprimante">Attribution d\'une imprimante </label>';
                                echo "<input type='checkbox' class='form-check-input' id='preparation_imprimante' name='preparation_imprimante' value='valeur2' checked disabled>";
                            }
                        echo "</div>";

                        echo '<div class="row g-3" style="padding-top:10px; padding-bottom:10px">';

                            if($profil_tasks['date_envoi']==""){

                                echo '<div class="col-sm-3">';
                                    echo '<label for="date_envoi" class="form-label" disabled>Date d\'envoi équipement informatique (JJ/MM/AAAA)</label>';
                                    echo '<input type="text" class="form-control" name="date_envoi" id="date_envoi" placeholder="" value="" disabled>';
                                echo '</div>';

                            }elseif($profil_tasks['date_envoi']!=""){
                                echo '<div class="col-sm-3">';
                                    echo '<label for="date_envoi" class="form-label" disabled>Date d\'envoi équipement informatique (JJ/MM/AAAA)</label>';
                                    echo '<input type="text" class="form-control" name="date_envoi" id="date_envoi" placeholder="" value="'. date('d-m-Y', strtotime($profil_tasks['date_envoi'])) .'" disabled>';
                                echo '</div>';
                            }

                        echo '</div>';

                        echo "<div class='form-check'>";
                            if ($profil_tasks['attribution_vehicule'] == "A faire") {
                                echo '<label class="form-check-label" for="attribution_vehicule">Attribution d\'un véhicule </label>';
                                echo "<input type='checkbox' class='form-check-input' id='attribution_vehicule' name='attribution_vehicule' value='valeur1' disabled>";
                            }elseif ($profil_tasks['attribution_vehicule'] == "Terminée") {
                                echo '<label class="form-check-label" for="attribution_vehicule">Attribution d\'un véhicule </label>';
                                echo "<input type='checkbox' class='form-check-input' id='attribution_vehicule' name='attribution_vehicule' value='valeur2' checked disabled>";
                            }elseif ($profil_tasks['attribution_vehicule'] == "Pas besoin") {
                                echo '<label style="color:red;" class="form-check-label" for="attribution_vehicule">Attribution d\'un véhicule </label>';
                                echo "<input type='checkbox' class='form-check-input' id='attribution_vehicule' name='attribution_vehicule' value='valeur2' checked disabled>";
                            }
                        echo "</div>";

                        echo "<div class='form-check'>";
                            if ($profil_tasks['attribution_badge'] == "A faire") {
                                echo '<label class="form-check-label" for="attribution_badge">Attribution d\'un badge </label>';
                                echo "<input type='checkbox' class='form-check-input' id='attribution_badge' name='attribution_badge' value='valeur1' disabled>";
                            }elseif ($profil_tasks['attribution_badge'] == "Terminée") {
                                echo '<label class="form-check-label" for="attribution_badge">Attribution d\'un badge </label>';
                                echo "<input type='checkbox' class='form-check-input' id='attribution_badge' name='attribution_badge' value='valeur2' checked disabled>";
                            }elseif ($profil_tasks['attribution_badge'] == "Pas besoin") {
                                echo '<label style="color:red;" class="form-check-label" for="attribution_badge">Attribution d\'un badge </label>';
                                echo "<input type='checkbox' class='form-check-input' id='attribution_badge' name='attribution_badge' value='valeur2' checked disabled>";
                            }
                        echo "</div>";

                        echo "<div class='form-check'>";
                            if ($profil_tasks['attribution_cles'] == "A faire") {
                                echo '<label class="form-check-label" for="attribution_cles">Attribution clés des batiments </label>';
                                echo "<input type='checkbox' class='form-check-input' id='attribution_cles' name='attribution_cles' value='valeur1' disabled>";
                            }elseif ($profil_tasks['attribution_cles'] == "Terminée") {
                                echo '<label class="form-check-label" for="attribution_cles">Attribution clés des batiments </label>';
                                echo "<input type='checkbox' class='form-check-input' id='attribution_cles' name='attribution_cles' value='valeur2' checked disabled>";
                            }elseif ($profil_tasks['attribution_cles'] == "Pas besoin") {
                                echo '<label style="color:red;" class="form-check-label" for="attribution_cles">Attribution clés des batiments </label>';
                                echo "<input type='checkbox' class='form-check-input' id='attribution_cles' name='attribution_cles' value='valeur2' checked disabled>";
                            }
                        echo "</div>";

                        echo "<div class='form-check'>";
                            if ($profil_tasks['attribution_vetements'] == "A faire") {
                                echo '<label class="form-check-label" for="attribution_vetements">Attribution de vêtements</label>';
                                echo "<input type='checkbox' class='form-check-input' id='attribution_vetements' name='attribution_vetements' value='valeur1' disabled>";
                            }elseif ($profil_tasks['attribution_vetements'] == "Terminée") {
                                echo '<label class="form-check-label" for="attribution_vetements">Attribution de vêtements</label>';
                                echo "<input type='checkbox' class='form-check-input' id='attribution_vetements' name='attribution_vetements' value='valeur2' checked disabled>";
                            }elseif ($profil_tasks['attribution_vetements'] == "Pas besoin") {
                                echo '<label style="color:red;" class="form-check-label" for="attribution_vetements">Attribution de vêtements</label>';
                                echo "<input type='checkbox' class='form-check-input' id='attribution_vetements' name='attribution_vetements' value='valeur2' checked disabled>";
                            }
                        echo "</div>";

                        echo "<div class='card' style='width: 25%;'>";
                            echo '<div class="card-header">';
                                echo "Taille des vêtements à attribués";
                            echo '</div>';
                            echo "<div class='card-body'>";
                                echo nl2br(str_replace(', ', "\n", $profil['commentaire_vetement']));
                            echo "</div>";
                        echo "</div>";
                        echo "<div class='form-check'>";

                            if ($profil_tasks['attribution_chaussures'] == "A faire") {
                                echo '<label class="form-check-label" for="attribution_chaussures">Attribution de Chaussures</label>';
                                echo "<input type='checkbox' class='form-check-input' id='attribution_chaussures' name='attribution_chaussures' value='valeur1' disabled>";
                            }elseif ($profil_tasks['attribution_vetements'] == "Terminée") {
                                echo '<label class="form-check-label" for="attribution_vetements">Attribution de vêtements</label>';
                                echo "<input type='checkbox' class='form-check-input' id='attribution_chaussures' name='attribution_chaussures' value='valeur2' checked disabled>";
                            }elseif ($profil_tasks['attribution_vetements'] == "Pas besoin") {
                                echo '<label style="color:red;" class="form-check-label" for="attribution_chaussures">Attribution de Chaussures</label>';
                                echo "<input type='checkbox' class='form-check-input' id='attribution_chaussures' name='attribution_chaussures' value='valeur2' checked disabled>";
                            }
                        echo "</div>";
                        echo "<div class='card' style='width: 25%;'>";
                            echo '<div class="card-header">';
                                echo "Pointure";
                            echo '</div>';
                            echo "<div class='card-body'>";
                                echo nl2br(str_replace(', ', "\n", $profil['pointure']));
                            echo "</div>";
                        echo "</div>";
                        
                    }elseif(strcasecmp($role_actuel, "informaticien") === 0){
                        echo "<div class='col-sm-3'>";
                            echo "<label for='attribution' class='form-label'>Check-List attribué à :</label>";
                            echo "<select class='form-select' id='attribution' name='attribution' required>";
                            echo "<option value=''>Choisir...</option>";
                            if ($profil_tasks['attribue_a']=="") {
                                if ($resultatR->num_rows>0) {
                                    while ($rowd= $resultatR->fetch_assoc()) {
                                        echo "<option value='" . $rowd['id']."'>". $rowd['user_firstname'] . " " . $rowd['user_lastname'] . "</option>";
                                    }    
                                }else{
                                    echo "<option value=''>Aucun technicien trouvé</option>";
                                }
                            }else{
                                echo "<option value='' selected >". $profil_tasks['attribue_a'] ."</option>";
                                if ($resultatR->num_rows>0) {
                                    while ($rowd= $resultatR->fetch_assoc()) {

                                        if ($profil_tasks['attribue_a'] == $rowd['user_firstname'] . " " . $rowd['user_lastname']) {
                                            
                                        }else{
                                            echo "<option value='" . $rowd['id']."'>". $rowd['user_firstname'] . " " . $rowd['user_lastname'] . "</option>";
                                        }
                                    }    
                                }else{
                                    echo "<option value=''>Aucun technicien trouvé</option>";
                                }
                            }
                            echo "</select>";
                            echo '<div class="invalid-feedback">';
                                    echo 'Faite un choix valable.';
                            echo '</div>';
                        echo '</div>';

                        echo "<div class='form-check'>";
                            if ($profil_tasks['creation_mail'] == "A faire") {
                                echo '<label class="form-check-label" for="creation_mail">Creation de mail </label>';
                                echo "<input type='checkbox' class='form-check-input' id='creation_mail' name='creation_mail' value='valeur1'>";
                            }elseif ($profil_tasks['creation_mail'] == "Terminée") {
                                echo '<label class="form-check-label" for="creation_mail">Creation de mail </label>';
                                echo "<input type='checkbox' class='form-check-input' id='creation_mail' name='creation_mail' value='valeur2' checked>";
                            }
                            elseif ($profil_tasks['creation_mail'] == "Pas besoin") {
                                echo '<label style="color:red;" class="form-check-label" for="creation_mail">Creation de mail </label>';
                                echo "<input type='checkbox' class='form-check-input' id='creation_mail' name='creation_mail' value='valeur2' checked disabled>";
                            }
                        echo "</div>";

                        /*echo "<div class='form-check'>";
                            if ($profil_tasks['redirection_mail'] == "A faire") {
                                echo '<label class="form-check-label" for="redirection_mail">Redirection de mail </label>';
                                echo "<input type='checkbox' class='form-check-input' id='redirection_mail' name='redirection_mail' value='valeur1'>";
                            }elseif ($profil_tasks['redirection_mail'] == "Terminée") {
                                echo '<label class="form-check-label" for="redirection_mail">Redirection de mail </label>';
                                echo "<input type='checkbox' class='form-check-input' id='redirection_mail' name='redirection_mail' value='valeur2' checked>";
                            }elseif ($profil_tasks['redirection_mail'] == "Pas besoin") {
                                echo '<label style="color:red;" class="form-check-label" for="redirection_mail">Redirection de mail </label>';
                                echo "<input type='checkbox' class='form-check-input' id='redirection_mail' name='redirection_mail' value='valeur2' checked disabled>";
                            }
                        echo "</div>";*/

                        echo "<div class='form-check'>";
                            if ($profil_tasks['creation_rrf'] == "A faire") {
                                echo '<label class="form-check-label" for="creation_rrf">Création dans RRF/DMD </label>';
                                echo "<input type='checkbox' class='form-check-input' id='creation_rrf' name='creation_rrf' value='valeur1' disabled>";
                            }elseif ($profil_tasks['creation_rrf'] == "Terminée") {
                                echo '<label class="form-check-label" for="creation_rrf">Création dans RRF/DMD </label>';
                                echo "<input type='checkbox' class='form-check-input' id='creation_rrf' name='creation_rrf' value='valeur2' checked disabled>";
                            }
                            elseif ($profil_tasks['creation_rrf'] == "Pas besoin") {
                                echo '<label style="color:red;" class="form-check-label" for="creation_rrf">Création dans RRF/DMD </label>';
                                echo "<input type='checkbox' class='form-check-input' id='creation_rrf' name='creation_rrf' value='valeur2' checked disabled>";
                            }
                        echo "</div>";


                        echo "<div class='form-check'>";
                            if ($profil_tasks['creation_rlearning'] == "A faire") {
                                echo '<label class="form-check-label" for="creation_rlearning">Création de RLearning </label>';
                                echo "<input type='checkbox' class='form-check-input' id='creation_rlearning' name='creation_rlearning' value='valeur1' disabled>";
                            }elseif ($profil_tasks['creation_rlearning'] == "Terminée") {
                                echo '<label class="form-check-label" for="creation_rlearning">Création de RLearning </label>';
                                echo "<input type='checkbox' class='form-check-input' id='creation_rlearning' name='creation_rlearning' value='valeur2' checked disabled>";
                            }elseif ($profil_tasks['creation_rlearning'] == "Pas besoin") {
                                echo '<label style="color:red;" class="form-check-label" for="creation_rlearning">Création de RLearning </label>';
                                echo "<input type='checkbox' class='form-check-input' id='creation_rlearning' name='creation_rlearning' value='valeur2' checked disabled>";
                            }
                        echo "</div>";

                        echo "<div class='form-check'>";
                            if ($profil_tasks['creation_rcampus'] == "A faire") {
                                echo '<label class="form-check-label" for="creation_rcampus">Création de RCampus </label>';
                                echo "<input type='checkbox' class='form-check-input' id='creation_rcampus' name='creation_rcampus' value='valeur1' disabled>";
                            }elseif ($profil_tasks['creation_rcampus'] == "Terminée") {
                                echo '<label class="form-check-label" for="creation_rcampus">Création de RCampus </label>';
                                echo "<input type='checkbox' class='form-check-input' id='creation_rcampus' name='creation_rcampus' value='valeur2' checked disabled>";
                            }elseif ($profil_tasks['creation_rcampus'] == "Pas besoin") {
                                echo '<label style="color:red;" class="form-check-label" for="creation_rcampus">Création de RCampus </label>';
                                echo "<input type='checkbox' class='form-check-input' id='creation_rcampus' name='creation_rcampus' value='valeur2' checked disabled disabled>";
                            }
                        echo "</div>";


                        echo "<div class='form-check'>";
                            if ($profil_tasks['creation_dcsnet'] == "A faire") {
                                echo '<label class="form-check-label" for="creation_dcsnet">Création DCS Net </label>';
                                echo "<input type='checkbox' class='form-check-input' id='creation_dcsnet' name='creation_dcsnet' value='valeur1'>";
                            }elseif ($profil_tasks['creation_dcsnet'] == "Terminée") {
                                echo '<label class="form-check-label" for="creation_dcsnet">Création DCS Net </label>';
                                echo "<input type='checkbox' class='form-check-input' id='creation_dcsnet' name='creation_dcsnet' value='valeur2' checked>";
                            }elseif ($profil_tasks['creation_dcsnet'] == "Pas besoin") {
                                echo '<label style="color:red;" class="form-check-label" for="creation_dcsnet">Création DCS Net </label>';
                                echo "<input type='checkbox' class='form-check-input' id='creation_dcsnet' name='creation_dcsnet' value='valeur2' checked disabled>";
                            }
                        echo "</div>";

                        echo "<div class='form-check'>";
                            if ($profil_tasks['creation_tocken'] == "A faire") {
                                echo '<label class="form-check-label" for="creation_tocken">Création de tocken </label>';
                                echo "<input type='checkbox' class='form-check-input' id='creation_tocken' name='creation_tocken' value='valeur1'>";
                            }elseif ($profil_tasks['creation_tocken'] == "Terminée") {
                                echo '<label class="form-check-label" for="creation_tocken">Création de tocken </label>';
                                echo "<input type='checkbox' class='form-check-input' id='creation_tocken' name='creation_tocken' value='valeur2' checked>";
                            }elseif ($profil_tasks['creation_tocken'] == "Pas besoin") {
                                echo '<label style="color:red;" class="form-check-label" for="creation_tocken">Création de tocken </label>';
                                echo "<input type='checkbox' class='form-check-input' id='creation_tocken' name='creation_tocken' value='valeur2' checked disabled>";
                            }
                        echo "</div>";

                        echo "<div class='form-check'>";
                            if ($profil_tasks['attribution_telephone'] == "A faire") {
                                echo '<label class="form-check-label" for="attribution_telephone">Attribution d\'une ligne téléphonique </label>';
                                echo "<input type='checkbox' class='form-check-input' id='attribution_telephone' name='attribution_telephone' value='valeur1' disabled disabled>";
                            }elseif ($profil_tasks['attribution_telephone'] == "Terminée") {
                                echo '<label class="form-check-label" for="attribution_telephone">Attribution d\'une ligne téléphonique </label>';
                                echo "<input type='checkbox' class='form-check-input' id='attribution_telephone' name='attribution_telephone' value='valeur2' checked disabled>";
                            }elseif ($profil_tasks['attribution_telephone'] == "Pas besoin") {
                                echo '<label style="color:red;" class="form-check-label" for="attribution_telephone">Attribution d\'une ligne téléphonique </label>';
                                echo "<input type='checkbox' class='form-check-input' id='attribution_telephone' name='attribution_telephone' value='valeur2' checked disabled>";
                            }
                        echo "</div>";

                        echo "<div class='form-check'>";
                            if ($profil_tasks['preaparation_pc'] == "A faire") {
                                echo '<label class="form-check-label" for="preaparation_pc">Attribution d\'un Ordinateur </label>';
                                echo "<input type='checkbox' class='form-check-input' id='preaparation_pc' name='preaparation_pc' value='valeur1'>";
                            }elseif ($profil_tasks['preaparation_pc'] == "Terminée") {
                                echo '<label class="form-check-label" for="preaparation_pc">Attribution d\'un Ordinateur </label>';
                                echo "<input type='checkbox' class='form-check-input' id='preaparation_pc' name='preaparation_pc' value='valeur2' checked>";
                            }elseif ($profil_tasks['preaparation_pc'] == "Pas besoin") {
                                echo '<label style="color:red;" class="form-check-label" for="preaparation_pc">Attribution d\'un Ordinateur </label>';
                                echo "<input type='checkbox' class='form-check-input' id='preaparation_pc' name='preaparation_pc' value='valeur2' checked disabled>";
                            }
                        echo "</div>";

                        echo "<div class='form-check'>";
                            if ($profil_tasks['preparation_malette'] == "A faire") {
                                echo '<label class="form-check-label" for="preparation_malette">Attribution d\'une malette </label>';
                                echo "<input type='checkbox' class='form-check-input' id='preparation_malette' name='preparation_malette' value='valeur1'>";
                            }elseif ($profil_tasks['preparation_malette'] == "Terminée") {
                                echo '<label class="form-check-label" for="preparation_malette">Attribution d\'une malette </label>';
                                echo "<input type='checkbox' class='form-check-input' id='preparation_malette' name='preparation_malette' value='valeur2' checked>";
                            }elseif ($profil_tasks['preparation_malette'] == "Pas besoin") {
                                echo '<label style="color:red;" class="form-check-label" for="preparation_malette">Attribution d\'une malette </label>';
                                echo "<input type='checkbox' class='form-check-input' id='preparation_malette' name='preparation_malette' value='valeur2' checked disabled>";
                            }
                        echo "</div>";

                        echo "<div class='form-check'>";
                            if ($profil_tasks['preparation_imprimante'] == "A faire") {
                                echo '<label class="form-check-label" for="preparation_imprimante">Attribution d\'une imprimante </label>';
                                echo "<input type='checkbox' class='form-check-input' id='preparation_imprimante' name='preparation_imprimante' value='valeur1'>";
                            }elseif ($profil_tasks['preparation_imprimante'] == "Terminée") {
                                echo '<label class="form-check-label" for="preparation_imprimante">Attribution d\'une imprimante </label>';
                                echo "<input type='checkbox' class='form-check-input' id='preparation_imprimante' name='preparation_imprimante' value='valeur2' checked>";
                            }elseif ($profil_tasks['preparation_imprimante'] == "Pas besoin") {
                                echo '<label style="color:red;" class="form-check-label" for="preparation_imprimante">Attribution d\'une imprimante </label>';
                                echo "<input type='checkbox' class='form-check-input' id='preparation_imprimante' name='preparation_imprimante' value='valeur2' checked disabled>";
                            }
                        echo "</div>";

                        echo '<div class="row g-3" style="padding-top:10px; padding-bottom:10px">';

                            if($profil_tasks['date_envoi']==""){

                                echo '<div class="col-sm-3">';
                                    echo '<label for="date_envoi" class="form-label" disabled>Date d\'envoi équipement informatique (JJ/MM/AAAA)</label>';
                                    echo '<input type="text" class="form-control" name="date_envoi" id="date_envoi" placeholder="" value="">';
                                echo '</div>';

                            }elseif($profil_tasks['date_envoi']!=""){
                                echo '<div class="col-sm-3">';
                                    echo '<label for="date_envoi" class="form-label" disabled>Date d\'envoi équipement informatique (JJ/MM/AAAA)</label>';
                                    echo '<input type="text" class="form-control" name="date_envoi" id="date_envoi" placeholder="" value="'. date('d-m-Y', strtotime($profil_tasks['date_envoi'])) .'">';
                                echo '</div>';
                            }

                        echo '</div>';

                        echo "<div class='form-check'>";
                            if ($profil_tasks['attribution_vehicule'] == "A faire") {
                                echo '<label class="form-check-label" for="attribution_vehicule">Attribution d\'un véhicule </label>';
                                echo "<input type='checkbox' class='form-check-input' id='attribution_vehicule' name='attribution_vehicule' value='valeur1' disabled>";
                            }elseif ($profil_tasks['attribution_vehicule'] == "Terminée") {
                                echo '<label class="form-check-label" for="attribution_vehicule">Attribution d\'un véhicule </label>';
                                echo "<input type='checkbox' class='form-check-input' id='attribution_vehicule' name='attribution_vehicule' value='valeur2' checked disabled>";
                            }elseif ($profil_tasks['attribution_vehicule'] == "Pas besoin") {
                                echo '<label style="color:red;" class="form-check-label" for="attribution_vehicule">Attribution d\'un véhicule </label>';
                                echo "<input type='checkbox' class='form-check-input' id='attribution_vehicule' name='attribution_vehicule' value='valeur2' checked disabled>";
                            }
                        echo "</div>";

                        echo "<div class='form-check'>";
                            if ($profil_tasks['attribution_badge'] == "A faire") {
                                echo '<label class="form-check-label" for="attribution_badge">Attribution d\'un badge </label>';
                                echo "<input type='checkbox' class='form-check-input' id='attribution_badge' name='attribution_badge' value='valeur1' disabled>";
                            }elseif ($profil_tasks['attribution_badge'] == "Terminée") {
                                echo '<label class="form-check-label" for="attribution_badge">Attribution d\'un badge </label>';
                                echo "<input type='checkbox' class='form-check-input' id='attribution_badge' name='attribution_badge' value='valeur2' checked disabled>";
                            }elseif ($profil_tasks['attribution_badge'] == "Pas besoin") {
                                echo '<label style="color:red;" class="form-check-label" for="attribution_badge">Attribution d\'un badge </label>';
                                echo "<input type='checkbox' class='form-check-input' id='attribution_badge' name='attribution_badge' value='valeur2' checked disabled>";
                            }
                        echo "</div>";

                        echo "<div class='form-check'>";
                            if ($profil_tasks['attribution_cles'] == "A faire") {
                                echo '<label class="form-check-label" for="attribution_cles">Attribution clés des batiments </label>';
                                echo "<input type='checkbox' class='form-check-input' id='attribution_cles' name='attribution_cles' value='valeur1' disabled>";
                            }elseif ($profil_tasks['attribution_cles'] == "Terminée") {
                                echo '<label class="form-check-label" for="attribution_cles">Attribution clés des batiments </label>';
                                echo "<input type='checkbox' class='form-check-input' id='attribution_cles' name='attribution_cles' value='valeur2' checked disabled>";
                            }elseif ($profil_tasks['attribution_cles'] == "Pas besoin") {
                                echo '<label style="color:red;" class="form-check-label" for="attribution_cles">Attribution clés des batiments </label>';
                                echo "<input type='checkbox' class='form-check-input' id='attribution_cles' name='attribution_cles' value='valeur2' checked disabled>";
                            }
                        echo "</div>";

                        echo "<div class='form-check'>";
                            if ($profil_tasks['attribution_vetements'] == "A faire") {
                                echo '<label class="form-check-label" for="attribution_vetements">Attribution de vêtements</label>';
                                echo "<input type='checkbox' class='form-check-input' id='attribution_vetements' name='attribution_vetements' value='valeur1' disabled>";
                            }elseif ($profil_tasks['attribution_vetements'] == "Terminée") {
                                echo '<label class="form-check-label" for="attribution_vetements">Attribution de vêtements</label>';
                                echo "<input type='checkbox' class='form-check-input' id='attribution_vetements' name='attribution_vetements' value='valeur2' checked disabled>";
                            }elseif ($profil_tasks['attribution_vetements'] == "Pas besoin") {
                                echo '<label style="color:red;" class="form-check-label" for="attribution_vetements">Attribution de vêtements</label>';
                                echo "<input type='checkbox' class='form-check-input' id='attribution_vetements' name='attribution_vetements' value='valeur2' checked disabled>";
                            }
                        echo "</div>";

                        echo "<div class='card' style='width: 25%;'>";
                            echo '<div class="card-header">';
                                echo "Taille des vêtements à attribués";
                            echo '</div>';
                            echo "<div class='card-body'>";
                                echo nl2br(str_replace(', ', "\n", $profil['commentaire_vetement']));
                            echo "</div>";
                        echo "</div>";
                        echo "<div class='form-check'>";

                            if ($profil_tasks['attribution_chaussures'] == "A faire") {
                                echo '<label class="form-check-label" for="attribution_chaussures">Attribution de Chaussures</label>';
                                echo "<input type='checkbox' class='form-check-input' id='attribution_chaussures' name='attribution_chaussures' value='valeur1' disabled>";
                            }elseif ($profil_tasks['attribution_vetements'] == "Terminée") {
                                echo '<label class="form-check-label" for="attribution_vetements">Attribution de vêtements</label>';
                                echo "<input type='checkbox' class='form-check-input' id='attribution_chaussures' name='attribution_chaussures' value='valeur2' checked disabled>";
                            }elseif ($profil_tasks['attribution_vetements'] == "Pas besoin") {
                                echo '<label style="color:red;" class="form-check-label" for="attribution_chaussures">Attribution de Chaussures</label>';
                                echo "<input type='checkbox' class='form-check-input' id='attribution_chaussures' name='attribution_chaussures' value='valeur2' checked disabled>";
                            }
                        echo "</div>";
                        echo "<div class='card' style='width: 25%;'>";
                            echo '<div class="card-header">';
                                echo "Pointure";
                            echo '</div>';
                            echo "<div class='card-body'>";
                                echo nl2br(str_replace(', ', "\n", $profil['pointure']));
                            echo "</div>";
                        echo "</div>";
                        
                    }elseif(strcasecmp($role_actuel, "batiment") === 0){
                        echo "<div class='col-sm-3'>";
                            echo "<label for='attribution' class='form-label'>Check-List attribué à :</label>";
                            echo "<select class='form-select' id='attribution' name='attribution' required disabled>";
                            echo "<option value=''>Choisir...</option>";
                            if ($profil_tasks['attribue_a']=="") {
                                if ($resultatR->num_rows>0) {
                                    while ($rowd= $resultatR->fetch_assoc()) {
                                        echo "<option value='" . $rowd['id']."'>". $rowd['user_firstname'] . " " . $rowd['user_lastname'] . "</option>";
                                    }    
                                }else{
                                    echo "<option value=''>Aucun technicien trouvé</option>";
                                }
                            }else{
                                echo "<option value='' selected >". $profil_tasks['attribue_a'] ."</option>";
                                if ($resultatR->num_rows>0) {
                                    while ($rowd= $resultatR->fetch_assoc()) {

                                        if ($profil_tasks['attribue_a'] == $rowd['user_firstname'] . " " . $rowd['user_lastname']) {
                                            
                                        }else{
                                            echo "<option value='" . $rowd['id']."'>". $rowd['user_firstname'] . " " . $rowd['user_lastname'] . "</option>";
                                        }
                                    }    
                                }else{
                                    echo "<option value=''>Aucun technicien trouvé</option>";
                                }
                            }
                            echo "</select>";
                            echo '<div class="invalid-feedback">';
                                    echo 'Faite un choix valable.';
                            echo '</div>';
                        echo '</div>';

                        echo "<div class='form-check'>";
                            if ($profil_tasks['creation_mail'] == "A faire") {
                                echo '<label class="form-check-label" for="creation_mail">Creation de mail </label>';
                                echo "<input type='checkbox' class='form-check-input' id='creation_mail' name='creation_mail' value='valeur1' disabled>";
                            }elseif ($profil_tasks['creation_mail'] == "Terminée") {
                                echo '<label class="form-check-label" for="creation_mail">Creation de mail </label>';
                                echo "<input type='checkbox' class='form-check-input' id='creation_mail' name='creation_mail' value='valeur2' checked disabled>";
                            }
                            elseif ($profil_tasks['creation_mail'] == "Pas besoin") {
                                echo '<label style="color:red;" class="form-check-label" for="creation_mail">Creation de mail </label>';
                                echo "<input type='checkbox' class='form-check-input' id='creation_mail' name='creation_mail' value='valeur2' checked disabled>";
                            }
                        echo "</div>";

                        /*echo "<div class='form-check'>";
                            if ($profil_tasks['redirection_mail'] == "A faire") {
                                echo '<label class="form-check-label" for="redirection_mail">Redirection de mail </label>';
                                echo "<input type='checkbox' class='form-check-input' id='redirection_mail' name='redirection_mail' value='valeur1' disabled>";
                            }elseif ($profil_tasks['redirection_mail'] == "Terminée") {
                                echo '<label class="form-check-label" for="redirection_mail">Redirection de mail </label>';
                                echo "<input type='checkbox' class='form-check-input' id='redirection_mail' name='redirection_mail' value='valeur2' checked disabled>";
                            }elseif ($profil_tasks['redirection_mail'] == "Pas besoin") {
                                echo '<label style="color:red;" class="form-check-label" for="redirection_mail">Redirection de mail </label>';
                                echo "<input type='checkbox' class='form-check-input' id='redirection_mail' name='redirection_mail' value='valeur2' checked disabled>";
                            }
                        echo "</div>";*/

                        echo "<div class='form-check'>";
                            if ($profil_tasks['creation_rrf'] == "A faire") {
                                echo '<label class="form-check-label" for="creation_rrf">Création dans RRF/DMD </label>';
                                echo "<input type='checkbox' class='form-check-input' id='creation_rrf' name='creation_rrf' value='valeur1' disabled>";
                            }elseif ($profil_tasks['creation_rrf'] == "Terminée") {
                                echo '<label class="form-check-label" for="creation_rrf">Création dans RRF/DMD </label>';
                                echo "<input type='checkbox' class='form-check-input' id='creation_rrf' name='creation_rrf' value='valeur2' checked disabled>";
                            }
                            elseif ($profil_tasks['creation_rrf'] == "Pas besoin") {
                                echo '<label style="color:red;" class="form-check-label" for="creation_rrf">Création dans RRF/DMD </label>';
                                echo "<input type='checkbox' class='form-check-input' id='creation_rrf' name='creation_rrf' value='valeur2' checked disabled>";
                            }
                        echo "</div>";


                        echo "<div class='form-check'>";
                            if ($profil_tasks['creation_rlearning'] == "A faire") {
                                echo '<label class="form-check-label" for="creation_rlearning">Création de RLearning </label>';
                                echo "<input type='checkbox' class='form-check-input' id='creation_rlearning' name='creation_rlearning' value='valeur1' disabled>";
                            }elseif ($profil_tasks['creation_rlearning'] == "Terminée") {
                                echo '<label class="form-check-label" for="creation_rlearning">Création de RLearning </label>';
                                echo "<input type='checkbox' class='form-check-input' id='creation_rlearning' name='creation_rlearning' value='valeur2' checked disabled>";
                            }elseif ($profil_tasks['creation_rlearning'] == "Pas besoin") {
                                echo '<label style="color:red;" class="form-check-label" for="creation_rlearning">Création de RLearning </label>';
                                echo "<input type='checkbox' class='form-check-input' id='creation_rlearning' name='creation_rlearning' value='valeur2' checked disabled>";
                            }
                        echo "</div>";

                        echo "<div class='form-check'>";
                            if ($profil_tasks['creation_rcampus'] == "A faire") {
                                echo '<label class="form-check-label" for="creation_rcampus">Création de RCampus </label>';
                                echo "<input type='checkbox' class='form-check-input' id='creation_rcampus' name='creation_rcampus' value='valeur1' disabled>";
                            }elseif ($profil_tasks['creation_rcampus'] == "Terminée") {
                                echo '<label class="form-check-label" for="creation_rcampus">Création de RCampus </label>';
                                echo "<input type='checkbox' class='form-check-input' id='creation_rcampus' name='creation_rcampus' value='valeur2' checked disabled>";
                            }elseif ($profil_tasks['creation_rcampus'] == "Pas besoin") {
                                echo '<label style="color:red;" class="form-check-label" for="creation_rcampus">Création de RCampus </label>';
                                echo "<input type='checkbox' class='form-check-input' id='creation_rcampus' name='creation_rcampus' value='valeur2' checked disabled disabled>";
                            }
                        echo "</div>";


                        echo "<div class='form-check'>";
                            if ($profil_tasks['creation_dcsnet'] == "A faire") {
                                echo '<label class="form-check-label" for="creation_dcsnet">Création DCS Net </label>';
                                echo "<input type='checkbox' class='form-check-input' id='creation_dcsnet' name='creation_dcsnet' value='valeur1' disabled>";
                            }elseif ($profil_tasks['creation_dcsnet'] == "Terminée") {
                                echo '<label class="form-check-label" for="creation_dcsnet">Création DCS Net </label>';
                                echo "<input type='checkbox' class='form-check-input' id='creation_dcsnet' name='creation_dcsnet' value='valeur2' checked disabled>";
                            }elseif ($profil_tasks['creation_dcsnet'] == "Pas besoin") {
                                echo '<label style="color:red;" class="form-check-label" for="creation_dcsnet">Création DCS Net </label>';
                                echo "<input type='checkbox' class='form-check-input' id='creation_dcsnet' name='creation_dcsnet' value='valeur2' checked disabled>";
                            }
                        echo "</div>";

                        echo "<div class='form-check'>";
                            if ($profil_tasks['creation_tocken'] == "A faire") {
                                echo '<label class="form-check-label" for="creation_tocken">Création de tocken </label>';
                                echo "<input type='checkbox' class='form-check-input' id='creation_tocken' name='creation_tocken' value='valeur1' disabled>";
                            }elseif ($profil_tasks['creation_tocken'] == "Terminée") {
                                echo '<label class="form-check-label" for="creation_tocken">Création de tocken </label>';
                                echo "<input type='checkbox' class='form-check-input' id='creation_tocken' name='creation_tocken' value='valeur2' checked disabled>";
                            }elseif ($profil_tasks['creation_tocken'] == "Pas besoin") {
                                echo '<label style="color:red;" class="form-check-label" for="creation_tocken">Création de tocken </label>';
                                echo "<input type='checkbox' class='form-check-input' id='creation_tocken' name='creation_tocken' value='valeur2' checked disabled>";
                            }
                        echo "</div>";

                        echo "<div class='form-check'>";
                            if ($profil_tasks['attribution_telephone'] == "A faire") {
                                echo '<label class="form-check-label" for="attribution_telephone">Attribution d\'une ligne téléphonique </label>';
                                echo "<input type='checkbox' class='form-check-input' id='attribution_telephone' name='attribution_telephone' value='valeur1' disabled disabled>";
                            }elseif ($profil_tasks['attribution_telephone'] == "Terminée") {
                                echo '<label class="form-check-label" for="attribution_telephone">Attribution d\'une ligne téléphonique </label>';
                                echo "<input type='checkbox' class='form-check-input' id='attribution_telephone' name='attribution_telephone' value='valeur2' checked disabled>";
                            }elseif ($profil_tasks['attribution_telephone'] == "Pas besoin") {
                                echo '<label style="color:red;" class="form-check-label" for="attribution_telephone">Attribution d\'une ligne téléphonique </label>';
                                echo "<input type='checkbox' class='form-check-input' id='attribution_telephone' name='attribution_telephone' value='valeur2' checked disabled>";
                            }
                        echo "</div>";

                        echo "<div class='form-check'>";
                            if ($profil_tasks['preaparation_pc'] == "A faire") {
                                echo '<label class="form-check-label" for="preaparation_pc">Attribution d\'un Ordinateur </label>';
                                echo "<input type='checkbox' class='form-check-input' id='preaparation_pc' name='preaparation_pc' value='valeur1' disabled>";
                            }elseif ($profil_tasks['preaparation_pc'] == "Terminée") {
                                echo '<label class="form-check-label" for="preaparation_pc">Attribution d\'un Ordinateur </label>';
                                echo "<input type='checkbox' class='form-check-input' id='preaparation_pc' name='preaparation_pc' value='valeur2' checked disabled>";
                            }elseif ($profil_tasks['preaparation_pc'] == "Pas besoin") {
                                echo '<label style="color:red;" class="form-check-label" for="preaparation_pc">Attribution d\'un Ordinateur </label>';
                                echo "<input type='checkbox' class='form-check-input' id='preaparation_pc' name='preaparation_pc' value='valeur2' checked disabled>";
                            }
                        echo "</div>";

                        echo "<div class='form-check'>";
                            if ($profil_tasks['preparation_malette'] == "A faire") {
                                echo '<label class="form-check-label" for="preparation_malette">Attribution d\'une malette </label>';
                                echo "<input type='checkbox' class='form-check-input' id='preparation_malette' name='preparation_malette' value='valeur1' disabled>";
                            }elseif ($profil_tasks['preparation_malette'] == "Terminée") {
                                echo '<label class="form-check-label" for="preparation_malette">Attribution d\'une malette </label>';
                                echo "<input type='checkbox' class='form-check-input' id='preparation_malette' name='preparation_malette' value='valeur2' checked disabled>";
                            }elseif ($profil_tasks['preparation_malette'] == "Pas besoin") {
                                echo '<label style="color:red;" class="form-check-label" for="preparation_malette">Attribution d\'une malette </label>';
                                echo "<input type='checkbox' class='form-check-input' id='preparation_malette' name='preparation_malette' value='valeur2' checked disabled>";
                            }
                        echo "</div>";

                        echo "<div class='form-check'>";
                            if ($profil_tasks['preparation_imprimante'] == "A faire") {
                                echo '<label class="form-check-label" for="preparation_imprimante">Attribution d\'une imprimante </label>';
                                echo "<input type='checkbox' class='form-check-input' id='preparation_imprimante' name='preparation_imprimante' value='valeur1' disabled>";
                            }elseif ($profil_tasks['preparation_imprimante'] == "Terminée") {
                                echo '<label class="form-check-label" for="preparation_imprimante">Attribution d\'une imprimante </label>';
                                echo "<input type='checkbox' class='form-check-input' id='preparation_imprimante' name='preparation_imprimante' value='valeur2' checked disabled>";
                            }elseif ($profil_tasks['preparation_imprimante'] == "Pas besoin") {
                                echo '<label style="color:red;" class="form-check-label" for="preparation_imprimante">Attribution d\'une imprimante </label>';
                                echo "<input type='checkbox' class='form-check-input' id='preparation_imprimante' name='preparation_imprimante' value='valeur2' checked disabled>";
                            }
                        echo "</div>";

                        echo '<div class="row g-3" style="padding-top:10px; padding-bottom:10px">';

                            if($profil_tasks['date_envoi']==""){

                                echo '<div class="col-sm-3">';
                                    echo '<label for="date_envoi" class="form-label" disabled>Date d\'envoi équipement informatique (JJ/MM/AAAA)</label>';
                                    echo '<input type="text" class="form-control" name="date_envoi" id="date_envoi" placeholder="" value="" disabled>';
                                echo '</div>';

                            }elseif($profil_tasks['date_envoi']!=""){
                                echo '<div class="col-sm-3">';
                                    echo '<label for="date_envoi" class="form-label" disabled>Date d\'envoi équipement informatique (JJ/MM/AAAA)</label>';
                                    echo '<input type="text" class="form-control" name="date_envoi" id="date_envoi" placeholder="" value="'. date('d-m-Y', strtotime($profil_tasks['date_envoi'])) .'" disabled>';
                                echo '</div>';
                            }

                        echo '</div>';

                        echo "<div class='form-check'>";
                            if ($profil_tasks['attribution_vehicule'] == "A faire") {
                                echo '<label class="form-check-label" for="attribution_vehicule">Attribution d\'un véhicule </label>';
                                echo "<input type='checkbox' class='form-check-input' id='attribution_vehicule' name='attribution_vehicule' value='valeur1' disabled>";
                            }elseif ($profil_tasks['attribution_vehicule'] == "Terminée") {
                                echo '<label class="form-check-label" for="attribution_vehicule">Attribution d\'un véhicule </label>';
                                echo "<input type='checkbox' class='form-check-input' id='attribution_vehicule' name='attribution_vehicule' value='valeur2' checked disabled>";
                            }elseif ($profil_tasks['attribution_vehicule'] == "Pas besoin") {
                                echo '<label style="color:red;" class="form-check-label" for="attribution_vehicule">Attribution d\'un véhicule </label>';
                                echo "<input type='checkbox' class='form-check-input' id='attribution_vehicule' name='attribution_vehicule' value='valeur2' checked disabled>";
                            }
                        echo "</div>";

                        echo "<div class='form-check'>";
                            if ($profil_tasks['attribution_badge'] == "A faire") {
                                echo '<label class="form-check-label" for="attribution_badge">Attribution d\'un badge </label>';
                                echo "<input type='checkbox' class='form-check-input' id='attribution_badge' name='attribution_badge' value='valeur1' disabled>";
                            }elseif ($profil_tasks['attribution_badge'] == "Terminée") {
                                echo '<label class="form-check-label" for="attribution_badge">Attribution d\'un badge </label>';
                                echo "<input type='checkbox' class='form-check-input' id='attribution_badge' name='attribution_badge' value='valeur2' checked disabled>";
                            }elseif ($profil_tasks['attribution_badge'] == "Pas besoin") {
                                echo '<label style="color:red;" class="form-check-label" for="attribution_badge">Attribution d\'un badge </label>';
                                echo "<input type='checkbox' class='form-check-input' id='attribution_badge' name='attribution_badge' value='valeur2' checked disabled>";
                            }
                        echo "</div>";

                        echo "<div class='form-check'>";
                            if ($profil_tasks['attribution_cles'] == "A faire") {
                                echo '<label class="form-check-label" for="attribution_cles">Attribution clés des batiments </label>';
                                echo "<input type='checkbox' class='form-check-input' id='attribution_cles' name='attribution_cles' value='valeur1'>";
                            }elseif ($profil_tasks['attribution_cles'] == "Terminée") {
                                echo '<label class="form-check-label" for="attribution_cles">Attribution clés des batiments </label>';
                                echo "<input type='checkbox' class='form-check-input' id='attribution_cles' name='attribution_cles' value='valeur2' checked>";
                            }elseif ($profil_tasks['attribution_cles'] == "Pas besoin") {
                                echo '<label style="color:red;" class="form-check-label" for="attribution_cles">Attribution clés des batiments </label>';
                                echo "<input type='checkbox' class='form-check-input' id='attribution_cles' name='attribution_cles' value='valeur2' checked disabled>";
                            }
                        echo "</div>";

                        echo "<div class='form-check'>";
                            if ($profil_tasks['attribution_vetements'] == "A faire") {
                                echo '<label class="form-check-label" for="attribution_vetements">Attribution de vêtements</label>';
                                echo "<input type='checkbox' class='form-check-input' id='attribution_vetements' name='attribution_vetements' value='valeur1' disabled>";
                            }elseif ($profil_tasks['attribution_vetements'] == "Terminée") {
                                echo '<label class="form-check-label" for="attribution_vetements">Attribution de vêtements</label>';
                                echo "<input type='checkbox' class='form-check-input' id='attribution_vetements' name='attribution_vetements' value='valeur2' checked disabled>";
                            }elseif ($profil_tasks['attribution_vetements'] == "Pas besoin") {
                                echo '<label style="color:red;" class="form-check-label" for="attribution_vetements">Attribution de vêtements</label>';
                                echo "<input type='checkbox' class='form-check-input' id='attribution_vetements' name='attribution_vetements' value='valeur2' checked disabled>";
                            }
                        echo "</div>";

                        echo "<div class='card' style='width: 25%;'>";
                            echo '<div class="card-header">';
                                echo "Taille des vêtements à attribués";
                            echo '</div>';
                            echo "<div class='card-body'>";
                                echo nl2br(str_replace(', ', "\n", $profil['commentaire_vetement']));
                            echo "</div>";
                        echo "</div>";
                        echo "<div class='form-check'>";

                            if ($profil_tasks['attribution_chaussures'] == "A faire") {
                                echo '<label class="form-check-label" for="attribution_chaussures">Attribution de Chaussures</label>';
                                echo "<input type='checkbox' class='form-check-input' id='attribution_chaussures' name='attribution_chaussures' value='valeur1' disabled>";
                            }elseif ($profil_tasks['attribution_vetements'] == "Terminée") {
                                echo '<label class="form-check-label" for="attribution_vetements">Attribution de vêtements</label>';
                                echo "<input type='checkbox' class='form-check-input' id='attribution_chaussures' name='attribution_chaussures' value='valeur2' checked disabled>";
                            }elseif ($profil_tasks['attribution_vetements'] == "Pas besoin") {
                                echo '<label style="color:red;" class="form-check-label" for="attribution_chaussures">Attribution de Chaussures</label>';
                                echo "<input type='checkbox' class='form-check-input' id='attribution_chaussures' name='attribution_chaussures' value='valeur2' checked disabled>";
                            }
                        echo "</div>";
                        echo "<div class='card' style='width: 25%;'>";
                            echo '<div class="card-header">';
                                echo "Pointure";
                            echo '</div>';
                            echo "<div class='card-body'>";
                                echo nl2br(str_replace(', ', "\n", $profil['pointure']));
                            echo "</div>";
                        echo "</div>";
                    }elseif(strcasecmp($role_actuel, "achat") === 0){
                        echo "<div class='col-sm-3'>";
                            echo "<label for='attribution' class='form-label'>Check-List attribué à :</label>";
                            echo "<select class='form-select' id='attribution' name='attribution' required disabled>";
                            echo "<option value=''>Choisir...</option>";
                            if ($profil_tasks['attribue_a']=="") {
                                if ($resultatR->num_rows>0) {
                                    while ($rowd= $resultatR->fetch_assoc()) {
                                        echo "<option value='" . $rowd['id']."'>". $rowd['user_firstname'] . " " . $rowd['user_lastname'] . "</option>";
                                    }    
                                }else{
                                    echo "<option value=''>Aucun technicien trouvé</option>";
                                }
                            }else{
                                echo "<option value='' selected >". $profil_tasks['attribue_a'] ."</option>";
                                if ($resultatR->num_rows>0) {
                                    while ($rowd= $resultatR->fetch_assoc()) {

                                        if ($profil_tasks['attribue_a'] == $rowd['user_firstname'] . " " . $rowd['user_lastname']) {
                                            
                                        }else{
                                            echo "<option value='" . $rowd['id']."'>". $rowd['user_firstname'] . " " . $rowd['user_lastname'] . "</option>";
                                        }
                                    }    
                                }else{
                                    echo "<option value=''>Aucun technicien trouvé</option>";
                                }
                            }
                            echo "</select>";
                            echo '<div class="invalid-feedback">';
                                    echo 'Faite un choix valable.';
                            echo '</div>';
                        echo '</div>';

                        echo "<div class='form-check'>";
                            if ($profil_tasks['creation_mail'] == "A faire") {
                                echo '<label class="form-check-label" for="creation_mail">Creation de mail </label>';
                                echo "<input type='checkbox' class='form-check-input' id='creation_mail' name='creation_mail' value='valeur1' disabled>";
                            }elseif ($profil_tasks['creation_mail'] == "Terminée") {
                                echo '<label class="form-check-label" for="creation_mail">Creation de mail </label>';
                                echo "<input type='checkbox' class='form-check-input' id='creation_mail' name='creation_mail' value='valeur2' checked disabled>";
                            }
                            elseif ($profil_tasks['creation_mail'] == "Pas besoin") {
                                echo '<label style="color:red;" class="form-check-label" for="creation_mail">Creation de mail </label>';
                                echo "<input type='checkbox' class='form-check-input' id='creation_mail' name='creation_mail' value='valeur2' checked disabled>";
                            }
                        echo "</div>";

                        /*echo "<div class='form-check'>";
                            if ($profil_tasks['redirection_mail'] == "A faire") {
                                echo '<label class="form-check-label" for="redirection_mail">Redirection de mail </label>';
                                echo "<input type='checkbox' class='form-check-input' id='redirection_mail' name='redirection_mail' value='valeur1' disabled>";
                            }elseif ($profil_tasks['redirection_mail'] == "Terminée") {
                                echo '<label class="form-check-label" for="redirection_mail">Redirection de mail </label>';
                                echo "<input type='checkbox' class='form-check-input' id='redirection_mail' name='redirection_mail' value='valeur2' checked disabled>";
                            }elseif ($profil_tasks['redirection_mail'] == "Pas besoin") {
                                echo '<label style="color:red;" class="form-check-label" for="redirection_mail">Redirection de mail </label>';
                                echo "<input type='checkbox' class='form-check-input' id='redirection_mail' name='redirection_mail' value='valeur2' checked disabled>";
                            }
                        echo "</div>";*/

                        echo "<div class='form-check'>";
                            if ($profil_tasks['creation_rrf'] == "A faire") {
                                echo '<label class="form-check-label" for="creation_rrf">Création dans RRF/DMD </label>';
                                echo "<input type='checkbox' class='form-check-input' id='creation_rrf' name='creation_rrf' value='valeur1' disabled>";
                            }elseif ($profil_tasks['creation_rrf'] == "Terminée") {
                                echo '<label class="form-check-label" for="creation_rrf">Création dans RRF/DMD </label>';
                                echo "<input type='checkbox' class='form-check-input' id='creation_rrf' name='creation_rrf' value='valeur2' checked disabled>";
                            }
                            elseif ($profil_tasks['creation_rrf'] == "Pas besoin") {
                                echo '<label style="color:red;" class="form-check-label" for="creation_rrf">Création dans RRF/DMD </label>';
                                echo "<input type='checkbox' class='form-check-input' id='creation_rrf' name='creation_rrf' value='valeur2' checked disabled>";
                            }
                        echo "</div>";


                        echo "<div class='form-check'>";
                            if ($profil_tasks['creation_rlearning'] == "A faire") {
                                echo '<label class="form-check-label" for="creation_rlearning">Création de RLearning </label>';
                                echo "<input type='checkbox' class='form-check-input' id='creation_rlearning' name='creation_rlearning' value='valeur1' disabled>";
                            }elseif ($profil_tasks['creation_rlearning'] == "Terminée") {
                                echo '<label class="form-check-label" for="creation_rlearning">Création de RLearning </label>';
                                echo "<input type='checkbox' class='form-check-input' id='creation_rlearning' name='creation_rlearning' value='valeur2' checked disabled>";
                            }elseif ($profil_tasks['creation_rlearning'] == "Pas besoin") {
                                echo '<label style="color:red;" class="form-check-label" for="creation_rlearning">Création de RLearning </label>';
                                echo "<input type='checkbox' class='form-check-input' id='creation_rlearning' name='creation_rlearning' value='valeur2' checked disabled>";
                            }
                        echo "</div>";

                        echo "<div class='form-check'>";
                            if ($profil_tasks['creation_rcampus'] == "A faire") {
                                echo '<label class="form-check-label" for="creation_rcampus">Création de RCampus </label>';
                                echo "<input type='checkbox' class='form-check-input' id='creation_rcampus' name='creation_rcampus' value='valeur1' disabled>";
                            }elseif ($profil_tasks['creation_rcampus'] == "Terminée") {
                                echo '<label class="form-check-label" for="creation_rcampus">Création de RCampus </label>';
                                echo "<input type='checkbox' class='form-check-input' id='creation_rcampus' name='creation_rcampus' value='valeur2' checked disabled>";
                            }elseif ($profil_tasks['creation_rcampus'] == "Pas besoin") {
                                echo '<label style="color:red;" class="form-check-label" for="creation_rcampus">Création de RCampus </label>';
                                echo "<input type='checkbox' class='form-check-input' id='creation_rcampus' name='creation_rcampus' value='valeur2' checked disabled disabled>";
                            }
                        echo "</div>";


                        echo "<div class='form-check'>";
                            if ($profil_tasks['creation_dcsnet'] == "A faire") {
                                echo '<label class="form-check-label" for="creation_dcsnet">Création DCS Net </label>';
                                echo "<input type='checkbox' class='form-check-input' id='creation_dcsnet' name='creation_dcsnet' value='valeur1' disabled>";
                            }elseif ($profil_tasks['creation_dcsnet'] == "Terminée") {
                                echo '<label class="form-check-label" for="creation_dcsnet">Création DCS Net </label>';
                                echo "<input type='checkbox' class='form-check-input' id='creation_dcsnet' name='creation_dcsnet' value='valeur2' checked disabled>";
                            }elseif ($profil_tasks['creation_dcsnet'] == "Pas besoin") {
                                echo '<label style="color:red;" class="form-check-label" for="creation_dcsnet">Création DCS Net </label>';
                                echo "<input type='checkbox' class='form-check-input' id='creation_dcsnet' name='creation_dcsnet' value='valeur2' checked disabled>";
                            }
                        echo "</div>";

                        echo "<div class='form-check'>";
                            if ($profil_tasks['creation_tocken'] == "A faire") {
                                echo '<label class="form-check-label" for="creation_tocken">Création de tocken </label>';
                                echo "<input type='checkbox' class='form-check-input' id='creation_tocken' name='creation_tocken' value='valeur1' disabled>";
                            }elseif ($profil_tasks['creation_tocken'] == "Terminée") {
                                echo '<label class="form-check-label" for="creation_tocken">Création de tocken </label>';
                                echo "<input type='checkbox' class='form-check-input' id='creation_tocken' name='creation_tocken' value='valeur2' checked disabled>";
                            }elseif ($profil_tasks['creation_tocken'] == "Pas besoin") {
                                echo '<label style="color:red;" class="form-check-label" for="creation_tocken">Création de tocken </label>';
                                echo "<input type='checkbox' class='form-check-input' id='creation_tocken' name='creation_tocken' value='valeur2' checked disabled>";
                            }
                        echo "</div>";

                        echo "<div class='form-check'>";
                            if ($profil_tasks['attribution_telephone'] == "A faire") {
                                echo '<label class="form-check-label" for="attribution_telephone">Attribution d\'une ligne téléphonique </label>';
                                echo "<input type='checkbox' class='form-check-input' id='attribution_telephone' name='attribution_telephone' value='valeur1'>";
                            }elseif ($profil_tasks['attribution_telephone'] == "Terminée") {
                                echo '<label class="form-check-label" for="attribution_telephone">Attribution d\'une ligne téléphonique </label>';
                                echo "<input type='checkbox' class='form-check-input' id='attribution_telephone' name='attribution_telephone' value='valeur2' checked>";
                            }elseif ($profil_tasks['attribution_telephone'] == "Pas besoin") {
                                echo '<label style="color:red;" class="form-check-label" for="attribution_telephone">Attribution d\'une ligne téléphonique </label>';
                                echo "<input type='checkbox' class='form-check-input' id='attribution_telephone' name='attribution_telephone' value='valeur2' checked disabled>";
                            }
                        echo "</div>";

                        echo "<div class='form-check'>";
                            if ($profil_tasks['preaparation_pc'] == "A faire") {
                                echo '<label class="form-check-label" for="preaparation_pc">Attribution d\'un Ordinateur </label>';
                                echo "<input type='checkbox' class='form-check-input' id='preaparation_pc' name='preaparation_pc' value='valeur1' disabled>";
                            }elseif ($profil_tasks['preaparation_pc'] == "Terminée") {
                                echo '<label class="form-check-label" for="preaparation_pc">Attribution d\'un Ordinateur </label>';
                                echo "<input type='checkbox' class='form-check-input' id='preaparation_pc' name='preaparation_pc' value='valeur2' checked disabled>";
                            }elseif ($profil_tasks['preaparation_pc'] == "Pas besoin") {
                                echo '<label style="color:red;" class="form-check-label" for="preaparation_pc">Attribution d\'un Ordinateur </label>';
                                echo "<input type='checkbox' class='form-check-input' id='preaparation_pc' name='preaparation_pc' value='valeur2' checked disabled>";
                            }
                        echo "</div>";

                        echo "<div class='form-check'>";
                            if ($profil_tasks['preparation_malette'] == "A faire") {
                                echo '<label class="form-check-label" for="preparation_malette">Attribution d\'une malette </label>';
                                echo "<input type='checkbox' class='form-check-input' id='preparation_malette' name='preparation_malette' value='valeur1' disabled>";
                            }elseif ($profil_tasks['preparation_malette'] == "Terminée") {
                                echo '<label class="form-check-label" for="preparation_malette">Attribution d\'une malette </label>';
                                echo "<input type='checkbox' class='form-check-input' id='preparation_malette' name='preparation_malette' value='valeur2' checked disabled>";
                            }elseif ($profil_tasks['preparation_malette'] == "Pas besoin") {
                                echo '<label style="color:red;" class="form-check-label" for="preparation_malette">Attribution d\'une malette </label>';
                                echo "<input type='checkbox' class='form-check-input' id='preparation_malette' name='preparation_malette' value='valeur2' checked disabled>";
                            }
                        echo "</div>";

                        echo "<div class='form-check'>";
                            if ($profil_tasks['preparation_imprimante'] == "A faire") {
                                echo '<label class="form-check-label" for="preparation_imprimante">Attribution d\'une imprimante </label>';
                                echo "<input type='checkbox' class='form-check-input' id='preparation_imprimante' name='preparation_imprimante' value='valeur1' disabled>";
                            }elseif ($profil_tasks['preparation_imprimante'] == "Terminée") {
                                echo '<label class="form-check-label" for="preparation_imprimante">Attribution d\'une imprimante </label>';
                                echo "<input type='checkbox' class='form-check-input' id='preparation_imprimante' name='preparation_imprimante' value='valeur2' checked disabled>";
                            }elseif ($profil_tasks['preparation_imprimante'] == "Pas besoin") {
                                echo '<label style="color:red;" class="form-check-label" for="preparation_imprimante">Attribution d\'une imprimante </label>';
                                echo "<input type='checkbox' class='form-check-input' id='preparation_imprimante' name='preparation_imprimante' value='valeur2' checked disabled>";
                            }
                        echo "</div>";

                        echo '<div class="row g-3" style="padding-top:10px; padding-bottom:10px">';

                            if($profil_tasks['date_envoi']==""){

                                echo '<div class="col-sm-3">';
                                    echo '<label for="date_envoi" class="form-label" disabled>Date d\'envoi équipement informatique (JJ/MM/AAAA)</label>';
                                    echo '<input type="text" class="form-control" name="date_envoi" id="date_envoi" placeholder="" value="" disabled>';
                                echo '</div>';

                            }elseif($profil_tasks['date_envoi']!=""){
                                echo '<div class="col-sm-3">';
                                    echo '<label for="date_envoi" class="form-label" disabled>Date d\'envoi équipement informatique (JJ/MM/AAAA)</label>';
                                    echo '<input type="text" class="form-control" name="date_envoi" id="date_envoi" placeholder="" value="'. date('d-m-Y', strtotime($profil_tasks['date_envoi'])) .'" disabled>';
                                echo '</div>';
                            }

                        echo '</div>';

                        echo "<div class='form-check'>";
                            if ($profil_tasks['attribution_vehicule'] == "A faire") {
                                echo '<label class="form-check-label" for="attribution_vehicule">Attribution d\'un véhicule </label>';
                                echo "<input type='checkbox' class='form-check-input' id='attribution_vehicule' name='attribution_vehicule' value='valeur1'>";
                            }elseif ($profil_tasks['attribution_vehicule'] == "Terminée") {
                                echo '<label class="form-check-label" for="attribution_vehicule">Attribution d\'un véhicule </label>';
                                echo "<input type='checkbox' class='form-check-input' id='attribution_vehicule' name='attribution_vehicule' value='valeur2' checked>";
                            }elseif ($profil_tasks['attribution_vehicule'] == "Pas besoin") {
                                echo '<label style="color:red;" class="form-check-label" for="attribution_vehicule">Attribution d\'un véhicule </label>';
                                echo "<input type='checkbox' class='form-check-input' id='attribution_vehicule' name='attribution_vehicule' value='valeur2' checked disabled>";
                            }
                        echo "</div>";

                        echo "<div class='form-check'>";
                            if ($profil_tasks['attribution_badge'] == "A faire") {
                                echo '<label class="form-check-label" for="attribution_badge">Attribution d\'un badge </label>';
                                echo "<input type='checkbox' class='form-check-input' id='attribution_badge' name='attribution_badge' value='valeur1'>";
                            }elseif ($profil_tasks['attribution_badge'] == "Terminée") {
                                echo '<label class="form-check-label" for="attribution_badge">Attribution d\'un badge </label>';
                                echo "<input type='checkbox' class='form-check-input' id='attribution_badge' name='attribution_badge' value='valeur2' checked>";
                            }elseif ($profil_tasks['attribution_badge'] == "Pas besoin") {
                                echo '<label style="color:red;" class="form-check-label" for="attribution_badge">Attribution d\'un badge </label>';
                                echo "<input type='checkbox' class='form-check-input' id='attribution_badge' name='attribution_badge' value='valeur2' checked disabled>";
                            }
                        echo "</div>";

                        echo "<div class='form-check'>";
                            if ($profil_tasks['attribution_cles'] == "A faire") {
                                echo '<label class="form-check-label" for="attribution_cles">Attribution clés des batiments </label>';
                                echo "<input type='checkbox' class='form-check-input' id='attribution_cles' name='attribution_cles' value='valeur1' disabled>";
                            }elseif ($profil_tasks['attribution_cles'] == "Terminée") {
                                echo '<label class="form-check-label" for="attribution_cles">Attribution clés des batiments </label>';
                                echo "<input type='checkbox' class='form-check-input' id='attribution_cles' name='attribution_cles' value='valeur2' checked disabled>";
                            }elseif ($profil_tasks['attribution_cles'] == "Pas besoin") {
                                echo '<label style="color:red;" class="form-check-label" for="attribution_cles">Attribution clés des batiments </label>';
                                echo "<input type='checkbox' class='form-check-input' id='attribution_cles' name='attribution_cles' value='valeur2' checked disabled>";
                            }
                        echo "</div>";

                        echo "<div class='form-check'>";
                            if ($profil_tasks['attribution_vetements'] == "A faire") {
                                echo '<label class="form-check-label" for="attribution_vetements">Attribution de vêtements</label>';
                                echo "<input type='checkbox' class='form-check-input' id='attribution_vetements' name='attribution_vetements' value='valeur1'>";
                            }elseif ($profil_tasks['attribution_vetements'] == "Terminée") {
                                echo '<label class="form-check-label" for="attribution_vetements">Attribution de vêtements</label>';
                                echo "<input type='checkbox' class='form-check-input' id='attribution_vetements' name='attribution_vetements' value='valeur2' checked>";
                            }elseif ($profil_tasks['attribution_vetements'] == "Pas besoin") {
                                echo '<label style="color:red;" class="form-check-label" for="attribution_vetements">Attribution de vêtements</label>';
                                echo "<input type='checkbox' class='form-check-input' id='attribution_vetements' name='attribution_vetements' value='valeur2' checked disabled>";
                            }
                        echo "</div>";

                        echo "<div class='card' style='width: 25%;'>";
                            echo '<div class="card-header">';
                                echo "Taille des vêtements à attribués";
                            echo '</div>';
                            echo "<div class='card-body'>";
                                echo nl2br(str_replace(', ', "\n", $profil['commentaire_vetement']));
                            echo "</div>";
                        echo "</div>";
                        echo "<div class='form-check'>";

                            if ($profil_tasks['attribution_chaussures'] == "A faire") {
                                echo '<label class="form-check-label" for="attribution_chaussures">Attribution de Chaussures</label>';
                                echo "<input type='checkbox' class='form-check-input' id='attribution_chaussures' name='attribution_chaussures' value='valeur1'>";
                            }elseif ($profil_tasks['attribution_vetements'] == "Terminée") {
                                echo '<label class="form-check-label" for="attribution_vetements">Attribution de vêtements</label>';
                                echo "<input type='checkbox' class='form-check-input' id='attribution_chaussures' name='attribution_chaussures' value='valeur2' checked>";
                            }elseif ($profil_tasks['attribution_vetements'] == "Pas besoin") {
                                echo '<label style="color:red;" class="form-check-label" for="attribution_chaussures">Attribution de Chaussures</label>';
                                echo "<input type='checkbox' class='form-check-input' id='attribution_chaussures' name='attribution_chaussures' value='valeur2' checked disabled>";
                            }
                        echo "</div>";
                        echo "<div class='card' style='width: 25%;'>";
                            echo '<div class="card-header">';
                                echo "Pointure";
                            echo '</div>';
                            echo "<div class='card-body'>";
                                echo nl2br(str_replace(', ', "\n", $profil['pointure']));
                            echo "</div>";
                        echo "</div>";
                    }

                    echo '<button style="margin-top: 30px; margin-bottom: 20px;background:#EF7837;color:white;" class="w-20 btn btn-lg" type="submit">Sauvegarder</button>';
                echo "</form>";
            ?>

        </section>

        <!--Pied de page-->
        <footer class="bg-dark">
            <p>&copy; 2025 JCMI - Version 2.0 | Tous droits réservés.</p>
            <p>Support : informatiques@groupemichel.com | Tél : 1350 | <a style="color:#EF7837;" href="http://sos-informatique.gm.local" target="_blank">SOS INFORMATIQUE</a></p>
        </footer>


    </body>

</html>