<?php
    session_start();
    include_once '../../01_includes/dbconnect.php';
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>SUIVI CHECK LIST - RH</title>

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

            .pagination {
                display: flex;
                justify-content: center; /* centre les liens horizontalement */
                flex-wrap: wrap;
                gap: 10px; /* espace entre les liens */
                margin-top: 20px;
            }

            .pagination a,
            .pagination strong {
                padding: 6px 12px;
                text-decoration: none;
                border: 1px solid #ccc;
                border-radius: 5px;
                color: #333;
            }

            .pagination strong {
                background-color: #f0f0f0;
                font-weight: bold;
            }

        </style>

    </head>

    <body>

        <!--Entete de la page-->
        <header class="sticky-top">
            <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
                <div class="container-fluid">
                    
                    <a class="navbar-brand" href="#" style="width: 20%;"><img class="logo" src="../../01_assets/LOGO_BLANC.png" alt="JCMI" title="JCMI" width=60% height=30%></a>

                    <ul class="navbar-nav">
                        <li class="nav-item"><a class="nav-link active" href="rh_add_poste.php"><i class="fa-solid fa-file-pen fa-xl"></i> Fiches RNQSA</a></li>
                        <li class="nav-item"><a class="nav-link active" href="rh_add_checklist.php"><i class="fa-solid fa-person-through-window fa-xl"></i> Ajouter Entrée</a></li>
                        <li class="nav-item"><a class="nav-link active" href="rh_homepage.php"><i class="fa-solid fa-person-walking-arrow-right fa-xl"></i> Suivi Entrées</a></li>
                        <li class="nav-item"><a class="nav-link active" href="rh_closedentry.php"><i class="fa-solid fa-list-check fa-xl"></i> Entrées Terminées</a></li>
                        <li class="nav-item"><a class="nav-link active" href="rh_outmanager.php"><i class="fa-solid fa-person-walking-arrow-right fa-flip-horizontal fa-xl"></i> Gestion des Sorties</a></li>
                        <li class="nav-item"><a class="nav-link active" href="../deconnexion.php"><i class="fa-solid fa-light fa-power-off fa-xl"></i> Déconnexion</a></li>
                    </ul>

                </div>
            </nav>
        </header>

        <section>

            <h4 style='margin-top:20px;margin-bottom:20px;'><u>AJOUT D'UNE SORTIE</u></h4>

            <form class="container" style="margin:0px; padding-top:20px; padding-bottom:15px" id="add_out_form" method="POST" action="add_new_out.php">
                <div class="row g-4">

                    <div class="col-sm-3">
                        <label for="out_date" class="form-label">Date de sortie *</label>
                        <input type="date" class="form-control" name="out_date" id="out_date" placeholder="" value="" required>
                    </div>
                    <div class="col-sm-3">
                        <label for="s_e" class="form-label">Société - Etablissement*</label>
                        <select class="form-select" id="s_e" name="s_e" required>
                            <option value="">Choisir...</option>
                            <?php
                                //Requete récupération des services
                                $site_list = mysqli_query($database_connect, "SELECT * FROM site_embauche_table");
                                while($row = mysqli_fetch_assoc($site_list)){
                                    echo "<option>".$row['intitule_site']."</option>";
                                }
                            ?>
                        </select>
                        <div class="invalid-feedback">
                            Sélectionner un site valable.
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <label for="nom" class="form-label">Nom *</label>
                        <input type="text" class="form-control" id="nom" name="nom" placeholder="" value="" required>
                    </div>
                    <div class="col-sm-3">
                        <label for="prenom" class="form-label">Prénom *</label>
                        <input type="text" class="form-control" id="prenom" name="prenom" placeholder="" value="" required>
                    </div>

                </div>
                <div class="row g-4">
                    <div class="col-sm-3">
                            <label for="service" class="form-label">Service *</label>
                            <select class="form-select" id="service" name="service" required>
                                <option value="">Choisir...</option>
                                <?php
                                    //Requete récupération des services
                                    $service_list = mysqli_query($database_connect, "SELECT * FROM services_table");
                                    while($row = mysqli_fetch_assoc($service_list)){
                                        echo "<option>".$row['intitule_service']."</option>";
                                    }
                                ?>
                            </select>
                            <div class="invalid-feedback">
                                Sélectionner un service valable.
                            </div>
                    </div>


                    <div class="col-sm-3">
                        <label for="contrat" class="form-label">Type de contrat *</label>
                        <select class="form-select" id="contrat" name="contrat" required>
                            <option value="">Choisir...</option>
                            <option>CDI</option>
                            <option>CDD</option>
                            <option>Stage</option>
                            <option>Apprentissage</option>
                            <option>Contrat de professionalisation</option>
                            <option>Intérim</option>
                        </select>
                        <div class="invalid-feedback">
                            Sélectionner un service valable.
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <label for="cat_label" class="form-label">Fonction *</label>
                        <input type="text" class="form-control" id="fonction" name="fonction" placeholder="" value="" required>
                    </div>
                    <div class="col-sm-3">
                        <label for="sortie_type" class="form-label">Type de sortie *</label>
                        <select class="form-select" id="sortie_type" name="sortie_type" required>
                            <option value="">Choisir...</option>
                            <option>Novation</option>
                            <option>Sortie</option>
                        </select>
                        <div class="invalid-feedback">
                            Sélectionner un service valable.
                        </div>
                    </div>
                </div>
                <button style="margin-top: 30px;background:#EF7837;color:white;" class="w-20 btn btn-lg" type="submit">Ajouter</button>

            </form>

            <h4 style="margin-top: 40px; margin-bottom: 30px;"><u>LISTE DES NOVATIONS</u></h4>
            <?php
            // Pagination NOVATIONS
            $nov_page = isset($_GET['nov_page']) ? max(1, intval($_GET['nov_page'])) : 1;
            $nov_limit = 15;
            $nov_offset = ($nov_page - 1) * $nov_limit;
            $nov_query_count = "SELECT COUNT(*) as total FROM sorties_table WHERE type_sortie='NOVATION' AND (state_equipement=0 OR state_achat=0 OR state_batiment=0)";
            $nov_count_res = mysqli_query($database_connect, $nov_query_count);
            $nov_total = mysqli_fetch_assoc($nov_count_res)['total'];
            $nov_total_pages = ceil($nov_total / $nov_limit);
            $list_out = mysqli_query($database_connect, "SELECT * FROM sorties_table WHERE type_sortie='NOVATION' AND (state_equipement=0 OR state_achat=0 OR state_batiment=0) ORDER BY date_sortie ASC LIMIT $nov_limit OFFSET $nov_offset");
            ?>
            <table class="table table-hover table-bordered" style="width: 100%; margin-bottom: 40px;font-size: 14px;">
                <tr class="table-dark" style="font-size: 14px;">
                    <th style="width: 10%">Société</th>
                    <th style="width: 15%">Nom</th>
                    <th style="width: 15%">Service</th>
                    <th style="width: 15%">Fonction</th>
                    <th style="width: 8%">Type de contrat</th>
                    <th style="width: 8%">Date de sortie</th>
                </tr>
                <?php
                while ($row=$list_out->fetch_assoc()) {
                    $id_out=$row['id_sortie'];
                    $date_sortie = date("d-m-Y", strtotime($row['date_sortie']));
                    echo '<tr style="height: 40%;">';
                        echo "<td style='height: 20px';>" . $row['societe'] . "</td>";
                        echo "<td>" . $row['lastname']." ".$row['firstname'] . "</td>";
                        echo "<td>". $row['services'] ."</td>";
                        echo "<td style='height: 20px';>" . $row['fonction'] . "</td>";
                        echo "<td>". $row['contrat'] ."</td>";
                        echo "<td>". $date_sortie ."</td>";
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
            </table>

            <h4 style="margin-top: 40px; margin-bottom: 30px;"><u>LISTE DES SORTIES</u></h4>
            <?php
            // Pagination SORTIES
            $sort_page = isset($_GET['sort_page']) ? max(1, intval($_GET['sort_page'])) : 1;
            $sort_limit = 15;
            $sort_offset = ($sort_page - 1) * $sort_limit;
            $sort_query_count = "SELECT COUNT(*) as total FROM sorties_table WHERE type_sortie='SORTIE' AND (state_equipement=0 OR state_achat=0 OR state_batiment=0)";
            $sort_count_res = mysqli_query($database_connect, $sort_query_count);
            $sort_total = mysqli_fetch_assoc($sort_count_res)['total'];
            $sort_total_pages = ceil($sort_total / $sort_limit);
            $list_out = mysqli_query($database_connect, "SELECT * FROM sorties_table WHERE type_sortie='SORTIE' AND (state_equipement=0 OR state_achat=0 OR state_batiment=0) ORDER BY date_sortie ASC LIMIT $sort_limit OFFSET $sort_offset");
            ?>
            <table class="table table-hover table-bordered" style="width: 100%; margin-bottom: 40px;font-size: 14px;">
                <tr class="table-dark" style="font-size: 14px;">
                    <th style="width: 10%">Société</th>
                    <th style="width: 15%">Nom</th>
                    <th style="width: 15%">Service</th>
                    <th style="width: 15%">Fonction</th>
                    <th style="width: 8%">Type de contrat</th>
                    <th style="width: 8%">Date de sortie</th>
                </tr>
                <?php
                while ($row=$list_out->fetch_assoc()) {
                    $id_out=$row['id_sortie'];
                    $date_sortie = date("d-m-Y", strtotime($row['date_sortie']));
                    echo '<tr style="height: 40%;">';
                        echo "<td style='height: 20px';>" . $row['societe'] . "</td>";
                        echo "<td>" . $row['lastname']." ".$row['firstname'] . "</td>";
                        echo "<td>". $row['services'] ."</td>";
                        echo "<td style='height: 20px';>" . $row['fonction'] . "</td>";
                        echo "<td>". $row['contrat'] ."</td>";
                        echo "<td>". $date_sortie ."</td>";
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
            </table>


        </section>

        <!--Pied de page-->
        <footer class="bg-dark">
            <p>&copy; 2025 JCMI - Version 2.0 | Tous droits réservés.</p>
            <p>Support : informatiques@groupemichel.com | Tél : 1350 | <a style="color:#EF7837;" href="http://sos-informatique.gm.local" target="_blank">SOS INFORMATIQUE</a></p>
        </footer>


    </body>

</html>