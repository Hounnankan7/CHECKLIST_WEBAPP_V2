<?php
    session_start();
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

        <div class="user_connected" style="background:#EF7837;">
            <p style="color: white;margin-left:12px;margin-right:12px;padding-top:5px;padding-bottom:5px;font-size:18px;"> 
                <?php echo 'Utilisateur connecté : <b>' . $_SESSION['user_firstname'] . ' ' . $_SESSION['user_lastname'] . '</b>'; ?>
            </p>
        </div>

        <?php
        
            $deleteEntry_query_state = $_GET['delete_EntryState'];
            if (strcasecmp($deleteEntry_query_state, "success") === 0) {
                echo '<div class="alert alert-success" style="width:674px;margin-left:16px;" role="alert">';
                    echo 'Checklist sélectionnée supprimer du serveur.';
                    echo '<a class="closer_alert" href="../../01_scripts/RH_PAGES/rh_homepage.php" style="float:right;"><i class="fa-solid fa-x"></i></a>';
                echo '</div>';
            }elseif (strcasecmp($deleteEntry_query_state, "fail") === 0){
                echo '<div class="alert alert-danger" style="width:674px;margin-left:16px;" role="alert">';
                    echo "Echec de suppression de la Checklist sélectionnée du serveur.";
                    echo '<a class="closer_alert" href="../../01_scripts/RH_PAGES/rh_homepage.php" style="float:right;"><i class="fa-solid fa-x"></i></a>';
                echo '</div>';
            }

        ?>

        <section>

            <table class="table table-hover">
            
                <tr>
                    <th style="width: 20%;">Nom</th>
                    <th style="width: 20%;">Attribué à</th>
                    <th style="width: 8%;">Progression</th>
                    <th style="width: 8%;">Pdf</th>
                    <th style="width: 12%;">Date Embauche</th>
                    <th style="width: 12%;">Priorité</th>
                    <th style="width: 8%;">Etat</th>
                    <th style="width: 6%;"> </th>
                    <th style="width: 6%;"> </th>
                </tr>

                <?php
                    session_start();
                    include_once '../../01_includes/dbconnect.php';

                    //variables de pagination
                    $parPage = 20; // Nombre de résultats par page
                    $page = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
                    $page = max(1, $page); // Évite les pages < 1

                    $start = ($page - 1) * $parPage;

                    // Compter le nombre total d'entrée
                    $sql_total = "SELECT COUNT(*) AS total FROM tasks_table WHERE progression < 100";
                    $result_total = mysqli_query($database_connect, $sql_total);
                    $row_total = mysqli_fetch_assoc($result_total);
                    $total = $row_total['total'];
                    $totalPages = ceil($total / $parPage);


                    $found_tasks=mysqli_query($database_connect, "SELECT t.*, e.user_lastname, e.user_firstname, e.date_embauche 
                        FROM tasks_table t INNER JOIN new_employee_table e ON t.id_employe = e.id_employe WHERE t.progression < 100 
                        ORDER BY e.date_embauche DESC LIMIT $start, $parPage"
                    );

                    while ($row= $found_tasks-> fetch_assoc()) {

                        $employ_name = $row['user_lastname'];
                        $employ_prename = $row['user_firstname'];
                        $date_embauche = new DateTime($row['date_embauche']);
                        $checklistId = $row['id_task'];
                        $employeId = $row['id_employe'];
                        $date_emb = date('d-m-Y', strtotime($row['date_embauche']));


                        $date_aujourdhui = new DateTime(); // La date d'aujourd'hui
                        $interval = $date_aujourdhui->diff($date_embauche);
                        // Obtenez la différence en jours
                        $jours_restants = (int)$interval->format('%r%a');

                        if ($jours_restants > 20) {
                            $priorite = "Basse";
                            $color="green";
                        } elseif ($jours_restants <= 20 && $jours_restants > 10) {
                            $priorite = "Moyenne";
                            $color="gold";
                        } elseif ($jours_restants <= 10 && $jours_restants > 5) {
                            $priorite = "Haute";
                            $color="orange";
                        } else {
                            $priorite = "Très Haute";
                            $color="red";
                        }

                        // Afficher les données ici
                        echo '<tr style="height: 80%;">';
                            echo "<td style='height: 50px';>" . $employ_name .' '. $employ_prename . "</td>";
                            echo "<td>" . $row['attribue_a'] . "</td>";
                            echo "<td>" . intval($row['progression']) . " % </td>";
                            echo "<td><a target='_blank' href='../../01_scripts/RH_PAGES/00_PDF_CHECKLIST/CHECKLIST_". $employ_name .'_'. $employ_prename .".pdf' title='Afficher la checklist'><i style='color:#EF7837;' class='fa-solid fa-eye fa-lg'></i></a></td>";
                            echo "<td>" . $date_emb . " </td>";
                            echo "<td style='color:".$color.";'>" . $priorite . " </td>";

                            if (strcasecmp($row['etat_rh'],"En attente") === 0) {
                                echo "<td title='En attente de validation'><i class='fa-solid fa-hourglass-half fa-spin fa-lg'></i></td>";
                            }else{
                                echo "<td title='Valider'><i style='color:green;' class='fa-solid fa-circle-check fa-lg'></i></td>";                    
                            }
        
                            echo "<td><a href='delete_checklist.php?checklistId=$checklistId&employeId=$employeId' title='Supprimer cette entrée'><i style='color:#EF7837;' class='fa-solid fa-file-excel fa-lg'></i></a></td>";

                            echo "<td style='text-align: center;'> <a style='color:#EF7837;' href='../oneProfil_tasks.php?id_profil=$employeId&role=rh'>Modifier</a> </td>";

                        echo '</tr>';
                    }
                ?>

            </table>

            <!-- Liens de pagination -->
            <div class="pagination">
                <?php
                    if ($page > 1) {
                        echo '<a href="?page='.($page - 1).'">← Précédent </a>';
                    }
                ?>
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <?php if ($i == $page): ?>
                        <strong><?= $i ?></strong>
                    <?php else: ?>
                        <a href="?page=<?= $i ?>"><?= $i ?></a>
                    <?php endif; ?>
                <?php endfor; ?>

                <?php if ($page < $totalPages): ?>
                    <a href="?page=<?= $page + 1 ?>"> Suivant →</a>
                <?php endif; ?>
            </div>

        </section>

        <!--Pied de page-->
        <footer class="bg-dark">
            <p>&copy; 2025 JCMI - Version 2.0 | Tous droits réservés.</p>
            <p>Support : informatiques@groupemichel.com | Tél : 1350 | <a style="color:#EF7837;" href="http://sos-informatique.gm.local" target="_blank">SOS INFORMATIQUE</a></p>
        </footer>


    </body>

</html>