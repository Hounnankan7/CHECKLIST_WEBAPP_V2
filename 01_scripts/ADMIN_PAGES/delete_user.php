<?php

    session_start();
    include_once '../../01_includes/dbconnect.php';

    $deleteUser_query_id = $_GET['id_user'];

    $delete_task = mysqli_query($database_connect, "DELETE FROM users_table WHERE id = '$deleteUser_query_id'");

    // LOGGING
    $log_dir = __DIR__ . '/../../01_logs/';
    if (!is_dir($log_dir)) { mkdir($log_dir, 0777, true); }
    $log_file = $log_dir . 'logs_' . date('Ymd') . '.txt';
    $user = isset($_SESSION['user_firstname']) ? $_SESSION['user_firstname'] . ' ' . $_SESSION['user_lastname'] : 'ANONYMOUS';
    $action = 'DELETE_USER';
    $state = $delete_task ? 'success' : 'failed';
    $dateheure = date('Y-m-d H:i:s');
    $log_entry = "$user:$action:$state:$dateheure\n";
    file_put_contents($log_file, $log_entry, FILE_APPEND);

    if($delete_task){
        header('location: ../../01_scripts/ADMIN_PAGES/admin_systemeadmin.php?delete_UserState=success');
    }else{
        header('location: ../../01_scripts/ADMIN_PAGES/admin_systemeadmin.php?delete_UserState=fail');
    }

?>