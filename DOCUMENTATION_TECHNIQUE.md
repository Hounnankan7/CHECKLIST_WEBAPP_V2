# DOCUMENTATION TECHNIQUE - APPLICATION WEB CHECKLIST V2

## 📋 INFORMATIONS GÉNÉRALES

### Présentation du Projet
**Nom** : Application Web de Gestion des Checklists d'Embauche  
**Version** : 2.0  
**Société** : JCMI 
**Développeur principal** : Donald HOUNNANKAN  
**Date de création** : 2025  
**Environnement** : MAMP (Apache, MySQL, PHP), Bootstrap, Javascript

### Objectif de l'Application
Cette application web permet de gérer les checklists d'embauche des nouveaux employés, avec un système de validation multi-services et de notifications automatiques. Elle facilite le suivi des tâches d'intégration pour les services RH, Informatique, Achat et Bâtiment.

---

## 🏗️ ARCHITECTURE DU PROJET

### Structure des Dossiers
```
CHECKLIST_WEBAPP_V2/
├── index.php                          # Page d'accueil et connexion
├── 00_dataBase_Files/                 # Scripts SQL
│   └── checklist_dbv2.sql            # Structure de base de données
├── 01_assets/                         # Ressources visuelles
│   ├── fa-*.png                       # Icônes FontAwesome
│   ├── LOGO_BLANC.png                 # Logo de l'entreprise
│   └── faviconV2.png                  # Favicon
├── 01_configFiles/                    # Fichiers de configuration
│   ├── databaseLink.txt               # Configuration BDD
│   ├── passwords.txt                  # Mots de passe (à protéger)
│   ├── phpmailer_infoNotif.txt        # Config mail notifications
│   └── phpmailer_rhNotif.txt          # Config mail RH
├── 01_includes/                       # Bibliothèques et includes
│   ├── dbconnect.php                  # Connexion base de données
│   ├── PHPMailer/                     # Bibliothèque PHPMailer
│   └── TCPDF-main/                    # Bibliothèque PDF
├── 01_logs/                           # Journaux d'activité
│   └── logs_YYYYMMDD.txt             # Logs quotidiens
└── 01_scripts/                        # Scripts applicatifs
    ├── deconnexion.php                # Script de déconnexion
    ├── login.php                      # Script d'authentification
    ├── modif_suivi.php                # Script de modification/suivi
    ├── oneProfil_tasks.php            # Vue des tâches par profil
    ├── ACHAT_PAGES/                   # Interface service Achat
    ├── ADMIN_PAGES/                   # Interface administrateur
    ├── BATIMENT_PAGES/                # Interface service Bâtiment
    ├── CDS_PAGES/                     # Interface Chef de Service
    └── RH_PAGES/                      # Interface service RH
```

---

## 🗄️ BASE DE DONNÉES

### Configuration
- **Serveur** : 192.168.4.13:3307
- **Nom de la base** : checklist_dbv2
- **Utilisateur** : root
- **Mot de passe** : root
- **Encodage** : utf8

### Tables Principales

