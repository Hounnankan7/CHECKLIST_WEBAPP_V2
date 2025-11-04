<?php 
    session_start();
    include_once '../../01_includes/dbconnect.php';
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>RECUPERATION MATERIELS INFORMATIQUES</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
        <script src="https://kit.fontawesome.com/857f401658.js" crossorigin="anonymous"></script>
        <link rel="shortcut icon" type="image/png" href="../../01_assets/faviconV2.png"/>
    </head>
    <body>
        <h4 style='margin-top:20px;margin-bottom:20px;background:#EF7837;color: white;'>LISTE EQUIPEMENTS A RECUPERER</h4>
        
         
        <form class="container" style="margin:0px; padding-top:20px; padding-bottom:20px" id="recup_info" method="POST" action="process_hardware.php">

        <?php 

            
            echo '<label class="form-check-label" for="mail">Supression de mail</label>';
            echo "<input type='checkbox' class='form-check-input' id='mail' name='mail' value='valeur2' checked>";    
        ?>


            <button style="margin-top: 30px;" class="w-50 btn btn-primary btn-lg" type="submit">Sauvegarder</button>

        </form>
    </body>
</html>
