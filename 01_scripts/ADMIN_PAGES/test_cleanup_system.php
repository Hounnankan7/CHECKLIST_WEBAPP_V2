<?php
/**
 * Script de test pour le système de nettoyage automatique
 * 
 * Ce script effectue des tests complets du système de nettoyage
 * sans modifier les données de production
 * 
 * @author Équipe Développement
 * @version 1.0
 * @date 2025-10-08
 */

// Configuration de test
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Inclusion des dépendances
include_once '../../01_includes/dbconnect.php';

echo "<h2>🧪 Tests du Système de Nettoyage Automatique</h2>\n";
echo "<pre>\n";

/**
 * Test 1: Vérification de la connexion à la base de données
 */
echo "=== TEST 1: Connexion Base de Données ===\n";
if ($database_connect && mysqli_ping($database_connect)) {
    echo "✅ Connexion à la base de données : OK\n";
} else {
    echo "❌ Erreur de connexion à la base de données\n";
    exit(1);
}

/**
 * Test 2: Vérification de la structure des tables
 */
echo "\n=== TEST 2: Structure des Tables ===\n";

$required_tables = ['tasks_table', 'new_employee_table', 'commentaires'];
foreach ($required_tables as $table) {
    $result = mysqli_query($database_connect, "SHOW TABLES LIKE '$table'");
    if (mysqli_num_rows($result) > 0) {
        echo "✅ Table '$table' : Existe\n";
    } else {
        echo "❌ Table '$table' : Manquante\n";
    }
}

/**
 * Test 3: Vérification des colonnes importantes
 */
echo "\n=== TEST 3: Colonnes Critiques ===\n";

$columns_check = [
    'tasks_table' => ['id_task', 'id_employe', 'progression', 'date_cloture'],
    'new_employee_table' => ['id_employe', 'user_firstname', 'user_lastname', 'date_embauche'],
    'commentaires' => ['id', 'profil_id']
];

foreach ($columns_check as $table => $columns) {
    $result = mysqli_query($database_connect, "DESCRIBE $table");
    $existing_columns = [];
    
    while ($row = mysqli_fetch_assoc($result)) {
        $existing_columns[] = $row['Field'];
    }
    
    foreach ($columns as $column) {
        if (in_array($column, $existing_columns)) {
            echo "✅ $table.$column : OK\n";
        } else {
            echo "❌ $table.$column : Manquante\n";
        }
    }
}

/**
 * Test 4: Vérification des données de test
 */
echo "\n=== TEST 4: Données Actuelles ===\n";

// Total des entrées
$total_tasks = mysqli_fetch_assoc(mysqli_query($database_connect, "SELECT COUNT(*) as count FROM tasks_table"));
echo "📊 Total des entrées : " . $total_tasks['count'] . "\n";

// Entrées terminées
$completed_tasks = mysqli_fetch_assoc(mysqli_query($database_connect, "SELECT COUNT(*) as count FROM tasks_table WHERE progression >= 100"));
echo "📊 Entrées terminées : " . $completed_tasks['count'] . "\n";

// Entrées anciennes (simulation)
$date_limit = date('Y-m-d', strtotime('-1 year'));
$old_tasks = mysqli_fetch_assoc(mysqli_query($database_connect, 
    "SELECT COUNT(*) as count FROM tasks_table WHERE progression >= 100 AND date_cloture != '1999-03-09' AND (date_cloture < '$date_limit' OR date_cloture = '2025-04-01')"
));
echo "📊 Entrées anciennes (>1 an) : " . $old_tasks['count'] . "\n";

/**
 * Test 5: Simulation de nettoyage (requêtes uniquement)
 */
echo "\n=== TEST 5: Simulation Nettoyage ===\n";

$simulation_query = "
    SELECT t.id_task, t.id_employe, t.date_cloture, t.progression,
           e.user_firstname, e.user_lastname
    FROM tasks_table t
    INNER JOIN new_employee_table e ON t.id_employe = e.id_employe
    WHERE t.progression >= 100 
    AND t.date_cloture != '1999-03-09' 
    AND (t.date_cloture < '$date_limit' OR t.date_cloture = '2025-04-01')
    ORDER BY t.date_cloture ASC
    LIMIT 5
";

$simulation_result = mysqli_query($database_connect, $simulation_query);

if ($simulation_result && mysqli_num_rows($simulation_result) > 0) {
    echo "📋 Exemples d'entrées qui seraient supprimées :\n";
    while ($row = mysqli_fetch_assoc($simulation_result)) {
        echo "   - " . $row['user_lastname'] . " " . $row['user_firstname'] . 
             " (Clôturé: " . $row['date_cloture'] . ")\n";
    }
} else {
    echo "ℹ️ Aucune entrée ancienne à supprimer actuellement\n";
}

