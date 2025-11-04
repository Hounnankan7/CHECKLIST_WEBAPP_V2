<?php
    session_start();
    include_once '../../01_includes/dbconnect.php';
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Modification de l'utilisateur</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
        <script src="https://kit.fontawesome.com/857f401658.js" crossorigin="anonymous"></script>
        <link rel="shortcut icon" type="image/png" href="../../01_assets/faviconV2.png"/>
    </head>
    <body>
        <h4 style='margin-top:20px;margin-bottom:20px;background:#EF7837;color: white;'>MODIFICATION D'UN UTILISATEUR</h4>
        <!-- Vous pouvez ici ajouter un formulaire pour modifier les infos d'un utilisateur specifique -->
        <?php 
            $id_User = isset($_GET['id_User']) ? $_GET['id_User'] : '';
            $one_user = mysqli_query($database_connect, "SELECT * FROM users_table WHERE id='$id_User'");

            if ($one_user) {
                // Vérifier s'il y a au moins une ligne retournée
                if (mysqli_num_rows($one_user) > 0) {
                    // Récupérer la ligne de résultat dans un tableau associatif
                    $row = mysqli_fetch_assoc($one_user);

                    $user_firstname = htmlspecialchars($row['user_firstname']);
                    $user_lastname = htmlspecialchars($row['user_lastname']);
                    $user_role = htmlspecialchars($row['user_role']);
                    $user_email = htmlspecialchars($row['user_email']);
                    $user_pswd = htmlspecialchars($row['user_pswd']);
        ?>

                    <form class="container" style="margin:0px; padding-top:20px; padding-bottom:15px" id="add_poste_form" method="POST" action="process_modifyUser.php?id_User=<?php echo $id_User; ?>">

                        <div class="col">
                            <label for="user_nom" class="form-label">Nom *</label>
                            <input type="text" class="form-control" id="user_nom" name="user_nom" placeholder="" value="<?php echo $user_lastname; ?>" width="200px;" required>
                        </div>

                        <div class="col">
                            <label for="user_prenom" class="form-label">Prénom *</label>
                            <input type="text" class="form-control" id="user_prenom" name="user_prenom" placeholder="" value="<?php echo $user_firstname; ?>" width="200px;" required>
                        </div>

                        <div class="col">
                            <label for="fonction" class="form-label">Role *</label>
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


                        <div class="col">
                            <label for="user_email" class="form-label">Email *</label>
                            <input type="text" class="form-control" id="user_email" name="user_email" placeholder="" value="<?php echo $user_email; ?>" width="200px;" required>
                        </div>

                        <div class="col">
                            <label for="user_pswd" class="form-label">Mot de Passe *</label>
                            <input type="text" class="form-control" id="user_pswd" name="user_pswd" placeholder="" value="<?php echo $user_pswd; ?>" width="200px;" required>
                        </div>


                        <button style="margin-top: 30px;background:#EF7837;color:white;" class="w-20 btn btn-lg" type="submit">Sauvegarder</button>

                    </form>
        <?php       
                } else {
                        echo "Aucun utilisateur trouvé.";
                }
            } else {
                echo "Erreur dans la requête : " . mysqli_error($database_connect);
            }
        ?>
    </body>
</html>
