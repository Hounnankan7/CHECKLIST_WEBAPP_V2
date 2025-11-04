<?php
// Activer l'affichage des erreurs pour le debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// Vérification des droits administrateur (permissif comme les autres pages admin)
if (!isset($_SESSION['user_role'])) {
    header("Location: ../login.php");
    exit();
}

include_once '../../01_includes/dbconnect.php';

// Fonction pour calculer les statistiques
function getCleanupStatistics($database_connect) {
    $stats = [];
    
    try {
        // Entrées terminées depuis plus d'un an
        $date_limit = date('Y-m-d', strtotime('-1 year'));
        $query_old = "
            SELECT COUNT(*) as count, MIN(date_cloture) as oldest_entry
            FROM tasks_table 
            WHERE progression >= 100 
            AND date_cloture != '1999-03-09' 
            AND (date_cloture < '$date_limit' OR date_cloture = '2025-04-01')
        ";
        
        $result_old = mysqli_query($database_connect, $query_old);
        if (!$result_old) {
            throw new Exception("Erreur requête old entries: " . mysqli_error($database_connect));
        }
        
        $row_old = mysqli_fetch_assoc($result_old);
        $stats['old_entries'] = $row_old['count'] ?: 0;
        $stats['oldest_entry'] = $row_old['oldest_entry'];
        
        // Total des entrées terminées
        $query_total_completed = "SELECT COUNT(*) as count FROM tasks_table WHERE progression >= 100 AND date_cloture != '1999-03-09'";
        $result_total_completed = mysqli_query($database_connect, $query_total_completed);
        if (!$result_total_completed) {
            throw new Exception("Erreur requête total completed: " . mysqli_error($database_connect));
        }
        
        $row_total_completed = mysqli_fetch_assoc($result_total_completed);
        $stats['total_completed'] = $row_total_completed['count'] ?: 0;
        
        // Espace disque approximatif (estimation basée sur la taille moyenne des PDF)
        $stats['estimated_disk_space'] = $stats['old_entries'] * 0.5; // 500 Ko par PDF en moyenne
        
        // Dernière exécution du nettoyage (chercher dans les logs)
        $log_files = glob('../../01_logs/logs_*.txt');
        $last_cleanup = null;
        
        if ($log_files) {
            foreach (array_reverse($log_files) as $log_file) {
                if (file_exists($log_file)) {
                    $content = file_get_contents($log_file);
                    if ($content && strpos($content, 'CLEANUP:') !== false) {
                        preg_match('/\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\] CLEANUP:/', $content, $matches);
                        if (!empty($matches[1])) {
                            $last_cleanup = $matches[1];
                            break;
                        }
                    }
                }
            }
        }
        
        $stats['last_cleanup'] = $last_cleanup;
        
    } catch (Exception $e) {
        // En cas d'erreur, retourner des valeurs par défaut
        $stats = [
            'old_entries' => 0,
            'oldest_entry' => null,
            'total_completed' => 0,
            'estimated_disk_space' => 0,
            'last_cleanup' => null,
            'error' => $e->getMessage()
        ];
    }
    
    return $stats;
}

$stats = getCleanupStatistics($database_connect);

