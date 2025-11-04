<?php

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    date_default_timezone_set('Europe/Paris');

    session_start();

    include_once '../../01_includes/dbconnect.php';


    require('../../01_includes/TCPDF-main/tcpdf.php');

    //require 'vendor/autoload.php';
    require '../../01_includes/PHPMailer/src/Exception.php';
    require '../../01_includes/PHPMailer/src/PHPMailer.php';
    require '../../01_includes/PHPMailer/src/SMTP.php';

    //Responsables
    $tasks_resp_achat = mysqli_query($database_connect, "SELECT * FROM users_table WHERE user_role='Achat'");
    $row_achat = mysqli_fetch_assoc($tasks_resp_achat);
    $tasks_resp_batiment = mysqli_query($database_connect, "SELECT * FROM users_table WHERE user_role='Batiment'");
    $row_batiment = mysqli_fetch_assoc($tasks_resp_batiment);

    $respo_achat = $row_achat['user_firstname']." ".$row_achat['user_lastname'];
    $respo_batiment = $row_batiment['user_firstname']." ".$row_batiment['user_lastname'];

    //Recuperation infos du formulaire

        $createdBy = mysqli_real_escape_string($database_connect, $_GET['createdby']);
        $employe_nom = mysqli_real_escape_string($database_connect,$_POST['nom']);
        $employe_nomFille = mysqli_real_escape_string($database_connect,$_POST['nom_fille']);
        $employe_prenom = mysqli_real_escape_string($database_connect,$_POST['prenom']);
        //$employe_site = $_POST['site'];
        $employe_site = mysqli_real_escape_string($database_connect, $_POST['site']);
        $employe_service = mysqli_real_escape_string($database_connect, $_POST['service']);

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $employe_poste = mysqli_real_escape_string($database_connect, $_POST['posteSelect']); // Récupération de la valeur de poste
            // ... reste du traitement ...
            $employe_poste = $employe_poste." ".$_POST['precisionPoste'];
        }

        $employe_birth_date = date('Y-m-d', strtotime($_POST['birth_date']));
        $employe_arrive_date = date('Y-m-d', strtotime($_POST['arrive_date']));

        $employe_creation_mail = $_POST['creation_mail'];
        $employe_remplacement = $_POST['remplacement'];
        $employe_ancien_remplacer = $_POST['ancien_remplacer'];
        $employe_activer_transfert = $_POST['activer_transfert'];
        $employe_creation_rrf = $_POST['creation_rrf'];
        $employe_creation_rlearning = $_POST['creation_rlearning'];
        $employe_creation_rcampus = $_POST['creation_rcampus'];
        $employe_creation_dcsnet = $_POST['creation_dcsnet'];
        $employe_tocken = $_POST['tocken'];
        $employe_portable_pro = $_POST['portable_pro'];
        $employe_new_line = $_POST['new_line'];
        $employe_number_new_line = $_POST['number_new_line'];
        $employe_demande_pc = $_POST['demande_pc'];
        $employe_demande_pc_oui = $_POST['demande_pc_oui'];
        $employe_nom_ancien_pc = $_POST['nom_ancien_pc'];
        $employe_demande_malette = $_POST['demande_malette'];
        $employe_demande_imprimante = $_POST['demande_imprimante'];
        $employe_demande_vehicule = $_POST['demande_vehicule'];
        $employe_demande_badge = $_POST['demande_badge'];
        $employe_demande_cles = $_POST['demande_cles'];

        $employe_demande_vetement = $_POST['demande_vetement'];
        $employe_taille_tshirt = $_POST['taille_tshirt'];
        $employe_taille_chemise = $_POST['taille_chemise'];
        $employe_taille_veste = $_POST['taille_veste'];
        $employe_taille_pantalon = $_POST['taille_pantalon'];
        $commentaire_haut_vetement = "Taille T-Shirt : ".$employe_taille_tshirt.", Taille Chemise : ".$employe_taille_chemise.", Taille Veste : ".$employe_taille_veste.", Taille Pantalon : ".$employe_taille_pantalon;

        $employe_demande_chaussure = $_POST['demande_chaussure'];
        $employe_taille_chaussure = $_POST['pointure'];
        $employe_commentaire = mysqli_real_escape_string($database_connect,$_POST['commentaire']);
    
    //
    
    $form_data = mysqli_query($database_connect,
        "INSERT INTO new_employee_table(user_lastname, user_firstname, nom_jeune_fille, site_embauche, 
        intitule_poste, services, date_embauche, date_naissance, besoin_mail, remplace_un_ancien, nom_ancien, 
        besoin_transfert_mail, besoin_rrf_dmd, besoin_r_learning, besoin_r_campus, besoin_dcs_net, besoin_tocken, 
        besoin_portable_pro, besoin_new_ligne, numéro_repris, besoin_pc, type_pc, nom_pc_recupere, besoin_malette_pc, 
        besoin_imprimante, besoin_vehicule, besoin_badge_alarme, besoin_cle_batiments, besoin_vetement, besoin_chaussure, 
        taille_haut, commentaire_vetement, taille_bas, pointure, commentaire, signature_chef_service, signature_direction) VALUES 
        ('$employe_nom','$employe_prenom','$employe_nomFille','$employe_site','$employe_poste','$employe_service','$employe_arrive_date','$employe_birth_date',
        '$employe_creation_mail','$employe_remplacement','$employe_ancien_remplacer','$employe_activer_transfert','$employe_creation_rrf','$employe_creation_rlearning',
        '$employe_creation_rcampus','$employe_creation_dcsnet','$employe_tocken','$employe_portable_pro','$employe_new_line','$employe_number_new_line','$employe_demande_pc',
        '$employe_demande_pc_oui','$employe_nom_ancien_pc','$employe_demande_malette','$employe_demande_imprimante','$employe_demande_vehicule','$employe_demande_badge',
        '$employe_demande_cles','$employe_demande_vetement','$employe_demande_chaussure',' ','$commentaire_haut_vetement',' ','$employe_taille_chaussure','$employe_commentaire','$createdBy',' ');");

    if($form_data ){

        $result = mysqli_query($database_connect, "SELECT id_employe FROM new_employee_table WHERE user_lastname='$employe_nom' AND user_firstname='$employe_prenom'");
        $row_employe = mysqli_fetch_assoc($result);
        $employ_id = $row_employe['id_employe'];

        //Prise d'info sur les taches à faire ou non en vue d'ajout à la BDD et calcul de la progression par défaut
            $aFaire=array("","","","","","","","","","","","","","","","");
            $pasBesoin_rh=0;
            $pasBesoin_admin=0;
            $pasBesoin_achat=0;
            $pasBesoin_batiment=0;

            if($employe_creation_mail == "Oui"){
                $aFaire[0]="A faire";
            }elseif($employe_creation_mail == "Non"){
                $aFaire[0]="Pas besoin";
                $pasBesoin_admin = $pasBesoin_admin + 1;
            }

            $aFaire[1]="Désactiver";

            /*if($employe_activer_transfert == "Oui"){
                $aFaire[1]="A faire";
            }elseif($employe_activer_transfert == "Non"){
                $aFaire[1]="Pas besoin";
                $pasBesoin_admin = $pasBesoin_admin + 1;
            }*/

            if($employe_creation_rrf == "Oui"){
                $aFaire[2]="A faire";
            }elseif($employe_creation_rrf == "Non"){
                $aFaire[2]="Pas besoin";
                $pasBesoin_rh = $pasBesoin_rh + 1;
            }

            if($employe_creation_rlearning == "Oui"){
                $aFaire[3]="A faire";
            }elseif($employe_creation_rlearning == "Non"){
                $aFaire[3]="Pas besoin";
                $pasBesoin_rh = $pasBesoin_rh + 1;
            }

            if($employe_creation_rcampus == "Oui"){
                $aFaire[4]="A faire";
            }elseif($employe_creation_rcampus == "Non"){
                $aFaire[4]="Pas besoin";
                $pasBesoin_rh = $pasBesoin_rh + 1;
            }

            if($employe_creation_dcsnet == "Oui"){
                $aFaire[5]="A faire";
            }elseif($employe_creation_dcsnet == "Non"){
                $aFaire[5]="Pas besoin";
                $pasBesoin_admin = $pasBesoin_admin + 1;
            }

            if($employe_tocken == "Oui"){
                $aFaire[6]="A faire";
            }elseif($employe_tocken == "Non"){
                $aFaire[6]="Pas besoin";
                $pasBesoin_admin = $pasBesoin_admin + 1;
            }

            if($employe_portable_pro == "Oui"){
                $aFaire[7]="A faire";
            }elseif($employe_portable_pro == "Non"){
                $aFaire[7]="Pas besoin";
                $pasBesoin_achat = $pasBesoin_achat + 1;
            }

            if($employe_demande_pc == "Oui"){
                $aFaire[8]="A faire";
            }elseif($employe_demande_pc == "Non"){
                $aFaire[8]="Pas besoin";
                $pasBesoin_admin = $pasBesoin_admin + 1;
            }

            if($employe_demande_malette == "Oui"){
                $aFaire[9]="A faire";
            }elseif($employe_demande_malette == "Non"){
                $aFaire[9]="Pas besoin";
                $pasBesoin_admin = $pasBesoin_admin + 1;
            }

            if($employe_demande_imprimante == "Oui"){
                $aFaire[10]="A faire";
            }elseif($employe_demande_imprimante == "Non"){
                $aFaire[10]="Pas besoin";
                $pasBesoin_admin = $pasBesoin_admin + 1;
            }

            if($employe_demande_vehicule == "Oui"){
                $aFaire[11]="A faire";
            }elseif($employe_demande_vehicule == "Non"){
                $aFaire[11]="Pas besoin";
                $pasBesoin_achat = $pasBesoin_achat + 1;
            }

            if($employe_demande_badge == "Oui"){
                $aFaire[12]="A faire";
            }elseif($employe_demande_badge == "Non"){
                $aFaire[12]="Pas besoin";
                $pasBesoin_achat = $pasBesoin_achat + 1;
            }

            if($employe_demande_cles == "Oui"){
                $aFaire[13]="A faire";
            }elseif($employe_demande_cles == "Non"){
                $aFaire[13]="Pas besoin";
                $pasBesoin_batiment = $pasBesoin_batiment + 1;
            }

            if($employe_demande_vetement == "Oui"){
                $aFaire[14]="A faire";
            }elseif($employe_demande_vetement == "Non"){
                $aFaire[14]="Pas besoin";
                $pasBesoin_achat = $pasBesoin_achat + 1;
            }

            if($employe_demande_chaussure == "Oui"){
                $aFaire[15]="A faire";
            }elseif($employe_demande_chaussure == "Non"){
                $aFaire[15]="Pas besoin";
                $pasBesoin_achat = $pasBesoin_achat + 1;
            }
        //

        $prog_admin = ($pasBesoin_admin*100)/15;
        $prog_rh = ($pasBesoin_rh*100)/15;
        $prog_achat = ($pasBesoin_achat*100)/15;
        $prog_batiment = ($pasBesoin_batiment*100)/15;
        //Calcul de progression par defaut avec les entree "Pas besoin" + Insertion des infos dans BDD
        $progression = (($pasBesoin_admin + $pasBesoin_rh + $pasBesoin_achat + $pasBesoin_batiment)/15)*100;

        $task_data = mysqli_query($database_connect, "INSERT INTO tasks_table (id_task, id_employe, creation_mail, redirection_mail, creation_rrf, creation_rlearning, 
            creation_rcampus, creation_dcsnet, creation_tocken, attribution_telephone, preaparation_pc, preparation_malette, preparation_imprimante, attribution_vehicule, 
            attribution_badge, attribution_cles, attribution_vetements, attribution_chaussures, admin_progression, rh_progression, achat_progression, batiment_progression, progression, attribue_a, nom_pc, 
            caracteristique, date_envoi, date_cloture, etat_rh) VALUES (NULL, '$employ_id','$aFaire[0]', '$aFaire[1]','$aFaire[2]','$aFaire[3]','$aFaire[4]','$aFaire[5]',
            '$aFaire[6]','$aFaire[7]','$aFaire[8]','$aFaire[9]','$aFaire[10]','$aFaire[11]','$aFaire[12]','$aFaire[13]','$aFaire[14]','$aFaire[15]','$prog_admin','$prog_rh', '$prog_achat', '$prog_batiment','$progression',
            '', '', '', '1999-03-09', '1999-03-09','En attente');"
        );
        //Action pris en cas de reussite de l'ajout ou non à la BDD
        if($task_data){

            $employe_birth_date2 = date('d-m-Y', strtotime($_POST['birth_date']));
            $employe_arrive_date2 = date('d-m-Y', strtotime($_POST['arrive_date']));



            // Create a new TCPDF instance
            $pdf = new TCPDF();
            $pdf->SetCreator('Ressources Humaines JCMI');
            $pdf->SetAuthor('Ressources Humaines JCMI');
            $pdf->SetTitle('CHECKLIST'.$employe_nom.'_'.$employe_prenom);
            $pdf->setPrintHeader(false);
            $pdf->setPrintFooter(false);
            $pdf->AddPage();
            $pdf->SetFont('times', '', 12);
            // create some HTML content pour checklist
            $html = '
                <h1 align="right" style="margin: 100px; font-weight: bold;font-size: large;">BESOINS EN MATERIEL ET LOGICIELS INFORMATIQUE</h1>
                <p align="right" color="red" style="font-weight: bold;font-size: small;">A COMPLETER PAR LE CHEF DE SERVICE</p>
                <table style="border-collapse: separate;border-spacing: 0; width: 100%;">
                    <tr>
                        <th bgcolor="#FFFFCC" style="border: 1px solid black;">
                            <div style="font-weight: bold;font-size: xx-small;text-decoration: underline;">RENSEIGNEMENTS SUR LA PERSONNE</div>

                            <table cellspacing="3" cellpadding="3">

                                <tr>
                                    <th style="font-size: xx-small;">Nom: '.stripslashes($employe_nom).' </th>
                                    <th style="font-size: xx-small;">Nom jeune fille: '.stripslashes($employe_nomFille).' </th>
                                </tr>
                                <tr>
                                    <th style="font-size: xx-small;">Prénom: '.stripslashes($employe_prenom).'</th>
                                </tr>
                                <tr>
                                    <th style="font-size: xx-small;">Site d\'embauche: '.stripslashes($employe_site).'</th>
                                    <th style="font-size: xx-small;">Service: '.stripslashes($employe_service).'</th>
                                </tr>
                                <tr>
                                    <th style="font-size: xx-small;">Intitulé du poste: '.stripslashes($employe_poste).'</th>
                                </tr>
                                <tr>
                                    <th style="font-size: xx-small;">Date d\'embauche: '.$employe_arrive_date2.'</th>
                                    <th style="font-size: xx-small;">Date de naissance: '.$employe_birth_date2.'</th>
                                </tr>

                            </table>
                            
                        </th>
                    </tr>
                    <tr class="middle">
                        <th bgcolor="#FFFFCC" style="border-top: 1px double black;border-bottom: 1px double black;border: 1px solid black;padding: 20px;">
                        
                            <div style="font-weight: bold;font-size: xx-small;text-decoration: underline;">ADRESSE MAIL</div>
                            <table cellspacing="3" cellpadding="3">
                                <tr>
                                    <th style="font-size: xx-small;">Création d\'une adresse mail: '.$employe_creation_mail.'</th>
                                </tr>
                                <tr>
                                    <th style="font-size: xx-small;">Remplacement d\'une personne partie: '.$employe_remplacement.'</th>
                                </tr>
                                <tr>
                                    <th style="font-size: xx-small;" color="red">+ Si oui, nom de l\'ancien salarié: '.stripslashes($employe_ancien_remplacer).'</th>
                                </tr>
                                <tr>
                                    <th style="font-size: xx-small;" color="red">+ Activer le transfert de mail: '.$employe_activer_transfert.'</th>
                                </tr>
                            </table>

                            <div style="font-weight: bold;font-size: xx-small;text-decoration: underline;">APPLICATIONS</div>
                            <table cellspacing="3" cellpadding="3">

                                <tr>
                                    <th style="font-size: xx-small;">Création RRF/DMD: '.$employe_creation_rrf.'</th>
                                    <th style="font-size: xx-small;">Création R Learning: '.$employe_creation_rlearning.'</th>
                                </tr>
                                <tr>
                                    <th style="font-size: xx-small;">Droits R Campus: '.$employe_creation_rcampus.'</th>
                                    <th style="font-size: xx-small;">Création DCS Net: '.$employe_creation_dcsnet.'</th>
                                </tr>

                            </table>

                            <div style="font-weight: bold;font-size: xx-small;text-decoration: underline;">MATERIEL</div>
                            <table cellspacing="3" cellpadding="3">

                                <tr>
                                    <th style="font-size: xx-small;">Clé tocken: '.$employe_tocken.'</th>
                                </tr>
                                <tr>
                                    <th style="font-size: xx-small;">Tél portable Pro: '.$employe_portable_pro.'</th>
                                </tr>

                            </table>
                            <table cellspacing="3" cellpadding="3">
                                <tr>
                                    <th style="font-size: xx-small;" color="red">+ Nouvelle ligne: '.$employe_new_line.'</th>
                                    <th style="font-size: xx-small;" color="red">+ Numéro récupéré: '.$employe_number_new_line.'</th>
                                </tr>
                                <tr>
                                    <th style="font-size: xx-small;">Commander nouveau PC: '.$employe_demande_pc.'</th>
                                </tr>
                                <tr>
                                    <th style="font-size: xx-small;" color="red">+ Si oui, type de PC: '.$employe_demande_pc_oui.'</th>
                                    <th style="font-size: xx-small;" color="red">+ Si non, nom PC récupérer: '.$employe_nom_ancien_pc.'</th>
                                </tr>
                                <tr>
                                    <th style="font-size: xx-small;">Malette PC: '.$employe_demande_malette.'</th>
                                    <th style="font-size: xx-small;">Imprimante: '.$employe_demande_imprimante.'</th>
                                </tr>
                                <tr>
                                    <th style="font-size: xx-small;">Véhicule+Assurance: '.$employe_demande_vehicule.'</th>
                                </tr>
                                <tr>
                                    <th style="font-size: xx-small;">Badge alarme: '.$employe_demande_badge.'</th>
                                    <th style="font-size: xx-small;">Clés batiments: '.$employe_demande_cles.'</th>
                                </tr>

                            </table>

                            <div style="font-weight: bold;font-size: xx-small;text-decoration: underline;">EQUIPEMENT</div>
                            <table cellspacing="3" cellpadding="3">

                                <tr>
                                    <th style="font-size: xx-small;">Vêtements de travail: '.$employe_demande_vetement.'</th>
                                    <th style="font-size: xx-small;">Taille T-Shirt: '.$employe_taille_tshirt.'</th>
                                    <th style="font-size: xx-small;">Taille Chemise: '.$employe_taille_chemise.'</th>
                                    <th style="font-size: xx-small;">Taille Veste: '.$employe_taille_veste.'</th>
                                    <th style="font-size: xx-small;">Taille Pantalon: '.$employe_taille_pantalon.'</th>
                                </tr>
                                <tr>
                                    <th style="font-size: xx-small;">Chaussures de travail: '.$employe_demande_chaussure.'</th>
                                    <th style="font-size: xx-small;">Pointure: '.$employe_taille_chaussure.'</th>
                                </tr>

                            </table>


                            <div style="font-weight: bold;font-size: xx-small;text-decoration: underline;">COMMENTAIRES</div>
                            <div style="font-size: x-small;width: 400px;height: 400px;padding: 15px;margin-left: 20px;margin-bottom: 20px;background-color: #74b3ce;border: 1px solid #ccc;border-radius: 8px;overflow-y: auto;">
                                '.stripslashes($employe_commentaire).'
                            </div>


                        </th>
                    </tr>
                    <tr>
                        <th bgcolor="#FFFFCC" color="red" style="border: 1px solid black;padding: 10px;font-weight: bold;font-size: xx-small;text-align: center;">VALIDATION PAR LA DIRECTION</th>
                    </tr>
                    <tr>
                        <th bgcolor="#FFFFCC" style="height: 40px;border: 1px solid black;padding: 10px;width: 50%;font-size: xx-small;">Date,Nom et Signature : <span color="red">CHEF DE SERVICE</span>
                            <br/>'.date('d/m/Y').' '.stripslashes($createdBy).'
                        </th>
                        <th bgcolor="#FFFFCC" style="height: 40px;border: 1px solid black;padding: 10px;width: 50%;font-size: xx-small;">Date,Nom et Signature : <span color="red">DIRECTION</span><br/></th>
                    </tr>
                </table>
            ';
            $pdf->writeHTML($html, true, false, true, false, '');

            if($employe_tocken=="Oui" || $employe_demande_pc=="Oui" || $employe_demande_malette=="Oui" || $employe_demande_imprimante=="Oui"){
                $description_1 = "";
                if($employe_tocken=="Oui"){
                    $description_1 = $description_1 . "- 1 clé tocken permettant l’accès à la plateforme Renault Net.<br />";
                }
                if($employe_demande_pc=="Oui"){
                    $description_1 = $description_1 . "- 1 ordinateur '.$employe_demande_pc_oui.'.<br />";
                }
                if($employe_demande_malette=="Oui"){
                    $description_1 = $description_1 . "- 1 malette pour le transport de son équipement informatique lorsqu\'il est en déplacement .<br />";
                }                
                if($employe_demande_imprimante=="Oui"){
                    $description_1 = $description_1 . "- 1 imprimante portable pour l\'impression de documents lorsqu\'il est en déplacement.<br />";
                }

                // create some HTML content pour informatique
                $pdf->AddPage();
                $html = ' 
                    <h1 align="center" style="margin: 200px; font-weight: bold;font-size: large;">JCM INVESTISSEMENTS</h1>
                    <hr>
                    <p align="center" style="font-weight: bold;font-size: small;text-decoration: underline;">ACCORD SUR L’USAGE ET LA RESTITUTION DE BIENS APPARTENANT A L’ENTREPRISE</p>
                    <p></p>
                    <p style="font-weight: bold;font-size: small;">ENTRE :</p>
                    <p style="font-weight: bold;font-size: small;">     - La Société : '.stripslashes($employe_site).' :</p>
                    <p style="font-size: small;">Représentée par Monsieur <strong>Olivier ERTEL</strong>, agissant en qualité de Directeur Général, d’une part, et<strong> '.stripslashes($employe_nom).' '.stripslashes($employe_prenom).'</strong></p>
                    <p style="font-size: small;">Ci-après dénommée le « collaborateur », d’autre part,</p>
                    <p style="font-size: small;"><strong>IL EST CONVENU ET ARRETE CE QUI SUIT :</strong></p>

                    <h1 align="center" style="margin: 200px; font-weight: bold;font-size: small;text-decoration: underline;">Article 1 : Matériel confié</h1>
                    <p style="font-size: small;">La société confie au collaborateur, pour l’exécution de ses fonctions, les équipements suivants, que le collaborateur reconnaît avoir reçu :<br />
                        '.$description_1.'
                        Ces équipements demeurent la propriété de l’entreprise et devront être restitués en cas de départ du collaborateur, sans qu’il y ait besoin d’une quelconque mise en demeure préalable.
                    </p>
                    
                    <h1 align="center" style="margin: 200px; font-weight: bold;font-size: small;text-decoration: underline;">Article 2 : Responsabilité</h1>
                    <p style="font-size: small;">Ces équipements permettent au collaborateur nommé ci-dessus d’utiliser les outils de travail au sein de l\'entreprise pendant les horaires de travail.<br/>
                        En aucun cas, ces équipements ne peuvent être transmis à une tierce personne pour quelque raison que ce soit.<br/>
                        En cas de perte ou de vol, le collaborateur s’engage à en informer Jean FORGET au 06.64.68.13.13 ou le service informatique par le biais d\'un ticket, afin de prévoir le remplacement le cas échéant.
                    </p>
                    <p style="font-size: small;">Fait, en double exemplaire, à LA ROCHELLE, le <br/>Signature du collaborateur précédée de la mention manuscrite « Lu et approuvé – Bon pour acceptation »
                    </p>
                    <p></p>
                    <table cellspacing="3" cellpadding="3">
                        <tr>
                            <th align="center" style="height: 60px;font-size: small;">'.stripslashes($employe_nom).' '.stripslashes($employe_prenom).'<br/>'.stripslashes($employe_poste).'</th>
                            <th align="center" style="height: 60px;font-size: small;">Henri ALLE MONNE<br/>Responsable Informatique</th>
                        </tr>
                    </table>
                ';
                $pdf->writeHTML($html, true, false, true, false, '');
            }

            if($employe_demande_cles=="Oui"){
                // create some HTML content pour batiment
                $pdf->AddPage();
                $html = ' 
                    <h1 align="center" style="margin: 200px; font-weight: bold;font-size: large;">JCM INVESTISSEMENTS</h1>
                    <hr>
                    <p align="center" style="font-weight: bold;font-size: small;text-decoration: underline;">ACCORD SUR L’USAGE ET LA RESTITUTION DE BIENS APPARTENANT A L’ENTREPRISE</p>
                    <p></p>
                    <p style="font-weight: bold;font-size: small;">ENTRE :</p>
                    <p style="font-weight: bold;font-size: small;">     - La Société : '.stripslashes($employe_site).' :</p>
                    <p style="font-size: small;">Représentée par Monsieur <strong>Olivier ERTEL</strong>, agissant en qualité de Directeur Général, d’une part, et<strong> '.stripslashes($employe_nom).' '.stripslashes($employe_prenom).'</strong></p>
                    <p style="font-size: small;">Ci-après dénommée le « collaborateur », d’autre part,</p>
                    <p style="font-size: small;"><strong>IL EST CONVENU ET ARRETE CE QUI SUIT :</strong></p>

                    <h1 align="center" style="margin: 200px; font-weight: bold;font-size: small;text-decoration: underline;">Article 1 : Matériel confié</h1>
                    <p style="font-size: small;">La société confie au collaborateur, pour l’exécution de ses fonctions, le matériel suivant, que le collaborateur reconnaît avoir reçu :<br />
                        - Un trousseau de clés pour faciliter l\'accès aux infrastructures de la société.<br />
                        Ce matériel demeurent la propriété de l’entreprise et devra être restitué en cas de départ du collaborateur, sans qu’il y ait besoin d’une quelconque mise en demeure préalable.
                    </p>
                    
                    <h1 align="center" style="margin: 200px; font-weight: bold;font-size: small;text-decoration: underline;">Article 2 : Responsabilité</h1>
                    <p style="font-size: small;">Ce matériel permet au collaborateur nommé ci-dessus d’avoir aacès aux infrastructures de l\'entreprise pendant les horaires de travail.<br/>
                        En aucun cas, ce matériel ne peut être transmis à une tierce personne pour quelque raison que ce soit.<br/>
                        En cas de perte ou de vol, le collaborateur s’engage à en informer Jean FORGET au 06.64.68.13.13 ou le service bâtiment par le biais d\'un ticket, afin de prévoir le remplacement le cas échéant.
                    </p>
                    <p style="font-size: small;">Fait, en double exemplaire, à LA ROCHELLE, le <br/>Signature du collaborateur précédée de la mention manuscrite « Lu et approuvé – Bon pour acceptation »
                    </p>
                    <p></p>
                    <table cellspacing="3" cellpadding="3">
                        <tr>
                            <th align="center" style="height: 60px;font-size: small;">'.stripslashes($employe_nom).' '.stripslashes($employe_prenom).'<br/>'.stripslashes($employe_poste).'</th>
                            <th align="center" style="height: 60px;font-size: small;">'.$respo_batiment.'<br/>Responsable Bâtiments</th>
                        </tr>
                    </table>
                ';
                $pdf->writeHTML($html, true, false, true, false, '');
            }

            if($employe_demande_vehicule=="Oui" || $employe_portable_pro=="Oui" || $employe_demande_badge=="Oui"){
                $description_3 = "";
                if($employe_demande_vehicule=="Oui"){
                    $description_3 = $description_3 . "- Un véhicule + Assurance pour les déplacements.<br/>";
                }
                if($employe_portable_pro=="Oui"){
                    $description_3 = $description_3 . "- Un téléphone portable professionnel.<br/>";
                }
                if($employe_demande_badge=="Oui"){
                    $description_3 = $description_3 . "- Un badge électronique pour la gestion des alarmes.<br/>";
                }
                // create some HTML content pour achat
                $pdf->AddPage();
                $html = ' 
                    <h1 align="center" style="margin: 200px; font-weight: bold;font-size: large;">JCM INVESTISSEMENTS</h1>
                    <hr>
                    <p align="center" style="font-weight: bold;font-size: small;text-decoration: underline;">ACCORD SUR L’USAGE ET LA RESTITUTION DE BIENS APPARTENANT A L’ENTREPRISE</p>
                    <p></p>
                    <p style="font-weight: bold;font-size: small;">ENTRE :</p>
                    <p style="font-weight: bold;font-size: small;">     - La Société : '.stripslashes($employe_site).' :</p>
                    <p style="font-size: small;">Représentée par Monsieur <strong>Olivier ERTEL</strong>, agissant en qualité de Directeur Général, d’une part, et<strong> '.stripslashes($employe_nom).' '.stripslashes($employe_prenom).'</strong></p>
                    <p style="font-size: small;">Ci-après dénommée le « collaborateur », d’autre part,</p>
                    <p style="font-size: small;"><strong>IL EST CONVENU ET ARRETE CE QUI SUIT :</strong></p>

                    <h1 align="center" style="margin: 200px; font-weight: bold;font-size: small;text-decoration: underline;">Article 1 : Matériel confié</h1>
                    <p style="font-size: small;">La société confie au collaborateur, pour l’exécution de ses fonctions, les équipements suivants, que le collaborateur reconnaît avoir reçu :<br />
                    '.$description_3.'
                    Ces équipements demeurent la propriété de l’entreprise et devront être restitués en cas de départ du collaborateur, sans qu’il y ait besoin d’une quelconque mise en demeure préalable.
                    </p>
                    
                    <h1 align="center" style="margin: 200px; font-weight: bold;font-size: small;text-decoration: underline;">Article 2 : Responsabilité</h1>
                    <p style="font-size: small;">Ces équipements permettent au collaborateur nommé ci-dessus de réaliser toutes ses taches pendant les horaires de travail.<br/>
                        En aucun cas, ces équipements ne peuvent être transmis à une tierce personne pour quelque raison que ce soit.<br/>
                        En cas de perte ou de vol, le collaborateur s’engage à en informer Jean FORGET au 06.64.68.13.13 ou '.$respo_achat.', afin de prévoir le remplacement le cas échéant.
                    </p>
                    <p style="font-size: small;">Fait, en double exemplaire, à LA ROCHELLE, le <br/>Signature du collaborateur précédée de la mention manuscrite « Lu et approuvé – Bon pour acceptation »
                    </p>
                    <p></p>
                    <table cellspacing="3" cellpadding="3">
                        <tr>
                            <th align="center" style="height: 60px;font-size: small;">'.stripslashes($employe_nom).' '.stripslashes($employe_prenom).'<br/>'.stripslashes($employe_poste).'</th>
                            <th align="center" style="height: 60px;font-size: small;">'.$respo_achat.'<br/>Responsable Achats</th>
                        </tr>
                    </table>
                ';
                $pdf->writeHTML($html, true, false, true, false, '');
            }

            // Sauvegardez le PDF avec un nom de fichier spécifique
            $outputFilePath = 'C:/MAMP/htdocs/CHECKLIST_WEBAPP_V2/01_scripts/RH_PAGES/00_PDF_CHECKLIST/CHECKLIST_'.$employe_nom.'_'.$employe_prenom.'.pdf';
            $pdf->Output($outputFilePath, 'F');


            // Chemin du fichier de configuration
                $configFile = '../../01_configFiles/phpmailer_rhNotif.txt';

                // Lire le fichier et parser les paramètres
                $config = [];
                if (file_exists($configFile)) {
                    $lines = file($configFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                    
                    foreach ($lines as $line) {
                        // Ignorer les commentaires
                        if (strpos(trim($line), '#') === 0) continue;
                        
                        // Séparer clé/valeur
                        list($key, $value) = explode('=', $line, 2);
                        
                        // Nettoyer et stocker dans le tableau
                        $key = trim($key);
                        $value = trim($value, " \t\n\r\0\x0B'"); // Enlever les quotes et espaces
                        $config[$key] = $value;
                    }
                } else {
                    die("Fichier de configuration '01_configFiles/phpmailer_rhNotif.txt' introuvable !");
                }
           
                $mail = new PHPMailer(true);
            
                try {

                    $mail->CharSet = 'UTF-8';
                    // Configuration SMTP
                    $mail->isSMTP();
                    $mail->Host       = $config['host'];
                    $mail->SMTPAuth   = true;
                    $mail->Username   = $config['username'];
                    $mail->Password   = $config['password'];
                    $mail->SMTPSecure = $config['smtpsecure'];
                    $mail->Port       = $config['port'];
            
                    // Expéditeur
                    $mail->setFrom($config['username']);
            
                    // Destinataire unique
                    $mail->addAddress($config['receiveraddress']);
            
                    // Contenu
                    $mail->isHTML(true);
                    $mail->Subject = "Entrée du $employe_arrive_date2 en attente de validation";
                    $mail->Body    = "<b>Bonjour à tous,</b><br>Je vous informe d'une entrée en attente de validation :<br> ".stripslashes($employe_prenom)." ".stripslashes($employe_nom)." ( ".$employe_birth_date2." ), "."en qualité de ".stripslashes($employe_poste)." sur le site de ".stripslashes($employe_site).", à compter du $employe_arrive_date2.";
                    $mail->send();

                    header('location: ../../01_scripts/CDS_PAGES/cds_homepage.php?send_checklist=success');

                } catch (Exception $e) {
                    echo "Erreur : {$mail->ErrorInfo}<br>";
                    header('location: ../../01_scripts/CDS_PAGES/cds_homepage.php?send_checklist=error&message=Erreur: {'.$mail->ErrorInfo.'}<br>');
                }
        }else {
            $error_message2= mysqli_error($database_connect);
            echo "SQL_error_message2 = [".$error_message2."]";
        }
    }else {
        $error_message= mysqli_error($database_connect);
        echo "SQL_error_message = [".$error_message."]";
    }
// LOGGING
$log_dir = __DIR__ . '/../../01_logs/';
if (!is_dir($log_dir)) { mkdir($log_dir, 0777, true); }
$log_file = $log_dir . 'logs_' . date('Ymd') . '.txt';
$user = isset($_SESSION['user_firstname']) ? $_SESSION['user_firstname'] . ' ' . $_SESSION['user_lastname'] : 'ANONYMOUS';
$action = 'SEND_CHECKLIST';
$state = isset($tasks_resp_achat) && isset($tasks_resp_batiment) ? 'success' : 'failed';
$dateheure = date('Y-m-d H:i:s');
$log_entry = "$user:$action:$state:$dateheure\n";
file_put_contents($log_file, $log_entry, FILE_APPEND);
?>