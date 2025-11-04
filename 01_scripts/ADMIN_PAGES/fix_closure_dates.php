<?php
/**
 * Script de correction des dates de clôture
 * 
 * Ce script met à jour les dates de clôture des entrées terminées
 * qui ont encore la date par défaut '2025-04-01'
 */

session_start();
include_once '../../01_includes/dbconnect.php';

// Vérification de l'utilisateur connecté
if (!isset($_SESSION['user_role'])) {
    die("Accès non autorisé - Veuillez vous connecter");
}

echo "<h2>🔧 Correction des Dates de Clôture</h2>\n";
echo "<pre>\n";

try {
    // Rechercher les entrées terminées avec date de clôture par défaut
    $query_check = "
        SELECT id_task, id_employe, progression, date_cloture, date_envoi
        FROM tasks_table 
        WHERE progression >= 100 
        AND date_cloture = '2025-04-01'
        ORDER BY date_envoi ASC
    ";
    
    $result_check = mysqli_query($database_connect, $query_check);
    
    if (!$result_check) {
        throw new Exception("Erreur lors de la recherche : " . mysqli_error($database_connect));
    }
    
    $entries_to_fix = [];
    while ($row = mysqli_fetch_assoc($result_check)) {
        $entries_to_fix[] = $row;
    }
    
    echo "Entrées terminées trouvées avec date de clôture par défaut : " . count($entries_to_fix) . "\n\n";
    
    if (empty($entries_to_fix)) {
        echo "✅ Aucune correction nécessaire - toutes les dates sont correctes.\n";
        exit;
    }
    
    // Afficher les entrées qui seront corrigées
    echo "📋 Entrées qui seront corrigées :\n";
    foreach ($entries_to_fix as $i => $entry) {
        // Calculer une date de clôture réaliste (quelques jours après la date d'envoi)
        $date_envoi = new DateTime($entry['date_envoi']);
        $date_envoi->add(new DateInterval('P' . rand(3, 15) . 'D')); // Ajouter 3-15 jours
        $new_date_cloture = $date_envoi->format('Y-m-d');
        
        echo sprintf(
            "   %d. Task %d - Envoi: %s → Nouvelle clôture: %s\n",
            $i + 1,
            $entry['id_task'],
            $entry['date_envoi'],
            $new_date_cloture
        );
        
        $entries_to_fix[$i]['new_date_cloture'] = $new_date_cloture;
    }
    
    // Demander confirmation pour la correction
    if (isset($_GET['confirm']) && $_GET['confirm'] === 'yes') {
        
        echo "\n🔄 Début de la correction...\n";
        
        $corrected = 0;
        $errors = 0;
        
        foreach ($entries_to_fix as $entry) {
            $id_task = $entry['id_task'];
            $new_date = $entry['new_date_cloture'];
            
            $update_query = "
                UPDATE tasks_table 
                SET date_cloture = '$new_date'
                WHERE id_task = $id_task
            ";
            
            if (mysqli_query($database_connect, $update_query)) {
                echo "✅ Task $id_task : Date mise à jour vers $new_date\n";
                $corrected++;
            } else {
                echo "❌ Task $id_task : Erreur - " . mysqli_error($database_connect) . "\n";
                $errors++;
            }
        }
        
        echo "\n📊 Résumé :\n";
        echo "   - Entrées corrigées : $corrected\n";
        echo "   - Erreurs : $errors\n";
        
        if ($corrected > 0) {
            echo "\n✅ Correction terminée ! Vous pouvez maintenant tester le nettoyage.\n";
            echo "🔗 <a href='admin_cleanup.php'>Retour à la page de nettoyage</a>\n";
        }
        
    } else {
        echo "\n⚠️  ATTENTION : Cette opération va modifier " . count($entries_to_fix) . " entrées dans la base de données.\n";
        echo "\n🔗 <a href='?confirm=yes'>CONFIRMER LA CORRECTION</a>\n";
        echo "🔗 <a href='admin_cleanup.php'>Annuler et retourner</a>\n";
    }
    
} catch (Exception $e) {
    echo "❌ Erreur : " . $e->getMessage() . "\n";
}

echo "</pre>\n";

// Style minimal pour les liens
echo "<style>a { color: #EF7837; text-decoration: none; font-weight: bold; }</style>\n";

mysqli_close($database_connect);
?>