#### 1. `users_table` - Gestion des utilisateurs
```sql
CREATE TABLE `users_table` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_firstname` varchar(50) NOT NULL,
  `user_lastname` varchar(50) NOT NULL,
  `user_fullname` varchar(150) NOT NULL,
  `user_role` varchar(50) NOT NULL,
  `user_email` varchar(40) NOT NULL,
  `user_pswd` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
);
```

#### 2. `new_employee_table` - Informations employés
```sql
CREATE TABLE `new_employee_table` (
  `id_employe` int(11) NOT NULL AUTO_INCREMENT,
  `user_lastname` varchar(50) NOT NULL,
  `user_firstname` varchar(20) NOT NULL,
  `nom_jeune_fille` varchar(50) NOT NULL,
  `site_embauche` varchar(100) NOT NULL,
  `intitule_poste` varchar(150) NOT NULL,
  `services` varchar(100) NOT NULL,
  `date_embauche` date NOT NULL,
  `date_naissance` date NOT NULL,
  -- ... autres champs spécifiques aux besoins
  PRIMARY KEY (`id_employe`)
);
```

#### 3. `tasks_table` - Suivi des tâches
```sql
CREATE TABLE `tasks_table` (
  `id_task` int(11) NOT NULL AUTO_INCREMENT,
  `id_employe` int(11) DEFAULT NULL,
  `creation_mail` varchar(20) NOT NULL,
  `redirection_mail` varchar(20) NOT NULL,
  -- ... champs de statut pour chaque tâche
  `admin_progression` double NOT NULL,
  `rh_progression` double NOT NULL,
  `achat_progression` double NOT NULL DEFAULT '0',
  `batiment_progression` double NOT NULL DEFAULT '0',
  `progression` double NOT NULL,
  `attribue_a` varchar(100) NOT NULL,
  `etat_rh` varchar(100) NOT NULL,
  PRIMARY KEY (`id_task`)
);
```

#### 4. `sorties_table` - Gestion des sorties d'employés
```sql
CREATE TABLE `sorties_table` (
  `id_sortie` int(11) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `date_sortie` date DEFAULT NULL,
  -- ... champs de récupération matériel
  PRIMARY KEY (`id_sortie`)
);
```

#### Tables de Support
- `fiche_poste_table` : Catégories et intitulés de postes
- `services_table` : Liste des services
- `site_embauche_table` : Sites d'embauche disponibles
- `commentaires` : Système de commentaires

---

## 🔐 GESTION DES RÔLES ET ACCÈS

### Rôles Utilisateur
1. **RH** : Création et validation des checklists
2. **Informaticien** : Administration complète du système
3. **Achat** : Gestion des besoins matériels
4. **Batiment** : Attribution des clés et badges
5. **CDS** (Chef de Service) : Création de checklists

### Système d'Authentification
- Connexion via `index.php`
- Session PHP avec variables :
  - `$_SESSION['user_id']`
  - `$_SESSION['user_firstname']`
  - `$_SESSION['user_lastname']`
  - `$_SESSION['user_role']`

### Pages par Rôle
```php
// Redirection selon le rôle
switch($_SESSION['user_role']) {
    case 'RH': 
        header('location: 01_scripts/RH_PAGES/rh_homepage.php');
        break;
    case 'Informaticien':
        header('location: 01_scripts/ADMIN_PAGES/admin_homepage.php');
        break;
    case 'Achat':
        header('location: 01_scripts/ACHAT_PAGES/achat_homepage.php');
        break;
    case 'Batiment':
        header('location: 01_scripts/BATIMENT_PAGES/batiment_homepage.php');
        break;
}
```

---

## 📧 SYSTÈME DE NOTIFICATIONS EMAIL

### Configuration PHPMailer
- **Serveur SMTP** : smtp.office365.com
- **Port** : 587
- **Sécurité** : TLS
- **Compte émetteur** : serveur@groupemichel.com

### Types de Notifications

#### 1. Notifications RH (`phpmailer_rhNotif.txt`)
```txt
host='smtp.office365.com'
username='serveur@groupemichel.com'
password='Serv00'
receiveraddress='informatique@groupemichel.com'
```

#### 2. Notifications Informatique (`phpmailer_infoNotif.txt`)
```txt
receiveraddress='donald.hounnankan@groupemichel.com'
receiveraddress2='laurence.lerat@groupemichel.com'
receiveraddress3='catherine.grandpre@groupemichel.com'
```

### Déclencheurs d'Email
1. **Service Bâtiment** : Quand `attribution_cles = 'A faire'` et première validation RH
2. **Service Achat** : Selon critères multiples (matériel, véhicule, etc.)
3. **Prévention envois multiples** : Vérification `attribue_a != ""`

---

## 🔄 FLUX DE TRAVAIL PRINCIPAL

### 1. Création d'une Checklist
```
Utilisateur (RH/CDS/Achat/Batiment) → Formulaire checklist → envoi_checklist.php
→ Insertion en base → Notifications automatiques → Redirection
```

### 2. Suivi et Modification
```
Administrateur/RH → Page de suivi → modif_suivi.php → Mise à jour statuts
→ Calcul progression → Notifications conditionnelles
```

### 3. Workflow de Validation
```
1. Création par service métier
2. Validation RH (première étape obligatoire)
3. Attribution aux services concernés
4. Suivi et validation par chaque service
5. Clôture automatique à 100%
```

---

## 🛠️ SCRIPTS PRINCIPAUX

### Fichiers Core

#### `index.php` - Page d'accueil et authentification
- Formulaire de connexion
- Vérification des credentials
- Redirection selon le rôle

#### `01_includes/dbconnect.php` - Connexion base de données
```php
$database_connect = mysqli_connect('localhost:3307', 'root', 'root', 'checklist_dbv2')
    or die('Probleme de connection à la base de donnée !!!');
