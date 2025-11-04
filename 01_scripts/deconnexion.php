<?php
// Démarrer la session
session_start();

// Supprimer toutes les variables de session
$user = isset($_SESSION['user_firstname']) ? $_SESSION['user_firstname'] . ' ' . $_SESSION['user_lastname'] : 'ANONYMOUS';
session_unset();

// Détruire la session
session_destroy();

// LOGGING
$log_dir = __DIR__ . '/../01_logs/';
if (!is_dir($log_dir)) { mkdir($log_dir, 0777, true); }
$log_file = $log_dir . 'logs_' . date('Ymd') . '.txt';
$action = 'LOGOUT';
$state = 'success';
$dateheure = date('Y-m-d H:i:s');
$log_entry = "$user:$action:$state:$dateheure\n";
file_put_contents($log_file, $log_entry, FILE_APPEND);

// Supprimer le cookie de session s'il existe
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 3600, '/');
}

// Rediriger l'utilisateur vers la page de connexion ou une autre page
header('Location: ../index.php');

exit();
?>
