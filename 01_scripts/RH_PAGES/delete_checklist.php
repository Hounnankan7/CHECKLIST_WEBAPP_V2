<?php

    session_start();
    include_once '../../01_includes/dbconnect.php';

    $deleteChecklist_query_id = $_GET['checklistId'];
    $deleteEmploye_query_id = $_GET['employeId'];

    $deletechecklist_task = mysqli_query($database_connect, "DELETE FROM tasks_table WHERE id_task = '$deleteChecklist_query_id'");

    //$deleteemploye_task = mysqli_query($database_connect, "DELETE FROM new_employee_table WHERE id_employe = '$deleteEmploye_query_id'");


    // LOGGING
    $log_dir = __DIR__ . '/../../01_logs/';
    if (!is_dir($log_dir)) { mkdir($log_dir, 0777, true); }
    $log_file = $log_dir . 'logs_' . date('Ymd') . '.txt';
    $user = isset($_SESSION['user_firstname']) ? $_SESSION['user_firstname'] . ' ' . $_SESSION['user_lastname'] : 'ANONYMOUS';
    $action = 'DELETE_CHECKLIST';
    $state = $deletechecklist_task ? 'success' : 'failed';
    $dateheure = date('Y-m-d H:i:s');
    $log_entry = "$user:$action:$state:$dateheure\n";
    file_put_contents($log_file, $log_entry, FILE_APPEND);


    if($deletechecklist_task){
        header('location: ../../01_scripts/RH_PAGES/rh_homepage.php?delete_EntryState=success');
    }else{
        header('location: ../../01_scripts/RH_PAGES/rh_homepage.php?delete_EntryState=fail');
    }

?>