```

#### `modif_suivi.php` - Script central de traitement
- Mise à jour des statuts de tâches
- Calcul de progression
- Gestion des notifications email
- Logging des activités

### Scripts par Service

#### Service RH (`RH_PAGES/`)
- `rh_homepage.php` : Dashboard principal RH
- `rh_add_checklist.php` : Création de checklist
- `envoi_checklist.php` : Traitement formulaire

#### Service Achat (`ACHAT_PAGES/`)
- `achat_homepage.php` : Suivi des demandes matérielles
- `achat_add_checklist.php` : Création demandes achat
- `achat_closedentry.php` : Checklists terminées

#### Service Bâtiment (`BATIMENT_PAGES/`)
- `batiment_homepage.php` : Attribution clés/badges
- `batiment_add_checklist.php` : Nouvelles attributions

#### Administration (`ADMIN_PAGES/`)
- `admin_homepage.php` : Vue d'ensemble système
- `admin_systemeadmin.php` : Configuration système
- `admin_outmanager.php` : Gestion des sorties

---

## 🎨 INTERFACE UTILISATEUR

### Framework CSS
- **Bootstrap 5.3.0** : Structure responsive
- **FontAwesome** : Icônes interface
- **Couleurs corporate** : Orange (#EF7837) et noir

### Structure HTML Type
```html
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SUIVI CHECK LIST - [Service]</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <link rel="shortcut icon" type="image/png" href="../../01_assets/faviconV2.png"/>
</head>
```

### Navigation Responsive
- Menu horizontal avec icônes FontAwesome
- Liens contextuels selon le rôle utilisateur
- Déconnexion systématique

### Formulaires Dynamiques
- Validation côté client JavaScript
- Activation/désactivation de champs conditionnelle
- Autocomplétions depuis base de données

---

## 🔧 FONCTIONNALITÉS TECHNIQUES

### Sélection Dynamique de Postes
```javascript
// Chargement dynamique des postes selon la catégorie
document.getElementById("categorieSelect").addEventListener("change", function() {
    var selectedCategory = this.value;
    var postes = data[selectedCategory] || [];
    // Mise à jour du select des postes
});
```

### Gestion Conditionnelle des Champs
```javascript
// Activation/désactivation selon le choix PC
if (demandePC === "Non") {
    nomAncienPC.disabled = false;  // Activer champ nom PC existant
    demandePCOui.disabled = true;  // Désactiver champ nouveau PC
}
```

### Système de Logging
```php
$log_dir = __DIR__ . '/../../01_logs/';
$log_file = $log_dir . 'logs_' . date('Ymd') . '.txt';
$log_entry = $user . ':' . $action . ':' . $status . ':' . date('Y-m-d H:i:s') . "\n";
file_put_contents($log_file, $log_entry, FILE_APPEND | LOCK_EX);
```

### Gestion d'Erreurs
- Try-catch pour operations critiques
- Messages d'erreur utilisateur via URL params
- Logging automatique des erreurs

---

## 📊 MONITORING ET LOGS

### Types de Logs
1. **Connexions** : LOGIN/LOGOUT avec timestamps
2. **Actions** : SEND_CHECKLIST, DELETE_CHECKLIST, etc.
3. **Erreurs** : Échecs de mail, erreurs SQL

### Format des Logs
```
[UTILISATEUR]:[ACTION]:[STATUS]:[TIMESTAMP]
```

### Exemple
```
Donald HOUNNANKAN:LOGIN:success:2025-08-13 06:47:36
RIVIERE Fabien:SEND_CHECKLIST:success:2025-08-13 08:11:53
```

---

## 🚀 DÉPLOIEMENT ET MAINTENANCE

### Prérequis Système
- **Serveur Web** : Apache 2.4+
- **PHP** : 7.4+ avec extensions mysqli, mbstring
- **MySQL** : 5.7+ ou MariaDB équivalent
- **SMTP** : Accès Office365 ou serveur mail SMTP

### Installation MAMP (Développement/Test)

#### Téléchargement et Installation MAMP
1. **Télécharger MAMP** depuis https://www.mamp.info/
2. **Installer MAMP** dans le répertoire par défaut :
   - Windows : `C:\MAMP\`
   - macOS : `/Applications/MAMP/`
3. **Démarrer MAMP** et vérifier le fonctionnement

#### Configuration MAMP pour l'Application

##### Configuration Apache
**Fichier** : `C:\MAMP\conf\apache\httpd.conf`

```apache
# Port Apache (par défaut MAMP : 8888)
Listen 8888

