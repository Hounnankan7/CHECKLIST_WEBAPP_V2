@echo off
REM Script Batch pour automatiser le nettoyage des entrées anciennes
REM À utiliser avec le Planificateur de tâches Windows

echo ====================================
echo Nettoyage automatique des entrees
echo Date: %date% %time%
echo ====================================

REM Changement vers le répertoire du script
cd /d "C:\MAMP\htdocs\00_M_A_J\CHECKLIST_WEBAPP_V2\01_scripts\ADMIN_PAGES"

REM Exécution du script PHP de nettoyage
"C:\MAMP\bin\php\php8.2.0\php.exe" cleanup_old_entries.php mode=cron

REM Vérification du code de retour
if %errorlevel% equ 0 (
    echo Nettoyage execute avec succes
) else (
    echo Erreur lors du nettoyage - Code: %errorlevel%
)

echo ====================================
echo Fin du nettoyage: %date% %time%
echo ====================================

REM Optionnel : garder une trace dans un log Windows
echo %date% %time% - Execution automatique du nettoyage >> "..\..\01_logs\cron_execution.log"