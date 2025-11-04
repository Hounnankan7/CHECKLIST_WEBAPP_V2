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

            <h4 style="margin-top: 40px; margin-bottom: 30px;"><u>LISTE DES NOVATIONS</u></h4>
            <?php
            // Gestion des actions sur les équipements NOVATION
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_equipement_novation']) && isset($_POST['id_sortie_novation']) && isset($_POST['equipement_type_novation'])) {
                $id_sortie = intval($_POST['id_sortie_novation']);
                $equipement_type = $_POST['equipement_type_novation'];
                // Mettre à jour la colonne correspondante à 'Terminer'
                $sql_update = "UPDATE sorties_table SET $equipement_type = 'Terminer' WHERE id_sortie = $id_sortie";
                mysqli_query($database_connect, $sql_update);
                // Vérifier si tous les équipements sont 'Terminer'
                $sql_check = "SELECT out_mail, out_tocken, out_dcsnet, out_pc, out_malette, out_imprimante FROM sorties_table WHERE id_sortie = $id_sortie";
                $result_check = mysqli_query($database_connect, $sql_check);
                if ($result_check) {
                    $equip = mysqli_fetch_assoc($result_check);
                    $all_done = true;
                    foreach ($equip as $val) {
                        if ($val !== 'Terminer') {
                            $all_done = false;
                            break;
                        }
                    }
                    if ($all_done) {
                        $sql_state = "UPDATE sorties_table SET state_equipement = 1 WHERE id_sortie = $id_sortie";
                        mysqli_query($database_connect, $sql_state);
                    }
                }
                // Rafraîchir la page
                echo '<meta http-equiv="refresh" content="0">';
            }

            // Pagination NOVATIONS
            $nov_page = isset($_GET['nov_page']) ? max(1, intval($_GET['nov_page'])) : 1;
            $nov_limit = 15;
            $nov_offset = ($nov_page - 1) * $nov_limit;
            $nov_query_count = "SELECT COUNT(*) as total FROM sorties_table WHERE type_sortie='NOVATION' AND state_equipement = 0";
            $nov_count_res = mysqli_query($database_connect, $nov_query_count);
            $nov_total = mysqli_fetch_assoc($nov_count_res)['total'];
            $nov_total_pages = ceil($nov_total / $nov_limit);
            $list_out = mysqli_query($database_connect, "SELECT * FROM sorties_table WHERE type_sortie='NOVATION' AND state_equipement = 0 ORDER BY date_sortie ASC LIMIT $nov_limit OFFSET $nov_offset");
            ?>
            <table class="table table-hover table-bordered" style="width: 100%; margin-bottom: 40px;font-size: 14px;">
                <tr class="table-dark" style="font-size: 14px;">
                    <th style="width: 8%">Société</th>
                    <th style="width: 15%">Nom</th>
                    <th style="width: 10%">Service</th>
                    <th style="width: 15%">Fonction</th>
                    <th style="width: 8%">Equipement</th>
                    <th style="width: 7%">Date de sortie</th>
                </tr>
                    <?php

                    while ($row=$list_out->fetch_assoc()) {
                        $id_out=$row['id_sortie'];
                        $date_clo = date('d-m-Y', strtotime($row['date_sortie']));
                        //Affichage de la liste des postes
                        echo '<tr style="height: 40%; vertical-align: middle;">';
                            echo "<td style='height: 20px; vertical-align: middle;'>" . $row['societe'] . "</td>";
                            echo "<td style='vertical-align: middle;'>" . $row['lastname']." ".$row['firstname'] . "</td>";
                            echo "<td style='vertical-align: middle;'>". $row['services'] ."</td>";

                            echo "<td style='height: 20px; vertical-align: middle;'>" . $row['fonction'] . "</td>"; 
                    ?>
                            <td style='vertical-align: middle;'>
                                <?php 
                                $equipements = [
                                    'out_mail' => ['img' => 'fa-mail.png', 'img_done' => 'fa-mail - Copie.png', 'alt' => 'Supprimer mail', 'title' => 'Supprimer mail'],
                                    'out_tocken' => ['img' => 'fa-tocken.png', 'img_done' => 'fa-tocken - Copie.png', 'alt' => 'Récuperer tocken', 'title' => 'Récuperer tocken'],
                                    'out_dcsnet' => ['img' => 'fa-dcsnet.png', 'img_done' => 'fa-dcsnet - Copie.png', 'alt' => 'Supprimer profil DCSNet', 'title' => 'Supprimer profil DCSNet'],
                                    'out_pc' => ['img' => 'fa-pc.png', 'img_done' => 'fa-pc - Copie.png', 'alt' => 'Récupérer PC', 'title' => 'Récupérer PC'],
                                    'out_malette' => ['img' => 'fa-malette.png', 'img_done' => 'fa-malette - Copie.png', 'alt' => 'Récuperer malette', 'title' => 'Récuperer malette'],
                                    'out_imprimante' => ['img' => 'fa-imprimante.png', 'img_done' => 'fa-imprimante - Copie.png', 'alt' => 'Récupérer imprimante', 'title' => 'Récupérer imprimante'],
                                ];
                                foreach ($equipements as $type => $info) {
                                    if ($row[$type] == "A faire" || $row[$type] == "A verifier") {
                                        echo '<form method="POST" style="display:inline;">';
                                        echo '<input type="hidden" name="update_equipement_novation" value="1">';
                                        echo '<input type="hidden" name="id_sortie_novation" value="' . $row['id_sortie'] . '">';
                                        echo '<input type="hidden" name="equipement_type_novation" value="' . $type . '">';
                                        echo '<button type="submit" style="border:none;background:none;padding:0;margin:8px;">';
                                        echo '<img class="icon_1" src="../../01_assets/' . $info['img'] . '" alt="' . $info['alt'] . '" title="' . $info['title'] . '">';
                                        echo '</button>';
                                        echo '</form>';
                                    } else {
                                        echo '<img class="icon_1" src="../../01_assets/' . $info['img_done'] . '" alt="' . $info['alt'] . '" title="' . $info['title'] . '" style="margin:8px;">';
                                    }
                                }
                                ?>
                            </td>

                    <?php        
                            echo "<td>". $date_clo ."</td>";
                        echo '</tr>';

                    }
                    ?>
            </table>
            <div class="pagination">
                <?php
                for ($i = 1; $i <= $nov_total_pages; $i++) {
                    if ($i == $nov_page) {
                        echo "<strong>$i</strong> ";
                    } else {
                        echo "<a href='?nov_page=$i&sort_page=" . (isset($_GET['sort_page']) ? intval($_GET['sort_page']) : 1) . "'>$i</a> ";
                    }
                }
                ?>
            </div>

            <h4 style="margin-top: 40px; margin-bottom: 30px;"><u>LISTE DES SORTIES</u></h4>
            <?php
            // Gestion des actions sur les équipements
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_equipement']) && isset($_POST['id_sortie']) && isset($_POST['equipement_type'])) {
                $id_sortie = intval($_POST['id_sortie']);
                $equipement_type = $_POST['equipement_type'];
                // Mettre à jour la colonne correspondante à 'Terminer'
                $sql_update = "UPDATE sorties_table SET $equipement_type = 'Terminer' WHERE id_sortie = $id_sortie";
                mysqli_query($database_connect, $sql_update);
                // Vérifier si tous les équipements sont 'Terminer'
                $sql_check = "SELECT out_mail, out_tocken, out_dcsnet, out_pc, out_malette, out_imprimante FROM sorties_table WHERE id_sortie = $id_sortie";
                $result_check = mysqli_query($database_connect, $sql_check);
                if ($result_check) {
                    $equip = mysqli_fetch_assoc($result_check);
                    $all_done = true;
                    foreach ($equip as $val) {
                        if ($val !== 'Terminer') {
                            $all_done = false;
                            break;
                        }
                    }
                    if ($all_done) {
                        $sql_state = "UPDATE sorties_table SET state_equipement = 1 WHERE id_sortie = $id_sortie";
                        mysqli_query($database_connect, $sql_state);
                    }
                }
                // Rafraîchir la page
                echo '<meta http-equiv="refresh" content="0">';
            }

            // Pagination SORTIES
            $sort_page = isset($_GET['sort_page']) ? max(1, intval($_GET['sort_page'])) : 1;
            $sort_limit = 15;
            $sort_offset = ($sort_page - 1) * $sort_limit;
            $sort_query_count = "SELECT COUNT(*) as total FROM sorties_table WHERE type_sortie='SORTIE' AND state_equipement = 0";
            $sort_count_res = mysqli_query($database_connect, $sort_query_count);
            $sort_total = mysqli_fetch_assoc($sort_count_res)['total'];
            $sort_total_pages = ceil($sort_total / $sort_limit);
            $list_out = mysqli_query($database_connect, "SELECT * FROM sorties_table WHERE type_sortie='SORTIE' AND state_equipement = 0 ORDER BY date_sortie ASC LIMIT $sort_limit OFFSET $sort_offset");
            ?>
            <table class="table table-hover table-bordered" style="width: 100%; margin-bottom: 40px;font-size: 14px;">
                <tr class="table-dark" style="font-size: 14px;">
                    <th style="width: 8%">Société</th>
                    <th style="width: 15%">Nom</th>
                    <th style="width: 10%">Service</th>
                    <th style="width: 15%">Fonction</th>
                    <th style="width: 8%">Equipement</th>
                    <th style="width: 7%">Date de sortie</th>
                </tr>
                <?php

                    while ($row=$list_out->fetch_assoc()) {
                        $id_out=$row['id_sortie'];
                        $date_clo = date('d-m-Y', strtotime($row['date_sortie']));
                        //Affichage de la liste des postes
                        echo '<tr style="height: 40%; vertical-align: middle;">';
                            echo "<td style='height: 10px; vertical-align: middle;'>" . $row['societe'] . "</td>";
                            echo "<td style='vertical-align: middle;'>" . $row['lastname']." ".$row['firstname'] . "</td>";
                            echo "<td style='vertical-align: middle;'>". $row['services'] ."</td>";

                            echo "<td style='height: 10px; vertical-align: middle;'>" . $row['fonction'] . "</td>"; 
                ?>
                            <td style='vertical-align: middle;'>
                                <?php 
                                $equipements = [
                                    'out_mail' => ['img' => 'fa-mail.png', 'img_done' => 'fa-mail - Copie.png', 'alt' => 'Supprimer mail', 'title' => 'Supprimer mail'],
                                    'out_tocken' => ['img' => 'fa-tocken.png', 'img_done' => 'fa-tocken - Copie.png', 'alt' => 'Récuperer tocken', 'title' => 'Récuperer tocken'],
                                    'out_dcsnet' => ['img' => 'fa-dcsnet.png', 'img_done' => 'fa-dcsnet - Copie.png', 'alt' => 'Supprimer profil DCSNet', 'title' => 'Supprimer profil DCSNet'],
                                    'out_pc' => ['img' => 'fa-pc.png', 'img_done' => 'fa-pc - Copie.png', 'alt' => 'Récupérer PC', 'title' => 'Récupérer PC'],
                                    'out_malette' => ['img' => 'fa-malette.png', 'img_done' => 'fa-malette - Copie.png', 'alt' => 'Récuperer malette', 'title' => 'Récuperer malette'],
                                    'out_imprimante' => ['img' => 'fa-imprimante.png', 'img_done' => 'fa-imprimante - Copie.png', 'alt' => 'Récupérer imprimante', 'title' => 'Récupérer imprimante'],
                                ];
                                foreach ($equipements as $type => $info) {
                                    if ($row[$type] == "A faire" || $row[$type] == "A verifier") {
                                        echo '<form method="POST" style="display:inline;">';
                                        echo '<input type="hidden" name="update_equipement" value="1">';
                                        echo '<input type="hidden" name="id_sortie" value="' . $row['id_sortie'] . '">';
                                        echo '<input type="hidden" name="equipement_type" value="' . $type . '">';
                                        echo '<button type="submit" style="border:none;background:none;padding:0;margin:8px;">';
                                        echo '<img class="icon_1" src="../../01_assets/' . $info['img'] . '" alt="' . $info['alt'] . '" title="' . $info['title'] . '">';
                                        echo '</button>';
                                        echo '</form>';
                                    } else {
                                        echo '<img class="icon_1" src="../../01_assets/' . $info['img_done'] . '" alt="' . $info['alt'] . '" title="' . $info['title'] . '" style="margin:8px;">';
                                    }
                                }
                                ?>
                            </td>

                <?php        
                           // echo "<td><a href='#' class='' data-id_service=''><i class='fa-solid fa-computer fa-xl' style='color: #EF7837;'></i></a></td>";

                            echo "<td>". $date_clo ."</td>";
                        echo '</tr>';

                    }
                ?>
            </table>
            <div class="pagination">
                <?php
                for ($i = 1; $i <= $sort_total_pages; $i++) {
                    if ($i == $sort_page) {
                        echo "<strong>$i</strong> ";
                    } else {
                        echo "<a href='?sort_page=$i&nov_page=" . (isset($_GET['nov_page']) ? intval($_GET['nov_page']) : 1) . "'>$i</a> ";
                    }
                }
                ?>
            </div>


        </section>

        <!--Pied de page-->
        <footer class="bg-dark">
            <p>&copy; 2025 JCMI - Version 2.0 | Tous droits réservés.</p>
            <p>Support : informatiques@groupemichel.com | Tél : 1350 | <a style="color:#EF7837;" href="http://sos-informatique.gm.local" target="_blank">SOS INFORMATIQUE</a></p>
        </footer>
    </body>

</html>