# DocumentRoot pour l'application
DocumentRoot "C:/MAMP/htdocs"

# Module PHP activé
LoadModule php7_module modules/libphp7.so

# Support .htaccess
<Directory "C:/MAMP/htdocs">
    AllowOverride All
    Require all granted
</Directory>
```

##### Configuration PHP
**Fichier** : `C:\MAMP\bin\php\php7.4.x\conf\php.ini`

```ini
# Extensions requises
extension=mysqli
extension=mbstring
extension=openssl
extension=curl

# Paramètres mail
SMTP = smtp.office365.com
smtp_port = 587

# Limites upload
upload_max_filesize = 50M
post_max_size = 50M
max_execution_time = 300

# Sessions
session.gc_maxlifetime = 3600

# Affichage erreurs (développement uniquement)
display_errors = On
error_reporting = E_ALL
```

##### Configuration MySQL
**Port** : 3307 (par défaut MAMP pour éviter conflits)

**Accès** :
- **Host** : localhost:3307
- **Username** : root
- **Password** : root (par défaut MAMP)

**Configuration my.cnf** : `C:\MAMP\conf\mysql\my.cnf`
```ini
[mysqld]
port = 3307
max_allowed_packet = 100M
innodb_buffer_pool_size = 256M

# Charset UTF-8
character-set-server = utf8
collation-server = utf8_general_ci
```

#### Déploiement de l'Application dans MAMP

##### Étape 1 : Copie des Fichiers
```batch
# Créer le dossier projet
mkdir C:\MAMP\htdocs\00_M_A_J\CHECKLIST_WEBAPP_V2

# Copier tous les fichiers du projet dans ce dossier
# Respecter l'arborescence complète
```

##### Étape 2 : Configuration Base de Données
1. **Démarrer MAMP** (Apache + MySQL)
2. **Accéder phpMyAdmin** : http://localhost:8888/phpMyAdmin/
3. **Créer la base** : 
   - Nom : `checklist_dbv2`
   - Charset : `utf8_general_ci`
4. **Importer le SQL** : 
   - Onglet "Importer"
   - Fichier : `00_dataBase_Files/checklist_dbv2.sql`

##### Étape 3 : Configuration Connexion BDD
**Fichier** : `01_includes/dbconnect.php`
```php
<?php
    // Configuration MAMP
    $database_connect = mysqli_connect('localhost:3307', 'root', 'root', 'checklist_dbv2')
                        or die('Probleme de connection à la base de donnée !!!');
?>
```

##### Étape 4 : Configuration URLs
**Fichier** : `01_configFiles/databaseLink.txt`
```txt
db_link='http://localhost:8888/phpMyAdmin/db_structure.php?server=1&db=checklist_dbv2'
```

##### Étape 5 : Permissions Logs
```batch
# Windows - Donner droits écriture au dossier logs
icacls "C:\MAMP\htdocs\00_M_A_J\CHECKLIST_WEBAPP_V2\01_logs" /grant Everyone:F
```

#### Vérification Installation MAMP
1. **Démarrer MAMP** (boutons Start Apache + MySQL)
2. **Tester Apache** : http://localhost:8888/
3. **Tester MySQL** : Connexion phpMyAdmin
4. **Tester Application** : http://localhost:8888/00_M_A_J/CHECKLIST_WEBAPP_V2/
5. **Vérifier logs** : Création automatique dans `01_logs/`

#### Dépannage MAMP

##### Problèmes Fréquents
**Port 8888 occupé** :
```
# Changer le port Apache dans MAMP
Préférences → Ports → Apache Port : 8889
```

**MySQL ne démarre pas** :
```
# Vérifier processus MySQL existants
# Arrêter services MySQL Windows si conflit
# Changer port MySQL : 3307 → 3308
```

**PHP ne fonctionne pas** :
```
# Vérifier module PHP chargé dans httpd.conf
# Redémarrer Apache après modification
```

**Permissions fichiers** :
```
# Windows : Propriétés → Sécurité → Modifier permissions
# Donner contrôle total à l'utilisateur MAMP
```

#### MAMP PRO (Version Avancée)
Pour un environnement professionnel, MAMP PRO offre :
- **Virtual Hosts** : Plusieurs domaines locaux
- **SSL** : Certificats HTTPS locaux
- **Monitoring** : Surveillance en temps réel
- **Backup** : Sauvegardes automatiques

### Installation Production (Serveur Linux)

#### Prérequis Linux
```bash
# Ubuntu/Debian
sudo apt update
sudo apt install apache2 php mysql-server php-mysqli php-mbstring

