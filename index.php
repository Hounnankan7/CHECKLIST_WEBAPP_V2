<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>SUIVI CHECK LIST</title>

        <!-- Telechargement de bootstrap et bootstrap icon -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
        <script src="https://kit.fontawesome.com/857f401658.js" crossorigin="anonymous"></script>
        <link rel="shortcut icon" type="image/png" href="01_assets/faviconV2.png"/>


        <style>
            .container {
                max-width: 400px;
                padding: 20px;
                background: white;
                border-radius: 10px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                margin-top: 40px;
            }    

            .logo {
                display: block;
                margin: 0 auto;
            }
            
            footer{
                width: 100%;
                padding: 10px;
                background-color: #343a40;
                color: white;
                text-align: center;
                position: fixed;
                bottom: 0;
            }
        </style>

    </head>

    <body>

        <!--Entete de la page-->
        <header>
            <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
                <div class="container-fluid">
                    <a class="navbar-brand" href="#"><img class="logo" src="01_assets/LOGO_BLANC.png" alt="JCMI" title="JCMI" width=15% height=10%></a>
                </div>
            </nav>
        </header>

        <!--Formulaire de connexion-->
        <section>
            <form class="container" id="login_form" method="POST" action="01_scripts/login.php">

                <h1 style="margin-top: 10px;margin-bottom: 15px">IDENTIFICATION</h1>

                <!--Message to display on success-->
                <?php
                    if(isset($_GET['login_error'])){?>
                    <?php echo $_GET['login_error'];?>
                <?php }?>
                <!--------------------------------->

                <div class="mb-3">
                <label class="form_label">Se connecter en tant que : </label>
                <select class="form-select" id="role" name="role" required>
                    <option value="">Choisir un rôle...</option>
                    <option>Chef de Service</option>
                    <option>RH</option>
                    <option>Informaticien</option>
                    <option>Responsable Batiments</option>
                    <option>Responsable Achats</option>
                </select>
                <div class="invalid-feedback">
                    Faite un choix valable.
                </div>
            </div>

            <div class="mb-3">
                <label class="form_label">Adresse Mail</label>
                <input class="form-control" type="email" name="email" placeholder="Email" required>
            </div>
            <div class="mb-3">
                <label class="form_label">Mot de Passe</label>
                <input class="form-control" type="password" name="password" id="password" placeholder="Password" required>
            </div>

            <div class="btn_submit" style="text-align: center;">
                <button class="btn" style="background:rgb(33 37 41);color:white;" type="submit" name="loginbtn">CONNEXION</button>
            </div>
            </form>
        </section>


        <!--Pied de page-->
        <footer class="bg-dark">
            <p>&copy; 2025 JCMI - Version 2.0 | Tous droits réservés.</p>
            <p>Support : informatiques@groupemichel.com | Tél : 1350 | <a style="color:#EF7837;" href="http://sos-informatique.gm.local" target="_blank">SOS INFORMATIQUE</a></p>
        </footer>


    </body>

</html>