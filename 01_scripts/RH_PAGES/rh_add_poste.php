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

        <section>

            <h4 style='margin-top:20px;margin-bottom:20px;'><u>AJOUT D'UN NOUVEAU POSTE</u></h4>
            <?php
            
                $newPoste_queryState = $_GET['add_poste'];
                if (strcasecmp($newPoste_queryState, "success") === 0) {
                    echo '<div class="alert alert-success" style="width:674px;margin-left:16px;" role="alert">';
                        echo 'Nouveau poste ajouté sur le serveur.';
                        echo '<a class="closer_alert" href="rh_add_poste.php" style="float:right;"><i class="fa-solid fa-x"></i></a>';
                    echo '</div>';
                }elseif (strcasecmp($newPoste_queryState, "duplicate") === 0){
                    echo '<div class="alert alert-danger" style="width:674px;margin-left:16px;" role="alert">';
                        echo "Cet poste existe déjà sur le serveur avec ces caractéristiques.";
                        echo '<a class="closer_alert" href="rh_add_poste.php" style="float:right;"><i class="fa-solid fa-x"></i></a>';
                    echo '</div>';
                }elseif (strcasecmp($newPoste_queryState, "fail") === 0){
                    echo '<div class="alert alert-danger" style="width:674px;margin-left:16px;" role="alert">';
                        echo "Echec de l'ajout poste sur le serveur.";
                        echo '<a class="closer_alert" href="rh_add_poste.php" style="float:right;"><i class="fa-solid fa-x"></i></a>';
                    echo '</div>';
                }

            ?>

            <form class="container" style="margin:0px; padding-top:20px; padding-bottom:15px" id="add_poste_form" method="POST" action="add_new_poste.php">
                <div class="row g-4">

                    <div class="col-sm-3">
                        <label for="code_rnqsa" class="form-label">RNQSA *</label>
                        <input type="text" class="form-control" id="rnqsa" name="rnqsa" placeholder="" value="" required>
                    </div>

                    <div class="col-sm-3">
                        <label for="cat_label" class="form-label">Catégorie *</label>
                        <input type="text" class="form-control" id="categorie_name" name="categorie_name" placeholder="" value="" required>
                    </div>

                    <div class="col-sm-3">
                        <label for="poste_label" class="form-label">Intitulé du Poste *</label>
                        <input type="text" class="form-control" id="poste_name" name="poste_name" placeholder="" value="" required>
                    </div>

                </div>
                <button style="margin-top: 30px;background:#EF7837;color:white;" class="w-20 btn btn-lg" type="submit">Ajouter</button>

            </form>

            <h4 style="margin-top: 40px; margin-bottom: 30px;"><u>LISTE DES POSTES & FICHES RNQSA</u></h4>

            <?php
            
                $deletePoste_queryState = $_GET['delete_posteState'];
                if (strcasecmp($deletePoste_queryState, "success") === 0) {
                    echo '<div class="alert alert-success" style="width:674px;margin-left:16px;" role="alert">';
                        echo 'Poste supprimé du serveur.';
                        echo '<a class="closer_alert" href="rh_add_poste.php" style="float:right;"><i class="fa-solid fa-x"></i></a>';
                    echo '</div>';
                }elseif (strcasecmp($deletePoste_queryState, "fail") === 0){
                    echo '<div class="alert alert-danger" style="width:674px;margin-left:16px;" role="alert">';
                        echo "Echec de la suppression du poste.";
                        echo '<a class="closer_alert" href="rh_add_poste.php" style="float:right;"><i class="fa-solid fa-x"></i></a>';
                    echo '</div>';
                }

            ?>

            <table class="table table-hover table-bordered" style="width: 90%; margin-bottom: 40px;">
                <tr class="table-dark">
                    <th style="width: 8%">RNQSA</th>
                    <th style="width: 35%">Catégorie</th>
                    <th style="width: 50%">Intitulé du Poste</th>
                    <th style="width: 10%">Editer</th>
                    <th style="width: 20%">Supprimer</th>
                </tr>
                <?php

                    $list_poste = mysqli_query($database_connect, "SELECT * FROM fiche_poste_table ORDER BY categorie ASC");
                    while ($row=$list_poste->fetch_assoc()) {
                        $id_poste=$row['id'];
                        //Affichage de la liste des postes
                        echo '<tr style="height: 40%;">';
                            echo "<td style='height: 20px';>" . $row['fiche_rnqsa'] . "</td>";
                            echo "<td>" . $row['categorie'] . "</td>";
                            echo "<td>". $row['intitule_poste'] ."</td>";

                            //echo "<td><a href='modify_poste.php?id_poste=$id_poste'><i class='fa-regular fa-pen-to-square' style='color: #EF7837;'></i></a></td>";
                            echo "<td><a href='#' class='openPopup_modifyPoste' data-id_poste='$id_poste'><i class='fa-regular fa-pen-to-square' style='color: #EF7837;'></i></a></td>";

                            echo "<td><a href='delete_poste.php?id_poste=$id_poste'><i class='fa-regular fa-trash-can' style='color: #EF7837;'></i></a></td>";
                        echo '</tr>';

                    }
                ?>
            </table>

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
            // Sélectionne tous les liens de modification
            document.querySelectorAll('.openPopup_modifyPoste').forEach(function(link) {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    // Récupère la valeur id depuis l'attribut data
                    var idValue = this.getAttribute('data-id_poste');
                    
                    // Construit l'URL de la pop-up avec le paramètre GET
                    var url = 'modify_poste.php?id_Poste=' + idValue;
                    
                    // Ouvre la pop-up dans une nouvelle fenêtre
                    window.open(url, 'popUp', 'width=400,height=400,resizable=no');
                });
            });
        </script>

    </body>

</html>