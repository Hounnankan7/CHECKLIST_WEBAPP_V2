<?php
    session_start();
    include_once '../../01_includes/dbconnect.php';
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Modification Service</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
        <script src="https://kit.fontawesome.com/857f401658.js" crossorigin="anonymous"></script>
        <link rel="shortcut icon" type="image/png" href="../../01_assets/faviconV2.png"/>
    </head>
    <body>
        <h4 style='margin-top:20px;margin-bottom:20px;background:#EF7837;color: white;'>MODIFICATION SERVICE D'EMBAUCHE</h4>
        <!-- Vous pouvez ici ajouter un formulaire pour modifier les services -->
        <?php 
            $id_Service = isset($_GET['id_Service']) ? $_GET['id_Service'] : '';
            $one_Service = mysqli_query($database_connect, "SELECT * FROM services_table WHERE id='$id_Service'");

            if ($one_Service) {
                // Vérifier s'il y a au moins une ligne retournée
                if (mysqli_num_rows($one_Service) > 0) {
                    // Récupérer la ligne de résultat dans un tableau associatif
                    $row = mysqli_fetch_assoc($one_Service);
                    $intitule_service = htmlspecialchars($row['intitule_service']);
        ?>

                    <form class="container" style="margin:0px; padding-top:20px; padding-bottom:15px" id="modify_site_form" method="POST" action="process_modifyService.php?id_Service=<?php echo $id_Service; ?>">

                        <div class="col">
                            <label for="intitule_service" class="form-label">Intitule du Service *</label>
                            <input type="text" class="form-control" id="intitule_service" name="intitule_service" placeholder="" value="<?php echo $intitule_service; ?>" width="200px;" required>
                        </div>

                        <button style="margin-top: 30px;background:#EF7837;color:white;" class="w-20 btn btn-lg" type="submit">Sauvegarder</button>

                    </form>
        <?php       
                } else {
                        echo "Aucun Service trouvé.";
                }
            } else {
                echo "Erreur dans la requête : " . mysqli_error($database_connect);
            }
        ?>
    </body>
</html>
