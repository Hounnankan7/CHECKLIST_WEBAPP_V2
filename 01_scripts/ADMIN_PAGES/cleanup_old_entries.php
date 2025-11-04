<?php
/**
 * Script de nettoyage automatique des entrées terminées anciennes
 * 
 * Ce script supprime les entrées terminées (progression >= 100) de plus d'un an
 * Il peut être exécuté via cron ou manuellement depuis l'interface d'administration
 * 
 * @author Équipe Développement
 * @version 1.0
 * @date 2025-10-08
 */

// Configuration de sécurité
$allowed_modes = ['cron', 'manual', 'test'];
$execution_mode = isset($_GET['mode']) ? $_GET['mode'] : 'manual';

// Vérification du mode d'exécution
if (!in_array($execution_mode, $allowed_modes)) {
    die("Mode d'exécution non autorisé");
}

// Pour le mode manuel, vérification de l'authentification
if ($execution_mode === 'manual') {
    session_start();
    if (!isset($_SESSION['user_role'])) {
        die("Accès non autorisé - Veuillez vous connecter");
    }
    // Accepter tous les rôles connectés pour être cohérent avec les autres pages admin
}

// Inclusion de la connexion à la base de données
include_once '../../01_includes/dbconnect.php';

/**
 * Fonction pour écrire dans les logs
 */
function writeToLog($message) {
    $log_file = '../../01_logs/logs_' . date('Ymd') . '.txt';
    $timestamp = date('Y-m-d H:i:s');
    $log_entry = "[$timestamp] CLEANUP: $message\n";
    file_put_contents($log_file, $log_entry, FILE_APPEND | LOCK_EX);
}

/**
 * Fonction pour supprimer les fichiers PDF associés
 */
function deletePDFFiles($firstname, $lastname) {
    $pdf_path = '../RH_PAGES/00_PDF_CHECKLIST/CHECKLIST_' . $lastname . '_' . $firstname . '.pdf';
    if (file_exists($pdf_path)) {
        if (unlink($pdf_path)) {
            writeToLog("PDF supprimé : $pdf_path");
            return true;
        } else {
            writeToLog("Erreur lors de la suppression du PDF : $pdf_path");
            return false;
        }
    }
    return true; // Pas d'erreur si le fichier n'existe pas
}

/**
 * Fonction principale de nettoyage
 */
function cleanupOldEntries($test_mode = false) {
    global $database_connect;
    
    $results = [
        'total_found' => 0,
        'total_deleted' => 0,
        'errors' => [],
        'deleted_entries' => []
    ];
    
    try {
        // Calcul de la date limite (1 an avant aujourd'hui)
        $date_limit = date('Y-m-d', strtotime('-1 year'));
        
        writeToLog("Début du nettoyage des entrées antérieures au $date_limit");
        
        // Recherche des entrées terminées de plus d'un an
        $query = "
            SELECT t.id_task, t.id_employe, t.date_cloture, t.progression,
                   e.user_firstname, e.user_lastname, e.date_embauche
            FROM tasks_table t
            INNER JOIN new_employee_table e ON t.id_employe = e.id_employe
            WHERE t.progression >= 100 
            AND t.date_cloture != '1999-03-09' 
            AND (t.date_cloture < '$date_limit' OR t.date_cloture = '2025-04-01')
            ORDER BY t.date_cloture ASC
        ";
        
        $result = mysqli_query($database_connect, $query);
        
        if (!$result) {
            throw new Exception("Erreur lors de la recherche des entrées : " . mysqli_error($database_connect));
        }
        
        $results['total_found'] = mysqli_num_rows($result);
        writeToLog("Nombre d'entrées trouvées à supprimer : " . $results['total_found']);
        
        if ($results['total_found'] == 0) {
            writeToLog("Aucune entrée ancienne à supprimer");
            return $results;
        }
        
        // Si mode test, on s'arrête ici
        if ($test_mode) {
            writeToLog("Mode test activé - Aucune suppression effectuée");
            while ($row = mysqli_fetch_assoc($result)) {
                $results['deleted_entries'][] = [
                    'nom' => $row['user_lastname'] . ' ' . $row['user_firstname'],
                    'date_embauche' => $row['date_embauche'],
                    'date_cloture' => $row['date_cloture'],
                    'id_task' => $row['id_task'],
                    'id_employe' => $row['id_employe']
                ];
            }
            return $results;
        }
        
        // Début de la transaction pour assurer la cohérence
        mysqli_begin_transaction($database_connect);
        
        while ($row = mysqli_fetch_assoc($result)) {
            $id_task = $row['id_task'];
            $id_employe = $row['id_employe'];
            $firstname = $row['user_firstname'];
            $lastname = $row['user_lastname'];
            $date_cloture = $row['date_cloture'];
            
            $entry_info = "$lastname $firstname (Task: $id_task, Employé: $id_employe, Clôturé: $date_cloture)";
            
            try {
                // Suppression des commentaires associés
                $delete_comments = mysqli_query($database_connect, 
                    "DELETE FROM commentaires WHERE profil_id = '$id_task'"
                );
                
                if (!$delete_comments) {
                    throw new Exception("Erreur suppression commentaires pour $entry_info");
                }
                
                // Suppression de la tâche
                $delete_task = mysqli_query($database_connect, 
                    "DELETE FROM tasks_table WHERE id_task = '$id_task'"
                );
                
                if (!$delete_task) {
                    throw new Exception("Erreur suppression tâche pour $entry_info");
                }
                
                // Suppression de l'employé
                $delete_employee = mysqli_query($database_connect, 
                    "DELETE FROM new_employee_table WHERE id_employe = '$id_employe'"
                );
                
                if (!$delete_employee) {
                    throw new Exception("Erreur suppression employé pour $entry_info");
                }
                
                // Suppression du fichier PDF associé
                deletePDFFiles($firstname, $lastname);
                
                // Ajout à la liste des suppressions réussies
                $results['deleted_entries'][] = [
                    'nom' => "$lastname $firstname",
                    'date_embauche' => $row['date_embauche'],
                    'date_cloture' => $date_cloture,
                    'id_task' => $id_task,
                    'id_employe' => $id_employe
                ];
                
                $results['total_deleted']++;
                writeToLog("Suppression réussie : $entry_info");
                
            } catch (Exception $e) {
                $results['errors'][] = $e->getMessage();
                writeToLog("ERREUR : " . $e->getMessage());
                // Continue avec les autres entrées même en cas d'erreur
            }
        }
        
        // Validation de la transaction
        mysqli_commit($database_connect);
        writeToLog("Transaction validée - " . $results['total_deleted'] . " entrées supprimées avec succès");
        
    } catch (Exception $e) {
        // Annulation de la transaction en cas d'erreur critique
        mysqli_rollback($database_connect);
        $results['errors'][] = "Erreur critique : " . $e->getMessage();
        writeToLog("ERREUR CRITIQUE : " . $e->getMessage());
    }
    
    return $results;
}

