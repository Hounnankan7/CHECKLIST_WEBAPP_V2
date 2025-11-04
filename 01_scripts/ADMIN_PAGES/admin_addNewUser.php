<?php 
    session_start();
    include_once '../../01_includes/dbconnect.php';
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>AJOUT D'UTILISATEUR</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
        <script src="https://kit.fontawesome.com/857f401658.js" crossorigin="anonymous"></script>
        <link rel="shortcut icon" type="image/png" href="../../01_assets/faviconV2.png"/>
    </head>
    <body>
        <h4 style='margin-top:20px;margin-bottom:20px;background:#EF7837;color: white;'>AJOUTER UN UTILISATEUR</h4>
        <!-- Vous pouvez ici ajouter un formulaire pour ajouter un user dans la BDD -->
         
        <form class="container" style="margin:0px; padding-top:20px; padding-bottom:20px" id="add_user_form" method="POST" action="add_new_user.php">

            <div class="row g-3" style="padding-top:20px; padding-bottom:20px">
                <div class="col-sm-3">
                    <label for="nom" class="form-label">Nom *</label>
                    <input type="text" class="form-control" id="nom" name="nom" placeholder="" value="" required>
                    <div class="invalid-feedback">
                        Un nom valable est obligatoire.
                    </div>
                </div>

                <div class="col-sm-3">
                    <label for="nom_fille" class="form-label">Prenom *</label>
                    <input type="text" class="form-control" id="prenom" name="prenom" placeholder="" value="" required>
                </div>
            </div>

            <div class="col-sm-6">
                <label class="form_label">Adresse Mail *</label>
                <input class="form-control" type="email" name="email" placeholder="Email" required>
            </div>

            <div class="col-sm-6">
                <label for="fonction" class="form-label">Site *</label>
                <select class="form-select" id="site_em" name="site" required>
                    <option value="">Choisir...</option>
                    <?php
                        //Requete récupération des sites d'embauche
                        $site_list = mysqli_query($database_connect, "SELECT * FROM site_embauche_table");
                        while($row = mysqli_fetch_assoc($site_list)){
                            echo "<option>".$row['intitule_site']."</option>";
                        }
                    ?>
                </select>
                <div class="invalid-feedback">
                    Faite un choix valable.
                </div>
            </div>

            <div class="col-sm-6">
                <label for="fonction" class="form-label">Profil *</label>
                <select class="form-select" id="fonction" name="fonction" required>
                    <option value="">Choisir...</option>
                    <option>RH</option>
                    <option>Informaticien</option>
                    <option>Chef de Service</option>
                    <option>Batiments</option>
                    <option>Achats</option>
                </select>
                <div class="invalid-feedback">
                    Faite un choix valable.
                </div>
            </div>

            <button style="margin-top: 30px;" class="w-50 btn btn-primary btn-lg" type="submit">Ajouter</button>

        </form>
    </body>
</html>
