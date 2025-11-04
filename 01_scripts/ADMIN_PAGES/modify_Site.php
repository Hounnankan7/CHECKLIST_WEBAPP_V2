<?php
    session_start();
    include_once '../../01_includes/dbconnect.php';
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Modification Site</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
        <script src="https://kit.fontawesome.com/857f401658.js" crossorigin="anonymous"></script>
        <link rel="shortcut icon" type="image/png" href="../../01_assets/faviconV2.png"/>
    </head>
    <body>
        <h4 style='margin-top:20px;margin-bottom:20px;background:#EF7837;color: white;'>MODIFICATION SITE D'EMBAUCHE</h4>
        <!-- Vous pouvez ici ajouter un formulaire pour modifier les sites -->
        <?php 
            $id_Site = isset($_GET['id_Site']) ? $_GET['id_Site'] : '';
            $one_site = mysqli_query($database_connect, "SELECT * FROM site_embauche_table WHERE id='$id_Site'");

            if ($one_site) {
                // Vérifier s'il y a au moins une ligne retournée
                if (mysqli_num_rows($one_site) > 0) {
                    // Récupérer la ligne de résultat dans un tableau associatif
                    $row = mysqli_fetch_assoc($one_site);
                    $intitule_site = htmlspecialchars($row['intitule_site']);
                    $pswd_default = htmlspecialchars($row['pswd_default']);

        ?>

                    <form class="container" style="margin:0px; padding-top:20px; padding-bottom:15px" id="modify_site_form" method="POST" action="process_modifySite.php?id_Site=<?php echo $id_Site; ?>">

                        <div class="col">
                            <label for="intitule_site" class="form-label">Intitule du Site *</label>
                            <input type="text" class="form-control" id="intitule_site" name="intitule_site" placeholder="" value="<?php echo $intitule_site; ?>" width="200px;" required>
                        </div>

                        <div class="col">
                            <label for="site_pswd" class="form-label">Mot de Passe (par défaut) *</label>
                            <input type="text" class="form-control" id="site_pswd" name="site_pswd" placeholder="" value="<?php echo $pswd_default; ?>" width="200px;" required>
                        </div>

                        <button style="margin-top: 30px;background:#EF7837;color:white;" class="w-20 btn btn-lg" type="submit">Sauvegarder</button>

                    </form>
        <?php       
                } else {
                        echo "Aucun Site trouvé.";
                }
            } else {
                echo "Erreur dans la requête : " . mysqli_error($database_connect);
            }
        ?>
    </body>
</html>