// Exécution du script selon le mode
$start_time = microtime(true);

switch ($execution_mode) {
    case 'cron':
        // Mode automatique (cron)
        writeToLog("Démarrage automatique du nettoyage (CRON)");
        $results = cleanupOldEntries();
        writeToLog("Nettoyage automatique terminé - " . $results['total_deleted'] . " entrées supprimées");
        break;
        
    case 'test':
        // Mode test (simulation)
        writeToLog("Démarrage du mode test");
        $results = cleanupOldEntries(true);
        break;
        
    case 'manual':
    default:
        // Mode manuel (interface admin)
        $test_mode = isset($_GET['test']) && $_GET['test'] === '1';
        $results = cleanupOldEntries($test_mode);
        break;
}

$execution_time = round(microtime(true) - $start_time, 2);
writeToLog("Temps d'exécution : " . $execution_time . " secondes");

// Affichage des résultats pour le mode manuel
if ($execution_mode === 'manual') {
    ?>
    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Nettoyage des entrées anciennes</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    </head>
    <body>
        <div class="container mt-4">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h4><i class="fas fa-broom me-2"></i>Nettoyage des entrées anciennes</h4>
                        </div>
                        <div class="card-body">
                            
                            <?php if (isset($_GET['test']) && $_GET['test'] === '1'): ?>
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <strong>Mode Test :</strong> Aucune suppression n'a été effectuée
                                </div>
                            <?php endif; ?>
                            
                            <div class="row mb-4">
                                <div class="col-md-3">
                                    <div class="card bg-light">
                                        <div class="card-body text-center">
                                            <h5 class="text-primary"><?php echo $results['total_found']; ?></h5>
                                            <small>Entrées trouvées</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card bg-light">
                                        <div class="card-body text-center">
                                            <h5 class="text-success"><?php echo $results['total_deleted']; ?></h5>
                                            <small>Entrées supprimées</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card bg-light">
                                        <div class="card-body text-center">
                                            <h5 class="text-danger"><?php echo count($results['errors']); ?></h5>
                                            <small>Erreurs</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card bg-light">
                                        <div class="card-body text-center">
                                            <h5 class="text-info"><?php echo $execution_time; ?>s</h5>
                                            <small>Temps d'exécution</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <?php if (!empty($results['errors'])): ?>
                            <div class="alert alert-danger">
                                <h6><i class="fas fa-exclamation-triangle me-2"></i>Erreurs rencontrées :</h6>
                                <ul class="mb-0">
                                    <?php foreach ($results['errors'] as $error): ?>
                                        <li><?php echo htmlspecialchars($error); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($results['deleted_entries'])): ?>
                            <div class="table-responsive">
                                <h6><?php echo isset($_GET['test']) ? 'Entrées qui seraient supprimées :' : 'Entrées supprimées :'; ?></h6>
                                <table class="table table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>Nom Prénom</th>
                                            <th>Date Embauche</th>
                                            <th>Date Clôture</th>
                                            <th>ID Tâche</th>
                                            <th>ID Employé</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($results['deleted_entries'] as $entry): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($entry['nom']); ?></td>
                                            <td><?php echo date('d/m/Y', strtotime($entry['date_embauche'])); ?></td>
                                            <td><?php echo date('d/m/Y', strtotime($entry['date_cloture'])); ?></td>
                                            <td><?php echo $entry['id_task']; ?></td>
                                            <td><?php echo $entry['id_employe']; ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php endif; ?>
                            
                            <div class="mt-4">
                                <a href="admin_homepage.php" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>Retour à l'administration
                                </a>
                                
                                <?php if (!isset($_GET['test'])): ?>
                                <a href="cleanup_old_entries.php?mode=manual&test=1" class="btn btn-info">
                                    <i class="fas fa-search me-2"></i>Mode Test
                                </a>
                                <?php else: ?>
                                <a href="cleanup_old_entries.php?mode=manual" class="btn btn-warning" 
                                   onclick="return confirm('Êtes-vous sûr de vouloir supprimer définitivement ces entrées ?')">
                                    <i class="fas fa-trash me-2"></i>Supprimer Réellement
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
    </html>
    <?php
}

// Fermeture de la connexion
mysqli_close($database_connect);
?>