# CentOS/RHEL
sudo yum install httpd php mysql-server php-mysql php-mbstring
```

#### Configuration Apache Production
```apache
<VirtualHost *:80>
    DocumentRoot /var/www/html/checklist_webapp_v2
    ServerName checklist.groupemichel.com
    
    <Directory /var/www/html/checklist_webapp_v2>
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog ${APACHE_LOG_DIR}/checklist_error.log
    CustomLog ${APACHE_LOG_DIR}/checklist_access.log combined
</VirtualHost>
```

#### Sécurisation Production
```bash
# Permissions restrictives
sudo chown -R www-data:www-data /var/www/html/checklist_webapp_v2
sudo chmod -R 755 /var/www/html/checklist_webapp_v2
sudo chmod -R 775 /var/www/html/checklist_webapp_v2/01_logs

# Protection fichiers config
sudo chmod 600 /var/www/html/checklist_webapp_v2/01_configFiles/*

# SSL/HTTPS (recommandé)
sudo apt install certbot python3-certbot-apache
sudo certbot --apache -d checklist.groupemichel.com
```

### Installation Standard
1. Copier les fichiers dans le répertoire web
2. Importer `00_dataBase_Files/checklist_dbv2.sql`
3. Configurer `01_includes/dbconnect.php`
4. Ajuster les fichiers de configuration dans `01_configFiles/`
5. Vérifier les permissions sur `01_logs/`

### Configuration SMTP
1. Modifier `01_configFiles/phpmailer_infoNotif.txt`
2. Modifier `01_configFiles/phpmailer_rhNotif.txt`
3. Tester l'envoi d'emails

### Maintenance Régulière
- Vérification logs quotidiens (`01_logs/`)
- Sauvegarde base de données hebdomadaire
- Mise à jour des listes : services, sites, postes
- Contrôle des comptes utilisateurs

### Sécurité
- Changer les mots de passe par défaut
- Restreindre l'accès aux fichiers de configuration
- Mettre en place HTTPS en production
- Audit régulier des logs de connexion

---

## 📞 SUPPORT ET CONTACTS

### Support Technique
- **Email** : informatiques@groupemichel.com
- **Téléphone** : 1350
- **SOS Informatique** : http://sos-informatique.gm.local

### Développement
- **Développeur principal** : Donald HOUNNANKAN
- **Email** : donald.hounnankan@groupemichel.com

### Administration Base de Données
- **URL** : Configurée dans `databaseLink.txt`
- **Accès** : Réservé aux administrateurs système

---

## 📝 NOTES DE VERSION

### Version 2.0 (2025)
- Système de notifications automatiques
- Interface modernisée Bootstrap 5
- Gestion multi-services améliorée
- Système de logging complet
- Validation formulaires dynamique
- Gestion des sorties d'employés

### Fonctionnalités Récentes
- Précision d'intitulé de poste personnalisable
- Notification conditionnelle service Bâtiment
- Notification multi-critères service Achat
- Prévention des emails en boucle
- Amélioration UX formulaires PC

---

## 🔮 ÉVOLUTIONS FUTURES POSSIBLES

### Améliorations Techniques
- Migration vers PHP 8+
- Implémentation d'une API REST
- Interface mobile dédiée
- Système de notifications push
- Intégration Active Directory

### Fonctionnalités Métier
- Workflows personnalisables
---

## 🧹 SYSTÈME DE NETTOYAGE AUTOMATIQUE

### Présentation
Le système de nettoyage automatique permet de supprimer les entrées terminées anciennes pour optimiser l'espace disque et maintenir les performances de l'application.

### Critères de Suppression
- **Progression** : 100% (entrées terminées uniquement)
- **Ancienneté** : Plus d'un an depuis la date de clôture
- **Date de clôture valide** : Différente de '1999-03-09' (valeur par défaut)

### Données Supprimées
1. **Tables de base de données** :
   - `tasks_table` : Tâches associées
   - `new_employee_table` : Informations employé
   - `commentaires` : Commentaires liés
   
2. **Fichiers** :
   - PDF des checklists (`00_PDF_CHECKLIST/`)

### Modes d'Exécution

#### 1. Mode Automatique (CRON)
- **Fréquence** : Mensuelle (1er de chaque mois à 2h)
- **Fichier** : `cleanup_old_entries.php?mode=cron`
- **Script batch** : `cleanup_cron.bat` (Windows)
- **Configuration** : Voir `CRON_SETUP.md`

#### 2. Mode Manuel (Interface Admin)
- **URL** : `admin_cleanup.php`
- **Droits requis** : Administrateur
- **Fonctionnalités** :
  - Mode test (simulation)
  - Exécution immédiate
  - Statistiques en temps réel

#### 3. Mode Test
- **URL** : `cleanup_old_entries.php?mode=manual&test=1`
- **But** : Simulation sans suppression
- **Utilisation** : Validation avant nettoyage réel

### Logging et Surveillance
- **Logs** : Intégrés dans `01_logs/logs_YYYYMMDD.txt`
- **Contenu** :
  - Heure d'exécution
  - Nombre d'entrées trouvées/supprimées
  - Détails des erreurs
  - Temps d'exécution
  
### Sécurité
- **Transactions SQL** : Garantit la cohérence
- **Vérifications d'accès** : Droits administrateur
- **Mode test** : Validation préalable
- **Logs détaillés** : Audit complet

### Configuration CRON Windows (MAMP)

#### Planificateur de Tâches
1. Ouvrir `taskschd.msc`
2. Créer une nouvelle tâche :
   - **Nom** : "Nettoyage Checklist WebApp"
   - **Déclencheur** : Mensuel, 1er du mois, 02:00
   - **Action** : `C:\MAMP\bin\php\php8.2.0\php.exe`
   - **Arguments** : `C:\MAMP\htdocs\00_M_A_J\CHECKLIST_WEBAPP_V2\01_scripts\ADMIN_PAGES\cleanup_old_entries.php mode=cron`

#### Script Batch
```batch
"C:\MAMP\bin\php\php8.2.0\php.exe" cleanup_old_entries.php mode=cron
```

### Interface Administration
- **Page** : `admin_cleanup.php`
- **Fonctionnalités** :
  - Statistiques en temps réel
  - Aperçu des entrées anciennes
  - Boutons d'action (test/nettoyage)
  - Historique des logs
  - Zone de sécurité avec avertissements

### Surveillance Recommandée
- Vérifier les logs mensuellement
- Contrôler l'espace disque libéré
- Valider la suppression des PDF
- Surveiller les erreurs éventuelles

---

## 🔄 ÉVOLUTIONS FUTURES RECOMMANDÉES

- Archivage des données avant suppression
- Interface de restauration
- Rapports et statistiques avancées
- Gestion documentaire intégrée
- Système de signatures électroniques
- Module de formation en ligne

---

## ⚠️ POINTS D'ATTENTION

### Sécurité
- Les mots de passe sont stockés en clair (à chiffrer)
- Pas de protection CSRF sur les formulaires
- Validation côté serveur à renforcer

### Performance
- Pas de système de cache implémenté
- Requêtes SQL non optimisées pour gros volumes
- Images non compressées

### Maintenance
- Logs non archivés automatiquement
- Système de nettoyage automatique implémenté ✅
- Configuration en fichiers texte (vulnérable)

---

*Document généré le 8 octobre 2025 - Version 1.1*
*Pour toute question technique, contacter l'équipe informatique JCMI*