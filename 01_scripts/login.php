<?php

    session_start();
    include_once '../01_includes/dbconnect.php';

    //Récupération des infos suite au remplissage du formulaire de connexion de login.php
    $user_role = $_POST['role'];
    $user_email = $_POST['email'];
    $user_password = $_POST['password'];

    //Vérification et redirection vers la bonne page d'acceuil en fonction du role de l'utilisateur
    if(strcasecmp($user_role, "") === 0){
        // Impossible car cette partie du formulaire est demandé absolument
    }elseif(strcasecmp($user_role, "Chef de Service") === 0){

        $result_login = mysqli_query($database_connect, "SELECT * FROM users_table WHERE user_role = 'Chef de Service' AND user_email='$user_email' AND user_pswd='$user_password'");
        
        while($row=mysqli_fetch_assoc($result_login)){
            $user_id = $row['id'];
            $user_lastname = $row['user_lastname'];//nom
            $user_firstname = $row['user_firstname'];//prenom
            $user_role = $row['user_role'];
        }

        if (mysqli_num_rows($result_login)>0) {

            $_SESSION['user_id']=$user_id;
            $_SESSION['user_email']=$user_email;
            $_SESSION['user_lastname']=$user_lastname;
            $_SESSION['user_firstname']=$user_firstname;
            $_SESSION['user_role']=$user_role;

            header('location: CDS_PAGES/cds_homepage.php');
    
        }else{
            echo "Identifiants incorrectes";
            header('location: ../index.php?login_error=<span style="color:red;">Role, Mail ou mot de passe incorrect</span>');
        }

    }elseif(strcasecmp($user_role, "RH") === 0){

        $result_login = mysqli_query($database_connect, "SELECT * FROM users_table WHERE user_role = 'rh' AND user_email='$user_email' AND user_pswd='$user_password'");
        
        while($row=mysqli_fetch_assoc($result_login)){
            $user_id = $row['id'];
            $user_lastname = $row['user_lastname'];//nom
            $user_firstname = $row['user_firstname'];//prenom
            $user_role = $row['user_role'];
        }

        if (mysqli_num_rows($result_login)>0) {

            $_SESSION['user_id']=$user_id;
            $_SESSION['user_email']=$user_email;
            $_SESSION['user_lastname']=$user_lastname;
            $_SESSION['user_firstname']=$user_firstname;
            $_SESSION['user_role']=$user_role;

            header('location: RH_PAGES/rh_homepage.php');
    
        }else{
            echo "Identifiants incorrectes";
            header('location: ../index.php?login_error=<span style="color:red;">Role, Mail ou mot de passe incorrect</span>');
        }


    }elseif(strcasecmp($user_role, "Informaticien") === 0){

        $result_login = mysqli_query($database_connect, "SELECT * FROM users_table WHERE user_role = 'informaticien' AND user_email='$user_email' AND user_pswd='$user_password'");
        
        while($row=mysqli_fetch_assoc($result_login)){
            $user_id = $row['id'];
            $user_lastname = $row['user_lastname'];//nom
            $user_firstname = $row['user_firstname'];//prenom
            $user_role = $row['user_role'];
        }

        if (mysqli_num_rows($result_login)>0) {

            $_SESSION['user_id']=$user_id;
            $_SESSION['user_email']=$user_email;
            $_SESSION['user_lastname']=$user_lastname;
            $_SESSION['user_firstname']=$user_firstname;
            $_SESSION['user_role']=$user_role;

            header('location: ADMIN_PAGES/admin_homepage.php');
    
        }else{
            echo "Identifiants incorrectes";
            header('location: ../index.php?login_error=<span style="color:red;">Role, Mail ou mot de passe incorrect</span>');
        }


    }elseif(strcasecmp($user_role, "Responsable Batiments") === 0){

        $result_login = mysqli_query($database_connect, "SELECT * FROM users_table WHERE user_role = 'batiment' AND user_email='$user_email' AND user_pswd='$user_password'");
        
        while($row=mysqli_fetch_assoc($result_login)){
            $user_id = $row['id'];
            $user_lastname = $row['user_lastname'];//nom
            $user_firstname = $row['user_firstname'];//prenom
            $user_role = $row['user_role'];
        }

        if (mysqli_num_rows($result_login)>0) {

            $_SESSION['user_id']=$user_id;
            $_SESSION['user_email']=$user_email;
            $_SESSION['user_lastname']=$user_lastname;
            $_SESSION['user_firstname']=$user_firstname;
            $_SESSION['user_role']=$user_role;

            header('location: BATIMENT_PAGES/batiment_homepage.php');
    
        }else{
            echo "Identifiants incorrectes";
            header('location: ../index.php?login_error=<span style="color:red;">Role, Mail ou mot de passe incorrect</span>');
        }


    }elseif(strcasecmp($user_role, "Responsable Achats") === 0){

        $result_login = mysqli_query($database_connect, "SELECT * FROM users_table WHERE user_role = 'achat' AND user_email='$user_email' AND user_pswd='$user_password'");
        
        while($row=mysqli_fetch_assoc($result_login)){
            $user_id = $row['id'];
            $user_lastname = $row['user_lastname'];//nom
            $user_firstname = $row['user_firstname'];//prenom
            $user_role = $row['user_role'];
        }

        if (mysqli_num_rows($result_login)>0) {

            $_SESSION['user_id']=$user_id;
            $_SESSION['user_email']=$user_email;
            $_SESSION['user_lastname']=$user_lastname;
            $_SESSION['user_firstname']=$user_firstname;
            $_SESSION['user_role']=$user_role;

            header('location: ACHAT_PAGES/achat_homepage.php');
    
        }else{
            echo "Identifiants incorrectes";
            header('location: ../index.php?login_error=<span style="color:red;">Role, Mail ou mot de passe incorrect</span>');
        }

    }

    // LOGGING
    $log_dir = __DIR__ . '/../01_logs/';
    if (!is_dir($log_dir)) { mkdir($log_dir, 0777, true); }
    $log_file = $log_dir . 'logs_' . date('Ymd') . '.txt';
    $user = isset($user_firstname) ? $user_firstname . ' ' . $user_lastname : 'ANONYMOUS';
    $action = 'LOGIN';
    $state = isset($_SESSION['user_id']) ? 'success' : 'failed';
    $dateheure = date('Y-m-d H:i:s');
    $log_entry = "$user:$action:$state:$dateheure\n";
    file_put_contents($log_file, $log_entry, FILE_APPEND);

?>