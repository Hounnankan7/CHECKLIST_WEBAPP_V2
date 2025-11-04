<?php
    session_start();
    include_once '../../01_includes/dbconnect.php';
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>SUIVI CHECK LIST - ADMINISTRATEUR</title>

        <!-- Telechargement de bootstrap et bootstrap icon -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
        <script src="https://kit.fontawesome.com/857f401658.js" crossorigin="anonymous"></script>
        <link rel="shortcut icon" type="image/png" href="../../01_assets/faviconV2.png"/>

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
            $configFile = '../../01_configFiles/databaseLink.txt';

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
        ?>

        <!--Entete de la page-->
        <header class="sticky-top">
            <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
                <div class="container-fluid">
                    
                    <a class="navbar-brand" href="#" style="width: 20%;"><img class="logo" src="../../01_assets/LOGO_BLANC.png" alt="JCMI" title="JCMI" width=60% height=30%></a>

                    <ul class="navbar-nav">
                        <li class="nav-item"><a class="nav-link active" href="admin_systemeadmin.php"><i class="fa-solid fa-user-gear fa-xl"></i>Administration Système</a></li>
                        <li class="nav-item"><a class="nav-link active" href="admin_homepage.php"><i class="fa-solid fa-person-walking-arrow-right fa-xl"></i> Suivi Entrées</a></li>
                        <li class="nav-item"><a class="nav-link active" href="admin_closedentry.php"><i class="fa-solid fa-list-check fa-xl"></i> Entrées Terminées</a></li>
                        <li class="nav-item"><a class="nav-link active" href="admin_outmanager.php"><i class="fa-solid fa-person-walking-arrow-right fa-flip-horizontal fa-xl"></i>Sorties & Retours Matériels</a></li>
                        <li class="nav-item"><a class="nav-link active" href="<?php echo $config['db_link']?>" target="_blank"><i class="fa-solid fa-database fa-xl"></i>Administration BDD</a></li>
                        <li class="nav-item"><a class="nav-link active" href="../deconnexion.php"><i class="fa-solid fa-light fa-power-off fa-xl"></i> Déconnexion</a></li>
                    </ul>

                </div>
            </nav>
        </header>

        <div class="user_connected" style="background:#EF7837;">
            <p style="color: white;margin-left:12px;margin-right:12px;padding-top:5px;padding-bottom:5px;font-size:18px;"> 
                <?php echo 'Utilisateur connecté : <b>' . $_SESSION['user_firstname'] . ' ' . $_SESSION['user_lastname'] . '</b>'; ?>
            </p>
        </div>

        <section>

            <div style="height: 50px;color: #EF7837;">
            <a href='#' class='openPopup_addNewUser' style="color: #EF7837;"><i class='fa-regular fa-address-book fa-2xl' style='color: #EF7837;margin:8px;width:30px;'></i>Ajouter un Utilisateur</a>
            </div>

            <?php
        
                $deleteUser_query_state = $_GET['delete_UserState'];
                if (strcasecmp($deleteUser_query_state, "success") === 0) {
                    echo '<div class="alert alert-success" style="width:674px;margin-left:16px;" role="alert">';
                        echo 'Utilisateur supprimé.';
                        echo '<a class="closer_alert" href="admin_systemeadmin.php" style="float:right;"><i class="fa-solid fa-x"></i></a>';
                    echo '</div>';
                }elseif (strcasecmp($deleteUser_query_state, "fail") === 0){
                    echo '<div class="alert alert-danger" style="width:674px;margin-left:16px;" role="alert">';
                        echo "L'utilisateur n'a pas pu etre supprimé.";
                        echo '<a class="closer_alert" href="admin_systemeadmin.php" style="float:right;"><i class="fa-solid fa-x"></i></a>';
                    echo '</div>';
                }

            ?>


            <div class="accordion" id="accordionPanelsStayOpenExample">
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne">
                            LISTE DES SITES D'EMBAUCHES
                        </button>
                    </h2>
                    <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse show">
                        <div class="accordion-body">
                            <table class="table table-hover table-bordered" style="width: 50%; margin-bottom: 20px;">

                                <tr class="table-dark">
                                    <th style="width: 10%">Sites</th>
                                    <th style="width: 1%">Editer</th>
                                </tr>
                                <?php
                                    $list_site = mysqli_query($database_connect, "SELECT * FROM site_embauche_table ORDER BY intitule_site ASC");
                                    while ($row=$list_site->fetch_assoc()) {
                                        $id_site=$row['id'];
                                        echo '<tr style="height: 40%;">';
                                            echo "<td>". $row['intitule_site'] ."</td>";
                                            echo "<td><a href='#' class='openPopup_modifySite' data-id_site='$id_site'><i class='fa-regular fa-pen-to-square' style='color: #EF7837;'></i></a></td>";
                                        echo '</tr>';
                                    }
                                ?>
                            </table>   
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseTwo" aria-expanded="false" aria-controls="panelsStayOpen-collapseTwo">
                        LISTE DES SERVICES
                    </button>
                    </h2>
                    <div id="panelsStayOpen-collapseTwo" class="accordion-collapse collapse">
                        <div class="accordion-body">

                            <table class="table table-hover table-bordered" style="width: 50%; margin-bottom: 20px;">

                                <tr class="table-dark">
                                    <th style="width: 10%">Services</th>
                                    <th style="width: 1%">Editer</th>
                                </tr>
                                <?php
                                    $list_service = mysqli_query($database_connect, "SELECT * FROM services_table ORDER BY id ASC");
                                    while ($row=$list_service->fetch_assoc()) {
                                        $id_service=$row['id'];
                                        echo '<tr style="height: 40%;">';
                                            echo "<td>". $row['intitule_service'] ."</td>";
                                            echo "<td><a href='#' class='openPopup_modifyService' data-id_service='$id_service'><i class='fa-regular fa-pen-to-square' style='color: #EF7837;'></i></a></td>";
                                        echo '</tr>';
                                    }
                                ?>
                            </table>   

                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseThree" aria-expanded="false" aria-controls="panelsStayOpen-collapseThree">
                        LISTES DES UTILISATEURS
                    </button>
                    </h2>
                    <div id="panelsStayOpen-collapseThree" class="accordion-collapse collapse">
                        <div class="accordion-body">
                            <table class="table table-hover table-bordered" style="width: 90%; margin-bottom: 20px;">

                                <tr class="table-dark">
                                    <th style="width: 26%">Noms</th>
                                    <th style="width: 10%">Role</th>
                                    <th style="width: 22%">Mail</th>
                                    <th style="width: 5%">Editer</th>
                                    <th style="width: 4%">Supprimer</th>
                                </tr>
                                <?php
                                    $list_user = mysqli_query($database_connect, "SELECT * FROM users_table ORDER BY user_role ASC");
                                    while ($row=$list_user->fetch_assoc()) {
                                        $id_user=$row['id'];
                                        echo '<tr style="height: 40%;">';
                                            echo "<td>". $row['user_lastname'] . " " .$row['user_firstname'] ."</td>";
                                            echo "<td>".$row['user_role']."</td>";
                                            echo "<td>".$row['user_email']."</td>"; 

                                            echo "<td><a href='#' class='openPopup_modifyUser' data-id_user='$id_user'><i class='fa-regular fa-pen-to-square' style='color: #EF7837;'></i></a></td>";
                                            echo "<td><a href='delete_user.php?id_user=$id_user'><i class='fa-regular fa-trash-can' style='color: #EF7837;'></i></a></td>";
                
                                        echo '</tr>';
                                    }
                                ?>
                            </table>   
                        </div>
                    </div>
                </div>

            </div>
        </section>

        <!--Pied de page-->
        <footer class="bg-dark">
            <p>&copy; 2025 JCMI - Version 2.0 | Tous droits réservés.</p>
            <p>Support : informatiques@groupemichel.com | Tél : 1350 | <a style="color:#EF7837;" href="http://sos-informatique.gm.local" target="_blank">SOS INFORMATIQUE</a></p>
        </footer>

        <!-- Inclusion de jQuery et Bootstrap JS Bundle -->
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

        <script>
            //--------------------------------------Script AJOUT NOUVEAU-----------------------------------------------------------
            // Sélectionne tous les liens ajout user
            document.querySelectorAll('.openPopup_addNewUser').forEach(function(link) {
                link.addEventListener('click', function(e) {
                    e.preventDefault();

                    // Construit l'URL de la pop-up
                    var url = 'admin_addNewUser.php';
                    
                    // Ouvre la pop-up dans une nouvelle fenêtre
                    window.open(url, 'popUp', 'width=500,height=5800,resizable=no');
                });
            });

            /* Sélectionne tous les liens ajout service
            document.querySelectorAll('.openPopup_addNewService').forEach(function(link) {
                link.addEventListener('click', function(e) {
                    e.preventDefault();

                    // Construit l'URL de la pop-up avec le paramètre GET
                    var url = 'admin_addNewService.php';
                    
                    // Ouvre la pop-up dans une nouvelle fenêtre
                    window.open(url, 'popUp', 'width=500,height=5800,resizable=no');
                });
            });

            // Sélectionne tous les liens ajout site embauche
            document.querySelectorAll('.openPopup_addNewSiteEmbauche').forEach(function(link) {
                link.addEventListener('click', function(e) {
                    e.preventDefault();

                    // Construit l'URL de la pop-up 
                    var url = 'admin_addNewSiteEmbauche.php';
                    
                    // Ouvre la pop-up dans une nouvelle fenêtre
                    window.open(url, 'popUp', 'width=500,height=500,resizable=no');
                });
            });*/

            //--------------------------------------Script MODIFICATION -----------------------------------------------------------
            // Sélectionne tous les liens de modification
            document.querySelectorAll('.openPopup_modifyUser').forEach(function(link) {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    // Récupère la valeur id depuis l'attribut data
                    var idValue = this.getAttribute('data-id_user');
                    
                    // Construit l'URL de la pop-up avec le paramètre GET
                    var url = 'modify_user.php?id_User=' + idValue;
                    
                    // Ouvre la pop-up dans une nouvelle fenêtre
                    window.open(url, 'popUp', 'width=500,height=500,resizable=no');
                });
            });

            // Sélectionne tous les liens de modification
            document.querySelectorAll('.openPopup_modifyService').forEach(function(link) {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    // Récupère la valeur id depuis l'attribut data
                    var idValue = this.getAttribute('data-id_service');
                    
                    // Construit l'URL de la pop-up avec le paramètre GET
                    var url = 'modify_Service.php?id_Service=' + idValue;
                    
                    // Ouvre la pop-up dans une nouvelle fenêtre
                    window.open(url, 'popUp', 'width=500,height=500,resizable=no');
                });
            });

            // Sélectionne tous les liens de modification
            document.querySelectorAll('.openPopup_modifySite').forEach(function(link) {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    // Récupère la valeur id depuis l'attribut data
                    var idValue = this.getAttribute('data-id_site');
                    
                    // Construit l'URL de la pop-up avec le paramètre GET
                    var url = 'modify_Site.php?id_Site=' + idValue;
                    
                    // Ouvre la pop-up dans une nouvelle fenêtre
                    window.open(url, 'popUp', 'width=500,height=500,resizable=no');
                });
            });

        </script>

    </body>

</html>