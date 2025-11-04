<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Diagnostic Session</title>
    <meta charset="UTF-8">
</head>
<body>
    <h2>Informations de Session</h2>
    
    <?php if (isset($_SESSION['user_role'])): ?>
        <p><strong>Rôle actuel :</strong> <?php echo htmlspecialchars($_SESSION['user_role']); ?></p>
        <p><strong>Prénom :</strong> <?php echo htmlspecialchars($_SESSION['user_firstname'] ?? 'Non défini'); ?></p>
        <p><strong>Nom :</strong> <?php echo htmlspecialchars($_SESSION['user_lastname'] ?? 'Non défini'); ?></p>
        
        <h3>Toutes les variables de session :</h3>
        <pre><?php print_r($_SESSION); ?></pre>
        
    <?php else: ?>
        <p style="color: red;">Aucune session active détectée</p>
        <p><a href="../login.php">Se connecter</a></p>
    <?php endif; ?>
    
    <p><a href="admin_cleanup.php">Retour au nettoyage</a></p>
</body>
</html>