// Afficher une erreur si il y en a une
if (isset($stats['error'])) {
    echo '<div class="alert alert-danger">Erreur lors du chargement des statistiques: ' . htmlspecialchars($stats['error']) . '</div>';
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration - Nettoyage Automatique</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="shortcut icon" type="image/png" href="../../01_assets/faviconV2.png"/>
    
    <style>
        .navbar-nav .nav-item .nav-link {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            margin-top: 14px;
        }
        
        .navbar-nav .nav-item .nav-link i {
            margin-bottom: 10px;
        }
        
        .stats-card {
            border-left: 4px solid #EF7837;
            transition: transform 0.2s;
        }
        
        .stats-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        .danger-zone {
            border: 2px solid #dc3545;
            border-radius: 8px;
            padding: 20px;
            margin-top: 20px;
            background-color: #fff5f5;
        }
        
        footer {
            width: 100%;
            padding: 10px;
            background-color: #343a40;
            color: white;
            text-align: center;
            bottom: 0;
            margin-top: 40px;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <header class="sticky-top">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="#" style="width: 20%;">
                    <img class="logo" src="../../01_assets/LOGO_BLANC.png" alt="JCMI" title="JCMI" width="60%" height="30%">
                </a>
                
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="admin_systemeadmin.php"><i class="fa-solid fa-user-gear fa-xl"></i>Administration Système</a></li>
                    <li class="nav-item"><a class="nav-link" href="admin_homepage.php"><i class="fa-solid fa-person-walking-arrow-right fa-xl"></i> Suivi Entrées</a></li>
                    <li class="nav-item"><a class="nav-link" href="admin_closedentry.php"><i class="fa-solid fa-list-check fa-xl"></i> Entrées Terminées</a></li>
                    <li class="nav-item"><a class="nav-link active" href="admin_cleanup.php"><i class="fa-solid fa-broom fa-xl"></i> Nettoyage</a></li>
                    <li class="nav-item"><a class="nav-link" href="../deconnexion.php"><i class="fa-solid fa-power-off fa-xl"></i> Déconnexion</a></li>
                </ul>
            </div>
        </nav>
    </header>

    <!-- User info -->
    <div class="user_connected" style="background:#EF7837;">
        <p style="color: white;margin-left:12px;margin-right:12px;padding-top:5px;padding-bottom:5px;font-size:18px;"> 
            <?php echo 'Utilisateur connecté : <b>' . $_SESSION['user_firstname'] . ' ' . $_SESSION['user_lastname'] . '</b>'; ?>
        </p>
    </div>

    <!-- Main Content -->
    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4><i class="fas fa-broom me-2"></i>Administration du Nettoyage Automatique</h4>
                        <p class="mb-0">Gestion des entrées terminées anciennes et optimisation de l'espace disque</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistiques -->
        <div class="row mt-4">
            <div class="col-md-3">
                <div class="card stats-card h-100">
                    <div class="card-body text-center">
                        <h3 class="text-warning"><?php echo $stats['old_entries']; ?></h3>
                        <p class="card-text">Entrées anciennes<br><small class="text-muted">(> 1 an)</small></p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card stats-card h-100">
                    <div class="card-body text-center">
                        <h3 class="text-info"><?php echo $stats['total_completed']; ?></h3>
                        <p class="card-text">Total entrées<br><small class="text-muted">terminées</small></p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card stats-card h-100">
                    <div class="card-body text-center">
                        <h3 class="text-success"><?php echo number_format($stats['estimated_disk_space'], 1); ?> MB</h3>
                        <p class="card-text">Espace récupérable<br><small class="text-muted">estimation</small></p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card stats-card h-100">
                    <div class="card-body text-center">
                        <?php if ($stats['last_cleanup']): ?>
                            <h6 class="text-primary"><?php echo date('d/m/Y', strtotime($stats['last_cleanup'])); ?></h6>
                            <small class="text-muted"><?php echo date('H:i', strtotime($stats['last_cleanup'])); ?></small>
                        <?php else: ?>
                            <h6 class="text-secondary">Jamais</h6>
                        <?php endif; ?>
                        <p class="card-text">Dernier nettoyage</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions de nettoyage -->
        <div class="row mt-4">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-tools me-2"></i>Actions de Nettoyage</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="d-grid gap-2">
                                    <a href="cleanup_old_entries.php?mode=manual&test=1" class="btn btn-info btn-lg">
                                        <i class="fas fa-search me-2"></i>Simulation (Mode Test)
                                    </a>
                                    <small class="text-muted">Voir quelles entrées seraient supprimées sans les supprimer réellement</small>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="d-grid gap-2">
                                    <a href="cleanup_old_entries.php?mode=manual" class="btn btn-warning btn-lg" 
                                       onclick="return confirm('⚠️ ATTENTION ⚠️\n\nCette action va supprimer DÉFINITIVEMENT les entrées terminées depuis plus d\'un an.\n\nÊtes-vous absolument certain de vouloir continuer ?')">
                                        <i class="fas fa-broom me-2"></i>Nettoyage Manuel
                                    </a>
                                    <small class="text-muted">Supprimer immédiatement les entrées anciennes</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-clock me-2"></i>Automatisation</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Fréquence :</strong> Mensuelle (1er de chaque mois)</p>
                        <p><strong>Heure :</strong> 02:00</p>
                        <p><strong>Statut :</strong> 
                            <span class="badge bg-success">Configuré</span>
                        </p>
                        <a href="CRON_SETUP.md" target="_blank" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-book me-1"></i>Documentation
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Détails des entrées anciennes -->
        <?php if ($stats['old_entries'] > 0): ?>
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-list me-2"></i>Aperçu des Entrées Anciennes</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <?php
                            $date_limit = date('Y-m-d', strtotime('-1 year'));
                            $preview_query = "
                                SELECT t.id_task, t.date_cloture, t.progression,
                                       e.user_firstname, e.user_lastname, e.date_embauche
                                FROM tasks_table t
                                INNER JOIN new_employee_table e ON t.id_employe = e.id_employe
                                WHERE t.progression >= 100 
                                AND t.date_cloture != '1999-03-09' 
                                AND (t.date_cloture < '$date_limit' OR t.date_cloture = '2025-04-01')
                                ORDER BY t.date_cloture ASC
                                LIMIT 10
                            ";
                            
                            $preview_result = mysqli_query($database_connect, $preview_query);
                            ?>
                            
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Nom Prénom</th>
                                        <th>Date Embauche</th>
                                        <th>Date Clôture</th>
                                        <th>Ancienneté</th>
                                        <th>ID Tâche</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = mysqli_fetch_assoc($preview_result)): ?>
                                    <?php
                                        $date_cloture = new DateTime($row['date_cloture']);
                                        $now = new DateTime();
                                        $interval = $now->diff($date_cloture);
                                        $anciennete = $interval->format('%y an(s) %m mois');
                                    ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row['user_lastname'] . ' ' . $row['user_firstname']); ?></td>
                                        <td><?php echo date('d/m/Y', strtotime($row['date_embauche'])); ?></td>
                                        <td><?php echo date('d/m/Y', strtotime($row['date_cloture'])); ?></td>
                                        <td><span class="badge bg-warning"><?php echo $anciennete; ?></span></td>
                                        <td><?php echo $row['id_task']; ?></td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                            
                            <?php if ($stats['old_entries'] > 10): ?>
                            <p class="text-muted mt-2">
                                <i class="fas fa-info-circle me-1"></i>
                                Affichage des 10 premières entrées sur <?php echo $stats['old_entries']; ?> total.
                            </p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Zone de danger -->
        <div class="danger-zone">
            <h5 class="text-danger"><i class="fas fa-exclamation-triangle me-2"></i>Zone de Danger</h5>
            <p><strong>⚠️ AVERTISSEMENT :</strong> Le nettoyage automatique supprime définitivement les données. Cette action est irréversible.</p>
            
            <div class="row">
                <div class="col-md-6">
                    <h6>Données supprimées :</h6>
                    <ul>
                        <li>Entrées dans la table <code>tasks_table</code></li>
                        <li>Employés dans la table <code>new_employee_table</code></li>
                        <li>Commentaires associés</li>
                        <li>Fichiers PDF des checklists</li>
                    </ul>
                </div>
                
                <div class="col-md-6">
                    <h6>Critères de suppression :</h6>
                    <ul>
                        <li>Progression = 100% (terminé)</li>
                        <li>Date de clôture > 1 an <strong>OU</strong> = 2025-04-01</li>
                        <li>Date de clôture valide (≠ 1999-03-09)</li>
                    </ul>
                    <small class="text-muted">
                        <i class="fas fa-info-circle me-1"></i>
                        Les entrées avec date 2025-04-01 sont considérées comme anciennes (valeur par défaut historique).
                    </small>
                </div>
            </div>
            
            <div class="alert alert-warning mt-3">
                <strong>Recommandations :</strong>
                <ul class="mb-0">
                    <li>Toujours tester avant de supprimer réellement</li>
                    <li>Vérifier les logs après chaque nettoyage</li>
                    <li>Sauvegarder la base de données régulièrement</li>
                </ul>
            </div>
        </div>

        <!-- Logs récents -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-file-alt me-2"></i>Logs de Nettoyage Récents</h5>
                    </div>
                    <div class="card-body">
                        <?php
                        $log_files = glob('../../01_logs/logs_*.txt');
                        rsort($log_files); // Trier par date décroissante
                        
                        $cleanup_logs = [];
                        $max_logs = 20;
                        $count = 0;
                        
                        foreach ($log_files as $log_file) {
                            if ($count >= $max_logs) break;
                            
                            $content = file($log_file, FILE_IGNORE_NEW_LINES);
                            foreach (array_reverse($content) as $line) {
                                if (strpos($line, 'CLEANUP:') !== false && $count < $max_logs) {
                                    $cleanup_logs[] = $line;
                                    $count++;
                                }
                            }
                        }
                        ?>
                        
                        <?php if (empty($cleanup_logs)): ?>
                            <p class="text-muted">Aucun log de nettoyage trouvé.</p>
                        <?php else: ?>
                            <div style="max-height: 300px; overflow-y: auto; background: #f8f9fa; padding: 15px; border-radius: 5px; font-family: 'Courier New', monospace; font-size: 13px;">
                                <?php foreach (array_slice($cleanup_logs, 0, 10) as $log_entry): ?>
                                    <div class="mb-1"><?php echo htmlspecialchars($log_entry); ?></div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark mt-5">
        <p>&copy; 2025 JCMI - Version 2.0 | Tous droits réservés.</p>
        <p>Support : informatiques@groupemichel.com | Tél : 1350 | <a style="color:#EF7837;" href="http://sos-informatique.gm.local" target="_blank">SOS INFORMATIQUE</a></p>
    </footer>

</body>
</html>