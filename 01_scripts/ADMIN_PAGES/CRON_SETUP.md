# Configuration CRON pour le nettoyage automatique

## Instructions pour Windows avec MAMP

### Option 1 : Planificateur de tâches Windows

1. **Ouvrir le Planificateur de tâches Windows**
   - Appuyer sur `Win + R`, taper `taskschd.msc`

2. **Créer une nouvelle tâche**
   - Clic droit sur "Bibliothèque du Planificateur de tâches"
   - Sélectionner "Créer une tâche..."

3. **Configuration générale**
   - Nom : `Nettoyage Checklist WebApp`
   - Description : `Suppression automatique des entrées terminées de plus d'un an`
   - Cocher "Exécuter même si l'utilisateur n'est pas connecté"
   - Cocher "Exécuter avec les autorisations maximales"

4. **Configuration du déclencheur**
   - Onglet "Déclencheurs" → "Nouveau..."
   - Commencer la tâche : "Selon une planification"
   - Paramètres : "Mensuel"
   - Mois : "Tous les mois"
   - Jours : "1" (premier jour du mois)
   - Heure : "02:00" (2h du matin)

5. **Configuration de l'action**
   - Onglet "Actions" → "Nouveau..."
   - Action : "Démarrer un programme"
   - Programme/script : `C:\MAMP\bin\php\php8.2.0\php.exe`
   - Ajouter des arguments : `C:\MAMP\htdocs\00_M_A_J\CHECKLIST_WEBAPP_V2\01_scripts\ADMIN_PAGES\cleanup_old_entries.php?mode=cron`

6. **Configuration des conditions**
   - Onglet "Conditions"
   - Décocher "Démarrer la tâche seulement si l'ordinateur est alimenté sur secteur"

### Option 2 : Script Batch automatisé

Créer un fichier `cleanup_cron.bat` avec le contenu suivant :

```batch
@echo off
cd /d C:\MAMP\htdocs\00_M_A_J\CHECKLIST_WEBAPP_V2\01_scripts\ADMIN_PAGES
C:\MAMP\bin\php\php8.2.0\php.exe cleanup_old_entries.php mode=cron
```

Puis programmer ce fichier .bat dans le Planificateur de tâches.

### Option 3 : Pour environnement Linux/Unix (si migration)

Ajouter dans le crontab :
```bash
# Nettoyage mensuel des entrées anciennes (1er de chaque mois à 2h)
0 2 1 * * /usr/bin/php /path/to/cleanup_old_entries.php mode=cron
```

## Vérification et monitoring

### Vérification des logs
Les logs de nettoyage sont automatiquement écrits dans :
```
01_logs/logs_AAAAMMJJ.txt
```

### Test manuel avant mise en production
1. Tester le mode test : `cleanup_old_entries.php?mode=manual&test=1`
2. Vérifier les logs
3. Activer la tâche automatique

### Surveillance recommandée
- Vérifier les logs mensuellement
- Surveiller l'espace disque libéré
- Contrôler que les PDF sont bien supprimés

## Sécurité

- Le script vérifie les autorisations administrateur
- Utilisation de transactions pour assurer la cohérence
- Logs détaillés pour audit
- Mode test pour validation préalable