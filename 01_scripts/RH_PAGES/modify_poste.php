<?php
    session_start();
    include_once '../../01_includes/dbconnect.php';
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Modification du Poste</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
        <script src="https://kit.fontawesome.com/857f401658.js" crossorigin="anonymous"></script>
        <link rel="shortcut icon" type="image/png" href="../../01_assets/faviconV2.png"/>
    </head>
    <body>
        <h4 style='margin-top:20px;margin-bottom:20px;background:#EF7837;color: white;'>MODIFICATION DU POSTE</h4>
        <!-- Vous pouvez ici ajouter un formulaire pour modifier le poste ou effectuer une recherche dans la BDD -->
        <?php 
            $id_Poste = isset($_GET['id_Poste']) ? $_GET['id_Poste'] : '';
            $one_poste = mysqli_query($database_connect, "SELECT * FROM fiche_poste_table WHERE id='$id_Poste'");

            if ($one_poste) {
                // Vérifier s'il y a au moins une ligne retournée
                if (mysqli_num_rows($one_poste) > 0) {
                    // Récupérer la ligne de résultat dans un tableau associatif
                    $row = mysqli_fetch_assoc($one_poste);

                    $rnqsa = htmlspecialchars($row['fiche_rnqsa']);
                    $categorie = htmlspecialchars($row['categorie']);
                    $intitule = htmlspecialchars($row['intitule_poste']);
        ?>

                    <form class="container" style="margin:0px; padding-top:20px; padding-bottom:15px" id="add_poste_form" method="POST" action="process_modifyPoste.php?id_Poste=<?php echo $id_Poste; ?>">

                        <div class="col">
                            <label for="code_rnqsa" class="form-label">RNQSA *</label>
                            <input type="text" class="form-control" id="rnqsa" name="rnqsa" placeholder="" value="<?php echo $rnqsa; ?>" width="400px;" required>
                        </div>

                        <div class="col">
                            <label for="cat_label" class="form-label">Catégorie *</label>
                            <input type="text" class="form-control" id="categorie_name" name="categorie_name" placeholder="" value="<?php echo $categorie; ?>" width="200px;" required>
                        </div>

                        <div class="col">
                            <label for="poste_label" class="form-label">Intitulé du Poste *</label>
                            <input type="text" class="form-control" id="poste_name" name="poste_name" placeholder="" value="<?php echo $intitule; ?>" width="400px;" required>
                        </div>

                        <button style="margin-top: 30px;background:#EF7837;color:white;" class="w-20 btn btn-lg" type="submit">Sauvegarder</button>

                    </form>

        <?php       
                } else {
                        echo "Aucun poste trouvé.";
                }
            } else {
                echo "Erreur dans la requête : " . mysqli_error($database_connect);
            }
        ?>
    </body>
</html>
