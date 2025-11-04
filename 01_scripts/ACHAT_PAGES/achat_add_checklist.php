<?php
    session_start();
    include_once '../../01_includes/dbconnect.php';

    //Pré chargement de la liste des poste
    $list_poste = mysqli_query($database_connect, "SELECT categorie,intitule_poste FROM fiche_poste_table ORDER BY categorie ASC");
    $data = array();
    if(mysqli_num_rows($list_poste) > 0){
        while($row = mysqli_fetch_assoc($list_poste)){
            $categorie = $row['categorie'];
            // Construire un tableau associatif : pour chaque catégorie, un tableau de postes
            if (!isset($data[$categorie])) {
                $data[$categorie] = array();
            }
            $data[$categorie][] = $row['intitule_poste'];
        }
    }
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>SUIVI CHECK LIST - Chef de Service</title>

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
                        <li class="nav-item"><a class="nav-link active" href="achat_add_checklist.php"><i class="fa-solid fa-person-through-window fa-xl"></i> Ajouter Entrée</a></li>
                        <li class="nav-item"><a class="nav-link active" href="achat_homepage.php"><i class="fa-solid fa-person-walking-arrow-right fa-xl"></i> Suivi Entrées</a></li>
                        <li class="nav-item"><a class="nav-link active" href="achat_closedentry.php"><i class="fa-solid fa-list-check fa-xl"></i> Entrées Terminées</a></li>
                        <li class="nav-item"><a class="nav-link active" href="achat_outmanager.php"><i class="fa-solid fa-person-walking-arrow-right fa-flip-horizontal fa-xl"></i> Suivi Sorties</a></li>
                        <li class="nav-item"><a class="nav-link active" href="../deconnexion.php"><i class="fa-solid fa-light fa-power-off fa-xl"></i> Déconnexion</a></li>
                    </ul>

                </div>
            </nav>
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

            <h3 style="margin-right:10px; margin-left:10px; padding-top:25px; padding-bottom:10px">BESOINS EN MATERIEL ET LOGICIELS INFORMATIQUE</h3>
            <div style="margin-left:10px; padding-top:10px;padding-bottom:10px;">
                <p style="font-size:18px;height:26px;color:white;">
                    <span style="padding:6px;background:#EF7837;"><b>Les champs marqués d'un * doivent impérativement être renseignés.</b></span>
                </p>
            </div>

            <form class="container" id="checklist_form" method="POST" action="envoi_checklist.php?createdby=<?php echo $createdBy; ?>" style="margin-left: 0px;">
                <h4><u>RENSEIGNEMENTS SUR LA PERSONNE</u></h4>

                <div class="row g-3" style="padding-top:20px; padding-bottom:20px">
                    <div class="col-sm-3">
                        <label for="nom" class="form-label">Nom *</label>
                        <input type="text" class="form-control" id="nom" name="nom" placeholder="" value="" required>
                        <div class="invalid-feedback">
                            Un nom valable est obligatoire.
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <label for="nom_fille" class="form-label">Nom de jeune fille</label>
                        <input type="text" class="form-control" id="nom_fille" name="nom_fille" placeholder="" value="">
                    </div>

                    <div class="col-sm-3">
                        <label for="prenom" class="form-label">Prenom *</label>
                        <input type="text" class="form-control" id="prenom" name="prenom" placeholder="" value="" required>
                        <div class="invalid-feedback">
                        Un prenom valable est obligatoire.
                        </div>
                    </div>
                </div>

                <div class="row g-3" style="padding-top:10px; padding-bottom:10px">
                    <div class="col-sm-6">
                        <label for="site" class="form-label">Site d'embauche *</label>
                        <select class="form-select" id="site" name="site" required>
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
                </div>

                <div class="row g-3" style="padding-top:10px; padding-bottom:10px">

                    <div class="col-sm-4">
                        <label for="categorieSelect" class="form-label">Catégorie du poste *</label>
                        <select id="categorieSelect" class="form-select"required>
                            <option value="">Choisir...</option>
                            <?php
                                //Requete récupération des categorie
                                $categorie_poste_list = mysqli_query($database_connect, "SELECT DISTINCT categorie FROM fiche_poste_table ORDER BY categorie ASC");
                                if(mysqli_num_rows($categorie_poste_list) > 0){
                                    while($row = mysqli_fetch_assoc($categorie_poste_list)){
                                        echo "<option value='".$row['categorie']."'>".$row['categorie']."</option>";
                                    }
                                    //echo '<option value="autres">AUTRES</option>';
                                }
                               
                            ?>
                        </select>
                        <div class="invalid-feedback">
                            Sélectionner une catégorie valable.
                        </div>
                    </div>

                    <div class="col-sm-5" id="posteSelectContainer">
                        <label for="poste" class="form-label">Intitulé du poste *</label>
                        <select id="posteSelect" class="form-select" name="posteSelect" required>
                            <option value="">Choisir...</option>
                        </select>
                    </div>
                    <!-- Conteneur pour le champ poste (caché par défaut) 
                    <div class="col-sm-5" id="posteInputContainer" style="display:none;">
                        <label for="posteInput" class="form-label">Intitulé du poste *</label>
                        <input type="text" id="posteInput" class="form-control" placeholder="Entrer l'intitulé du poste">
                    </div>-->

                </div>

                <div class="row g-3" style="padding-top:10px; padding-bottom:10px">
                    <div class="col-sm-6">
                        <label for="precisionPoste" class="form-label">Précision d'intitulé de poste</label>
                        <input type="text" class="form-control" id="precisionPoste" name="precisionPoste" placeholder="Ajouter une précision à l'intitulé du poste (optionnel)" value="">
                        <!--<small class="form-text text-muted">Cette précision sera ajoutée à l'intitulé du poste sélectionné ci-dessus.</small>-->
                    </div>

                </div>

                <div class="row g-3" style="padding-top:10px; padding-bottom:10px">
                    <div class="col-sm-3">
                        <label for="arrive_date" class="form-label">Date d'embauche (JJ/MM/AAAA) *</label>
                        <input type="date" class="form-control" name="arrive_date" id="arrive_date" placeholder="" value="" required>
                    </div>

                    <div class="col-sm-3">
                        <label for="birth_date" class="form-label">Date de naissance (JJ/MM/AAAA) *</label>
                        <input type="date" class="form-control" name="birth_date" id="birth_date" placeholder="" value="" required>
                    </div>
                </div>


                <hr class="my-4" style="width: 75%;">
                <h4><u>ADRESSE MAIL</u></h4>
                <div class="row g-3" style="padding-top:10px; padding-bottom:10px">
                    <div class="col-sm-3">
                        <label for="creation_mail" class="form-label">Création d'une adresse mail *</label>
                        <select class="form-select" id="creation_mail" name="creation_mail" required>
                            <option value="">Choisir...</option>
                            <option>Oui</option>
                            <option>Non</option>
                        </select>
                        <div class="invalid-feedback">
                            Faite un choix valable.
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <label for="remplacement" class="form-label">Remplacement d'une personne partie</label>
                        <select class="form-select" id="remplacement" name="remplacement">
                            <option value="">Choisir...</option>
                            <option>Oui</option>
                            <option>Non</option>
                        </select>
                        <div class="invalid-feedback">
                            Faite un choix valable.
                        </div>
                    </div>
                </div>
                <div class="row g-3" style="padding-top:10px; padding-bottom:10px">
                    <div class="col-sm-6">
                        <label style="color: #EF7837;" for="ancien_remplacer" class="form-label">Si oui, nom de l'ancien salarié</label>
                        <input type="text" class="form-control" id="ancien_remplacer" name="ancien_remplacer" placeholder="" value="">
                    </div>
                    <div class="col-sm-3">
                        <label style="color: #EF7837;" for="activer_transfert" class="form-label">Activer le transfert de mail</label>
                        <select class="form-select" id="activer_transfert" name="activer_transfert">
                            <option value="">Choisir...</option>
                            <option>Oui</option>
                            <option>Non</option>
                        </select>
                        <div class="invalid-feedback">
                            Faite un choix valable.
                        </div>
                    </div>
                </div>

                <hr class="my-4" style="width: 75%;">
                <h4><u>APPLICATIONS</u></h4>
                <div class="row g-3" style="padding-top:10px; padding-bottom:10px">
                    <div class="col-sm-3">
                        <label for="creation_rrf" class="form-label">Création RRF/DMD *</label>
                        <select class="form-select" id="creation_rrf" name="creation_rrf" required>
                            <option value="">Choisir...</option>
                            <option>Oui</option>
                            <option>Non</option>
                        </select>
                        <div class="invalid-feedback">
                            Faite un choix valable.
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <label for="creation_rlearning" class="form-label">Création R Learning *</label>
                        <select class="form-select" id="creation_rlearning" name="creation_rlearning" required>
                            <option value="">Choisir...</option>
                            <option>Oui</option>
                            <option>Non</option>
                        </select>
                        <div class="invalid-feedback">
                            Faite un choix valable.
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <label for="creation_rcampus" class="form-label">Création R Campus *</label>
                        <select class="form-select" id="creation_rcampus" name="creation_rcampus" required>
                            <option value="">Choisir...</option>
                            <option>Oui</option>
                            <option>Non</option>
                        </select>
                        <div class="invalid-feedback">
                            Faite un choix valable.
                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                        <label for="creation_dcsnet" class="form-label">Création DCS Net *</label>
                        <select class="form-select" id="creation_dcsnet" name="creation_dcsnet" required>
                            <option value="">Choisir...</option>
                            <option>Oui</option>
                            <option>Non</option>
                        </select>
                        <div class="invalid-feedback">
                            Faite un choix valable.
                        </div>
                </div>

                <hr class="my-4" style="width: 75%;">
                <h4><u>MATERIEL</u></h4>
                <div class="col-sm-3">
                    <label for="tocken" class="form-label">Clé tocken *</label>
                    <select class="form-select" id="tocken" name="tocken" required>
                        <option value="">Choisir...</option>
                        <option>Oui</option>
                        <option>Non</option>
                    </select>
                    <div class="invalid-feedback">
                        Faite un choix valable.
                    </div>
                </div>
                <div class="row g-3" style="padding-top:10px; padding-bottom:10px">
                    <div class="col-sm-3">
                        <label for="portable_pro" class="form-label">Télephone portable pro *</label>
                        <select class="form-select" id="portable_pro" name="portable_pro" required>
                            <option value="">Choisir...</option>
                            <option>Oui</option>
                            <option>Non</option>
                        </select>
                        <div class="invalid-feedback">
                            Faite un choix valable.
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <label style="color: #EF7837;" for="new_line" class="form-label">Nouvelle ligne</label>
                        <select class="form-select" id="new_line" name="new_line">
                            <option value="">Choisir...</option>
                            <option>Oui</option>
                            <option>Non</option>
                        </select>
                        <div class="invalid-feedback">
                            Faite un choix valable.
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <label style="color: #EF7837;" for="number_new_line" class="form-label">Reprise, indiquer le numéro</label>
                        <input type="text" class="form-control" id="number_new_line" name="number_new_line" placeholder="" value="">
                    </div>
                </div>

                <div class="row g-3" style="padding-top:10px; padding-bottom:10px">
                    <div class="col-sm-3">
                        <label for="demande_pc" class="form-label">Commander nouveau PC *</label>
                        <select class="form-select" id="demande_pc" name="demande_pc" required>
                            <option value="">Choisir...</option>
                            <option>Oui</option>
                            <option>Non</option>
                        </select>
                        <div class="invalid-feedback">
                            Faite un choix valable.
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <label style="color: #EF7837;" for="demande_pc_oui" class="form-label">Si oui</label>
                        <select class="form-select" id="demande_pc_oui" name="demande_pc_oui">
                            <option value="">Choisir...</option>
                            <option>FIXE</option>
                            <option>PORTABLE</option>
                        </select>
                        <div class="invalid-feedback">
                            Faite un choix valable.
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <label style="color: #EF7837;" for="nom_ancien_pc" class="form-label">Si non, nom de l'ordinateur</label>
                        <input type="text" class="form-control" id="nom_ancien_pc" name="nom_ancien_pc" placeholder="" value="">
                    </div>
                </div>

                <div class="row g-3" style="padding-top:10px; padding-bottom:10px">
                    
                    <div class="col-sm-3">
                        <label for="demande_malette" class="form-label">Malette PC *</label>
                        <select class="form-select" id="demande_malette" name="demande_malette" required>
                            <option value="">Choisir...</option>
                            <option>Oui</option>
                            <option>Non</option>
                        </select>
                        <div class="invalid-feedback">
                            Faite un choix valable.
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <label for="demande_imprimante" class="form-label">Imprimante *</label>
                        <select class="form-select" id="demande_imprimante" name="demande_imprimante" required>
                            <option value="">Choisir...</option>
                            <option>Oui</option>
                            <option>Non</option>
                        </select>
                        <div class="invalid-feedback">
                            Faite un choix valable.
                        </div>
                    </div>
                </div>

                <div class="row g-3" style="padding-top:10px; padding-bottom:10px">
                    <div class="col-sm-3">
                        <label for="demande_vehicule" class="form-label">Véhicule + Assurance *</label>
                        <select class="form-select" id="demande_vehicule" name="demande_vehicule" required>
                            <option value="">Choisir...</option>
                            <option>Oui</option>
                            <option>Non</option>
                        </select>
                        <div class="invalid-feedback">
                            Faite un choix valable.
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <label for="demande_badge" class="form-label">Badge alarme *</label>
                        <select class="form-select" id="demande_badge" name="demande_badge" required>
                            <option value="">Choisir...</option>
                            <option>Oui</option>
                            <option>Non</option>
                        </select>
                        <div class="invalid-feedback">
                            Faite un choix valable.
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <label for="demande_cles" class="form-label">Clés batiments*</label>
                        <select class="form-select" id="demande_cles" name="demande_cles" required>
                            <option value="">Choisir...</option>
                            <option>Oui</option>
                            <option>Non</option>
                        </select>
                        <div class="invalid-feedback">
                            Faite un choix valable.
                        </div>
                    </div>
                </div>

                <hr class="my-4" style="width: 75%;">
                <h4><u>EQUIPEMENT</u></h4>

                <div class="row g-3" style="padding-top:10px; padding-bottom:10px">
                    <div class="col-sm-3">
                        <label for="demande_vetement" class="form-label">Vêtements de travail *</label>
                        <select class="form-select" id="demande_vetement" name="demande_vetement" required>
                            <option value="">Choisir...</option>
                            <option>Oui</option>
                            <option>Non</option>
                        </select>
                        <div class="invalid-feedback">
                            Faite un choix valable.
                        </div>
                    </div>
                </div>

                <div style="padding-top:10px; padding-bottom:10px">
                    <label style="padding-bottom:15px;" for="">Liste des vêtements à attribués : (Sélectionner les vêtements requis et préciser leur tailles)</label>

                    <div class="form-check" style="display: flex; align-items: center; gap: 10px; margin-bottom:10px;">
                        <input class="form-check-input checkboxGroup" type="checkbox" value="" id="check_tshirt">
                        <label class="form-check-label" for="check_tshirt" style="width: 80px; text-align: left;">T-shirt</label>
                        <input type="text" class="form-control" id="taille_tshirt" name="taille_tshirt" placeholder="Taille T-Shirt" value="" style="width: 200px;">
                    </div>

                    <div class="form-check" style="display: flex; align-items: center; gap: 10px; margin-bottom:10px;">
                        <input class="form-check-input checkboxGroup" type="checkbox" value="" id="check_chemise">
                        <label class="form-check-label" for="check_chemise" style="width: 80px; text-align: left;">Chemise</label>
                        <input type="text" class="form-control" id="taille_chemise" name="taille_chemise" placeholder="Taille Chemise" value="" style="width: 200px;">
                    </div>

                    <div class="form-check" style="display: flex; align-items: center; gap: 10px; margin-bottom:10px;">
                        <input class="form-check-input checkboxGroup" type="checkbox" value="" id="check_veste">
                        <label class="form-check-label" for="check_veste" style="width: 80px; text-align: left;">Veste</label>
                        <input type="text" class="form-control" id="taille_veste" name="taille_veste" placeholder="Taille Veste" value="" style="width: 200px;">
                    </div>

                    <div class="form-check" style="display: flex; align-items: center; gap: 10px;">
                        <input class="form-check-input checkboxGroup" type="checkbox" value="" id="check_pantalon">
                        <label class="form-check-label" for="check_pantalon" style="width: 80px; text-align: left;">Pantalon</label>
                        <input type="text" class="form-control" id="taille_pantalon" name="taille_pantalon" placeholder="Taille Pantalon" value="" style="width: 200px;">
                    </div>
                </div>

                <div class="row g-3" style="padding-top:10px; padding-bottom:10px">
                    <div class="col-sm-3">
                        <label for="demande_chaussure" class="form-label">Chaussures de travail *</label>
                        <select class="form-select" id="demande_chaussure" name="demande_chaussure" required>
                            <option value="">Choisir...</option>
                            <option>Oui</option>
                            <option>Non</option>
                        </select>
                        <div class="invalid-feedback">
                            Faite un choix valable.
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <label for="taille_chaussure" class="form-label">Pointure</label>
                        <input type="text" class="form-control" id="poiture" name="pointure" placeholder="" value="">
                    </div>
                </div>

                <hr class="my-4" style="width: 75%;">
                <div class="col-sm-6" style="padding-top:8px; padding-bottom:8px">
                    <div class="mb-3">
                        <label for="commentaire" class="form-label"><h4><u>COMMENTAIRES</u></h4></label>
                        <textarea class="form-control" id="commentaire" name="commentaire" rows="6"></textarea>
                    </div>
                </div>

                <button style="margin-top: 30px; margin-bottom: 20px;background:#EF7837;color:white;" class="w-20 btn btn-lg" type="submit">Soumettre</button>
            </form>

            <?php mysqli_close($database_connect); ?>
        </section>

        <!--Pied de page-->
        <footer class="bg-dark">
            <p>&copy; 2025 JCMI - Version 2.0 | Tous droits réservés.</p>
            <p>Support : informatiques@groupemichel.com | Tél : 1350 | <a style="color:#EF7837;" href="http://sos-informatique.gm.local" target="_blank">SOS INFORMATIQUE</a></p>
        </footer>

        <!-- Inclusion de jQuery et Bootstrap JS Bundle -->
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- 5. Transmission des données PHP vers JavaScript -->
        <script>

            // Récupérez le paramètre de l'URL
                const urlParams = new URLSearchParams(window.location.search);
                const sendChecklist = urlParams.get('send_checklist');

                if (sendChecklist === 'success') {
                    // Affichez une boîte de dialogue de confirmation en cas de succès
                    alert('Nouvelle checklist envoyée avec succès sur le serveur.');
                }
                
                if(sendChecklist === 'error') {
                    const errorMessage = urlParams.get('message');
                    alert('Erreur lors de l\'ajout de la checklist :\n' + errorMessage);
                }
            //

            // La variable "data" contient les associations catégorie => [poste1, poste2, ...]
                var data = <?php echo json_encode($data); ?>;
                // Écoute du changement sur le select catégorie
                document.getElementById("categorieSelect").addEventListener("change", function() {
                    var selectedCategory = this.value;
                    var posteSelectContainer = document.getElementById("posteSelectContainer");
                    var posteInputContainer = document.getElementById("posteInputContainer");
                    var posteSelect = document.getElementById("posteSelect");

                    //if (selectedCategory === "autres") {
                        // Si "Autre" est sélectionné, masquer le select et afficher le champ texte
                        //posteSelectContainer.style.display = "none";
                        //posteInputContainer.style.display = "block";
                    //} else {
                        // Sinon, afficher le select et masquer le champ texte
                        //posteInputContainer.style.display = "none";
                        //posteSelectContainer.style.display = "block";
                        
                        // Vider le select des postes et ajouter une option par défaut
                        posteSelect.innerHTML = "";
                        var defaultOption = document.createElement("option");
                        defaultOption.value = "";
                        defaultOption.text = "-- Sélectionnez un poste --";
                        posteSelect.appendChild(defaultOption);
                        
                        // Récupérer la liste des postes correspondant à la catégorie sélectionnée
                        var postes = data[selectedCategory] || [];

                        // Trier les postes par ordre alphabétique
                        postes.sort();

                        // Ajouter les options triées au select
                        postes.forEach(function(poste) {
                        var option = document.createElement("option");
                        option.value = poste;
                        option.text = poste;
                        posteSelect.appendChild(option);
                        });
                    //}
                });
            //

            // Fonction qui met à jour l'état des checkboxes en fonction de la valeur du select
                function updateWidgetsState() {
                    var select = document.getElementById('demande_vetement');
                    var checkboxes = document.querySelectorAll('.checkboxGroup');
                    var tailleInputs = document.querySelectorAll('input[id^="taille_"]');
                    
                    // Si l'utilisateur choisit "Oui", on active (disabled = false)
                    if (select.value === "Oui") {
                        checkboxes.forEach(function(checkbox) {
                        checkbox.disabled = false;
                        });
                        tailleInputs.forEach(function(input) {
                        input.disabled = false;
                        });
                    } else {
                        // Sinon (choix "Non" ou valeur vide), on désactive les éléments (disabled = true)
                        checkboxes.forEach(function(checkbox) {
                        checkbox.disabled = true;
                        });
                        tailleInputs.forEach(function(input) {
                        input.disabled = true;
                        });
                    }
                    }

                    // Ajout d'un écouteur d'événement sur le select pour déclencher la mise à jour dès qu'il change
                    document.getElementById('demande_vetement').addEventListener('change', updateWidgetsState);

                    // Initialisation dès le chargement de la page pour synchroniser l'état des éléments
                    updateWidgetsState();
                //

                // Gestion de l'activation/désactivation des champs PC
                document.getElementById("demande_pc").addEventListener("change", function() {
                    var demandePC = this.value;
                    var demandePCOui = document.getElementById("demande_pc_oui");
                    var nomAncienPC = document.getElementById("nom_ancien_pc");
                    
                    if (demandePC === "Non") {
                        // Si "Non" est sélectionné, désactiver le champ "Si oui" et activer "Si non"
                        demandePCOui.disabled = true;
                        demandePCOui.style.backgroundColor = "#f8f9fa";
                        demandePCOui.style.color = "#6c757d";
                        demandePCOui.value = "";
                        
                        nomAncienPC.disabled = false;
                        nomAncienPC.style.backgroundColor = "";
                        nomAncienPC.style.color = "";
                    } else if (demandePC === "Oui") {
                        // Si "Oui" est sélectionné, activer le champ "Si oui" et désactiver "Si non"
                        demandePCOui.disabled = false;
                        demandePCOui.style.backgroundColor = "";
                        demandePCOui.style.color = "";
                        
                        nomAncienPC.disabled = true;
                        nomAncienPC.style.backgroundColor = "#f8f9fa";
                        nomAncienPC.style.color = "#6c757d";
                        nomAncienPC.value = "";
                    } else {
                        // Si aucune option n'est sélectionnée, désactiver les deux champs
                        demandePCOui.disabled = true;
                        demandePCOui.style.backgroundColor = "#f8f9fa";
                        demandePCOui.style.color = "#6c757d";
                        demandePCOui.value = "";
                        
                        nomAncienPC.disabled = true;
                        nomAncienPC.style.backgroundColor = "#f8f9fa";
                        nomAncienPC.style.color = "#6c757d";
                        nomAncienPC.value = "";
                    }
                });

                // Gestion de l'activation/désactivation des champs téléphone
                document.getElementById("portable_pro").addEventListener("change", function() {
                    var portablePro = this.value;
                    var newLine = document.getElementById("new_line");
                    var numberNewLine = document.getElementById("number_new_line");
                    
                    if (portablePro === "Oui") {
                        // Si "Oui" est sélectionné, activer les champs
                        newLine.disabled = false;
                        newLine.style.backgroundColor = "";
                        newLine.style.color = "";
                        
                        numberNewLine.disabled = false;
                        numberNewLine.style.backgroundColor = "";
                        numberNewLine.style.color = "";
                    } else {
                        // Sinon (choix "Non" ou valeur vide), désactiver et griser les champs
                        newLine.disabled = true;
                        newLine.style.backgroundColor = "#f8f9fa";
                        newLine.style.color = "#6c757d";
                        newLine.value = "";
                        
                        numberNewLine.disabled = true;
                        numberNewLine.style.backgroundColor = "#f8f9fa";
                        numberNewLine.style.color = "#6c757d";
                        numberNewLine.value = "";
                    }
                });

                // Initialiser l'état des champs PC au chargement de la page
                document.addEventListener("DOMContentLoaded", function() {
                    var demandePCOui = document.getElementById("demande_pc_oui");
                    var nomAncienPC = document.getElementById("nom_ancien_pc");
                    var newLine = document.getElementById("new_line");
                    var numberNewLine = document.getElementById("number_new_line");
                    
                    // Désactiver les champs PC par défaut
                    demandePCOui.disabled = true;
                    demandePCOui.style.backgroundColor = "#f8f9fa";
                    demandePCOui.style.color = "#6c757d";
                    
                    nomAncienPC.disabled = true;
                    nomAncienPC.style.backgroundColor = "#f8f9fa";
                    nomAncienPC.style.color = "#6c757d";
                    
                    // Désactiver les champs téléphone par défaut
                    newLine.disabled = true;
                    newLine.style.backgroundColor = "#f8f9fa";
                    newLine.style.color = "#6c757d";
                    
                    numberNewLine.disabled = true;
                    numberNewLine.style.backgroundColor = "#f8f9fa";
                    numberNewLine.style.color = "#6c757d";
                });
                //
                
        </script>

    </body>

</html>