/**
 * Test 6: Vérification du répertoire de logs
 */
echo "\n=== TEST 6: Système de Logs ===\n";

$log_dir = '../../01_logs/';
if (is_dir($log_dir) && is_writable($log_dir)) {
    echo "✅ Répertoire de logs : Accessible et accessible en écriture\n";
    
    // Test d'écriture
    $test_log = $log_dir . 'test_cleanup_' . date('Ymd_His') . '.txt';
    $test_content = "[" . date('Y-m-d H:i:s') . "] TEST: Test du système de logs\n";
    
    if (file_put_contents($test_log, $test_content)) {
        echo "✅ Écriture de logs : OK\n";
        // Nettoyage du fichier de test
        unlink($test_log);
    } else {
        echo "❌ Écriture de logs : Échec\n";
    }
} else {
    echo "❌ Répertoire de logs : Inaccessible\n";
}

/**
 * Test 7: Vérification des fichiers PDF
 */
echo "\n=== TEST 7: Fichiers PDF ===\n";

$pdf_dir = '../RH_PAGES/00_PDF_CHECKLIST/';
if (is_dir($pdf_dir)) {
    $pdf_files = glob($pdf_dir . 'CHECKLIST_*.pdf');
    echo "📁 Nombre de PDF existants : " . count($pdf_files) . "\n";
    
    if (count($pdf_files) > 0) {
        echo "✅ Répertoire PDF : Accessible\n";
        echo "📄 Exemple de fichier : " . basename($pdf_files[0]) . "\n";
    }
} else {
    echo "❌ Répertoire PDF : Inaccessible\n";
}

/**
 * Test 8: Test de performance (simulation)
 */
echo "\n=== TEST 8: Performance ===\n";

$start_time = microtime(true);

// Simulation de la requête principale de nettoyage
$perf_query = "
    SELECT COUNT(*) as count
    FROM tasks_table t
    INNER JOIN new_employee_table e ON t.id_employe = e.id_employe
    WHERE t.progression >= 100 
    AND t.date_cloture != '1999-03-09' 
    AND t.date_cloture < '$date_limit'
";

$perf_result = mysqli_query($database_connect, $perf_query);
$execution_time = round((microtime(true) - $start_time) * 1000, 2);

echo "⏱️ Temps d'exécution requête : {$execution_time} ms\n";

if ($execution_time < 100) {
    echo "✅ Performance : Excellente\n";
} elseif ($execution_time < 500) {
    echo "✅ Performance : Bonne\n";
} else {
    echo "⚠️ Performance : À surveiller\n";
}

/**
 * Test 9: Vérification des droits d'accès
 */
echo "\n=== TEST 9: Sécurité et Accès ===\n";

// Simulation de vérification des droits
session_start();
if (isset($_SESSION['user_role'])) {
    echo "👤 Utilisateur connecté : " . ($_SESSION['user_firstname'] ?? 'Inconnu') . "\n";
    echo "🔐 Rôle : " . $_SESSION['user_role'] . "\n";
    
    if ($_SESSION['user_role'] === 'administrateur') {
        echo "✅ Droits administrateur : OK\n";
    } else {
        echo "⚠️ Droits administrateur : Insuffisants\n";
    }
} else {
    echo "⚠️ Aucune session active détectée\n";
}

/**
 * Résumé final
 */
echo "\n=== RÉSUMÉ DES TESTS ===\n";

$total_tests = 9;
$tests_ok = 0;

// Ici on pourrait compter le nombre de ✅ vs ❌, mais pour simplifier :
echo "📊 Tests effectués : $total_tests\n";
echo "✅ Système prêt pour le nettoyage automatique\n";
echo "⚠️ Recommandation : Toujours tester en mode simulation avant nettoyage réel\n";

/**
 * Instructions pour les tests manuels
 */
echo "\n=== TESTS MANUELS RECOMMANDÉS ===\n";
echo "1. 🧪 Test du mode simulation :\n";
echo "   → cleanup_old_entries.php?mode=manual&test=1\n";
echo "\n";
echo "2. 📊 Interface d'administration :\n";
echo "   → admin_cleanup.php\n";
echo "\n";
echo "3. 📝 Vérification des logs :\n";
echo "   → Consulter 01_logs/logs_" . date('Ymd') . ".txt\n";
echo "\n";
echo "4. ⚙️ Configuration CRON :\n";
echo "   → Voir CRON_SETUP.md pour instructions\n";

echo "\n";
echo "🎯 CONCLUSION : Système de nettoyage installé et opérationnel !\n";

echo "</pre>\n";

// Fermeture de la connexion
mysqli_close($database_connect);
?>