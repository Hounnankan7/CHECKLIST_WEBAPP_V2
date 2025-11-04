<?php 

echo "<form class='container' style='margin:0px; padding-top:20px; padding-bottom:20px' id='forrmulaire_suivi' method='POST' action='modif_suivi.php?role=$role_actuel'>"; 

    echo "<div class='col-sm-3'>";
        echo "<label for='attribution' class='form-label'>Check-List attribué à :</label>";
        echo "<select class='form-select' id='attribution' name='attribution' required disabled>";
        echo "<option value=''>Choisir...</option>";
        if ($profil_tasks['attribue_a']=="") {
            if ($resultatR->num_rows>0) {
                while ($rowd= $resultatR->fetch_assoc()) {
                    echo "<option value='" . $rowd['id']."'>". $rowd['user_firstname'] . " " . $rowd['user_lastname'] . "</option>";
                }    
            }else{
                echo "<option value=''>Aucun technicien trouvé</option>";
            }
        }else{
            echo "<option value='' selected >". $profil_tasks['attribue_a'] ."</option>";
            if ($resultatR->num_rows>0) {
                while ($rowd= $resultatR->fetch_assoc()) {

                    if ($profil_tasks['attribue_a'] == $rowd['user_firstname'] . " " . $rowd['user_lastname']) {
                        
                    }else{
                        echo "<option value='" . $rowd['id']."'>". $rowd['user_firstname'] . " " . $rowd['user_lastname'] . "</option>";
                    }
                }    
            }else{
                echo "<option value=''>Aucun technicien trouvé</option>";
            }
        }
        echo "</select>";
        echo '<div class="invalid-feedback">';
                echo 'Faite un choix valable.';
        echo '</div>';
    echo '</div>';

    echo "<div class='form-check'>";
        if ($profil_tasks['creation_mail'] == "A faire") {
            echo '<label class="form-check-label" for="creation_mail">Creation de mail </label>';
            echo "<input type='checkbox' class='form-check-input' id='creation_mail' name='creation_mail' value='valeur1' disabled>";
        }elseif ($profil_tasks['creation_mail'] == "Terminée") {
            echo '<label class="form-check-label" for="creation_mail">Creation de mail </label>';
            echo "<input type='checkbox' class='form-check-input' id='creation_mail' name='creation_mail' value='valeur2' checked disabled>";
        }
        elseif ($profil_tasks['creation_mail'] == "Pas besoin") {
            echo '<label style="color:red;" class="form-check-label" for="creation_mail">Creation de mail </label>';
            echo "<input type='checkbox' class='form-check-input' id='creation_mail' name='creation_mail' value='valeur2' checked disabled>";
        }
    echo "</div>";

    echo "<div class='form-check'>";
        if ($profil_tasks['redirection_mail'] == "A faire") {
            echo '<label class="form-check-label" for="redirection_mail">Redirection de mail </label>';
            echo "<input type='checkbox' class='form-check-input' id='redirection_mail' name='redirection_mail' value='valeur1' disabled>";
        }elseif ($profil_tasks['redirection_mail'] == "Terminée") {
            echo '<label class="form-check-label" for="redirection_mail">Redirection de mail </label>';
            echo "<input type='checkbox' class='form-check-input' id='redirection_mail' name='redirection_mail' value='valeur2' checked disabled>";
        }elseif ($profil_tasks['redirection_mail'] == "Pas besoin") {
            echo '<label style="color:red;" class="form-check-label" for="redirection_mail">Redirection de mail </label>';
            echo "<input type='checkbox' class='form-check-input' id='redirection_mail' name='redirection_mail' value='valeur2' checked disabled>";
        }
    echo "</div>";

    echo "<div class='form-check'>";
        if ($profil_tasks['creation_rrf'] == "A faire") {
            echo '<label class="form-check-label" for="creation_rrf">Création dans RRF/DMD </label>';
            echo "<input type='checkbox' class='form-check-input' id='creation_rrf' name='creation_rrf' value='valeur1' disabled>";
        }elseif ($profil_tasks['creation_rrf'] == "Terminée") {
            echo '<label class="form-check-label" for="creation_rrf">Création dans RRF/DMD </label>';
            echo "<input type='checkbox' class='form-check-input' id='creation_rrf' name='creation_rrf' value='valeur2' checked disabled>";
        }
        elseif ($profil_tasks['creation_rrf'] == "Pas besoin") {
            echo '<label style="color:red;" class="form-check-label" for="creation_rrf">Création dans RRF/DMD </label>';
            echo "<input type='checkbox' class='form-check-input' id='creation_rrf' name='creation_rrf' value='valeur2' checked disabled>";
        }
    echo "</div>";


    echo "<div class='form-check'>";
        if ($profil_tasks['creation_rlearning'] == "A faire") {
            echo '<label class="form-check-label" for="creation_rlearning">Création de RLearning </label>';
            echo "<input type='checkbox' class='form-check-input' id='creation_rlearning' name='creation_rlearning' value='valeur1' disabled>";
        }elseif ($profil_tasks['creation_rlearning'] == "Terminée") {
            echo '<label class="form-check-label" for="creation_rlearning">Création de RLearning </label>';
            echo "<input type='checkbox' class='form-check-input' id='creation_rlearning' name='creation_rlearning' value='valeur2' checked disabled>";
        }elseif ($profil_tasks['creation_rlearning'] == "Pas besoin") {
            echo '<label style="color:red;" class="form-check-label" for="creation_rlearning">Création de RLearning </label>';
            echo "<input type='checkbox' class='form-check-input' id='creation_rlearning' name='creation_rlearning' value='valeur2' checked disabled>";
        }
    echo "</div>";

    echo "<div class='form-check'>";
        if ($profil_tasks['creation_rcampus'] == "A faire") {
            echo '<label class="form-check-label" for="creation_rcampus">Création de RCampus </label>';
            echo "<input type='checkbox' class='form-check-input' id='creation_rcampus' name='creation_rcampus' value='valeur1' disabled>";
        }elseif ($profil_tasks['creation_rcampus'] == "Terminée") {
            echo '<label class="form-check-label" for="creation_rcampus">Création de RCampus </label>';
            echo "<input type='checkbox' class='form-check-input' id='creation_rcampus' name='creation_rcampus' value='valeur2' checked disabled>";
        }elseif ($profil_tasks['creation_rcampus'] == "Pas besoin") {
            echo '<label style="color:red;" class="form-check-label" for="creation_rcampus">Création de RCampus </label>';
            echo "<input type='checkbox' class='form-check-input' id='creation_rcampus' name='creation_rcampus' value='valeur2' checked disabled disabled>";
        }
    echo "</div>";


    echo "<div class='form-check'>";
        if ($profil_tasks['creation_dcsnet'] == "A faire") {
            echo '<label class="form-check-label" for="creation_dcsnet">Création DCS Net </label>';
            echo "<input type='checkbox' class='form-check-input' id='creation_dcsnet' name='creation_dcsnet' value='valeur1' disabled>";
        }elseif ($profil_tasks['creation_dcsnet'] == "Terminée") {
            echo '<label class="form-check-label" for="creation_dcsnet">Création DCS Net </label>';
            echo "<input type='checkbox' class='form-check-input' id='creation_dcsnet' name='creation_dcsnet' value='valeur2' checked disabled>";
        }elseif ($profil_tasks['creation_dcsnet'] == "Pas besoin") {
            echo '<label style="color:red;" class="form-check-label" for="creation_dcsnet">Création DCS Net </label>';
            echo "<input type='checkbox' class='form-check-input' id='creation_dcsnet' name='creation_dcsnet' value='valeur2' checked disabled>";
        }
    echo "</div>";

    echo "<div class='form-check'>";
        if ($profil_tasks['creation_tocken'] == "A faire") {
            echo '<label class="form-check-label" for="creation_tocken">Création de tocken </label>';
            echo "<input type='checkbox' class='form-check-input' id='creation_tocken' name='creation_tocken' value='valeur1' disabled>";
        }elseif ($profil_tasks['creation_tocken'] == "Terminée") {
            echo '<label class="form-check-label" for="creation_tocken">Création de tocken </label>';
            echo "<input type='checkbox' class='form-check-input' id='creation_tocken' name='creation_tocken' value='valeur2' checked disabled>";
        }elseif ($profil_tasks['creation_tocken'] == "Pas besoin") {
            echo '<label style="color:red;" class="form-check-label" for="creation_tocken">Création de tocken </label>';
            echo "<input type='checkbox' class='form-check-input' id='creation_tocken' name='creation_tocken' value='valeur2' checked disabled>";
        }
    echo "</div>";

    echo "<div class='form-check'>";
        if ($profil_tasks['attribution_telephone'] == "A faire") {
            echo '<label class="form-check-label" for="attribution_telephone">Attribution d\'une ligne téléphonique </label>';
            echo "<input type='checkbox' class='form-check-input' id='attribution_telephone' name='attribution_telephone' value='valeur1' disabled disabled>";
        }elseif ($profil_tasks['attribution_telephone'] == "Terminée") {
            echo '<label class="form-check-label" for="attribution_telephone">Attribution d\'une ligne téléphonique </label>';
            echo "<input type='checkbox' class='form-check-input' id='attribution_telephone' name='attribution_telephone' value='valeur2' checked disabled>";
        }elseif ($profil_tasks['attribution_telephone'] == "Pas besoin") {
            echo '<label style="color:red;" class="form-check-label" for="attribution_telephone">Attribution d\'une ligne téléphonique </label>';
            echo "<input type='checkbox' class='form-check-input' id='attribution_telephone' name='attribution_telephone' value='valeur2' checked disabled>";
        }
    echo "</div>";

    echo "<div class='form-check'>";
        if ($profil_tasks['preaparation_pc'] == "A faire") {
            echo '<label class="form-check-label" for="preaparation_pc">Attribution d\'un Ordinateur </label>';
            echo "<input type='checkbox' class='form-check-input' id='preaparation_pc' name='preaparation_pc' value='valeur1' disabled>";
        }elseif ($profil_tasks['preaparation_pc'] == "Terminée") {
            echo '<label class="form-check-label" for="preaparation_pc">Attribution d\'un Ordinateur </label>';
            echo "<input type='checkbox' class='form-check-input' id='preaparation_pc' name='preaparation_pc' value='valeur2' checked disabled>";
        }elseif ($profil_tasks['preaparation_pc'] == "Pas besoin") {
            echo '<label style="color:red;" class="form-check-label" for="preaparation_pc">Attribution d\'un Ordinateur </label>';
            echo "<input type='checkbox' class='form-check-input' id='preaparation_pc' name='preaparation_pc' value='valeur2' checked disabled>";
        }
    echo "</div>";

    echo "<div class='form-check'>";
        if ($profil_tasks['preparation_malette'] == "A faire") {
            echo '<label class="form-check-label" for="preparation_malette">Attribution d\'une malette </label>';
            echo "<input type='checkbox' class='form-check-input' id='preparation_malette' name='preparation_malette' value='valeur1' disabled>";
        }elseif ($profil_tasks['preparation_malette'] == "Terminée") {
            echo '<label class="form-check-label" for="preparation_malette">Attribution d\'une malette </label>';
            echo "<input type='checkbox' class='form-check-input' id='preparation_malette' name='preparation_malette' value='valeur2' checked disabled>";
        }elseif ($profil_tasks['preparation_malette'] == "Pas besoin") {
            echo '<label style="color:red;" class="form-check-label" for="preparation_malette">Attribution d\'une malette </label>';
            echo "<input type='checkbox' class='form-check-input' id='preparation_malette' name='preparation_malette' value='valeur2' checked disabled>";
        }
    echo "</div>";

    echo "<div class='form-check'>";
        if ($profil_tasks['preparation_imprimante'] == "A faire") {
            echo '<label class="form-check-label" for="preparation_imprimante">Attribution d\'une imprimante </label>';
            echo "<input type='checkbox' class='form-check-input' id='preparation_imprimante' name='preparation_imprimante' value='valeur1' disabled>";
        }elseif ($profil_tasks['preparation_imprimante'] == "Terminée") {
            echo '<label class="form-check-label" for="preparation_imprimante">Attribution d\'une imprimante </label>';
            echo "<input type='checkbox' class='form-check-input' id='preparation_imprimante' name='preparation_imprimante' value='valeur2' checked disabled>";
        }elseif ($profil_tasks['preparation_imprimante'] == "Pas besoin") {
            echo '<label style="color:red;" class="form-check-label" for="preparation_imprimante">Attribution d\'une imprimante </label>';
            echo "<input type='checkbox' class='form-check-input' id='preparation_imprimante' name='preparation_imprimante' value='valeur2' checked disabled>";
        }
    echo "</div>";

    echo '<div class="row g-3" style="padding-top:10px; padding-bottom:10px">';

        if($profil_tasks['date_envoi']==""){

            echo '<div class="col-sm-3">';
                echo '<label for="date_envoi" class="form-label" disabled>Date d\'envoi équipement informatique (JJ/MM/AAAA)</label>';
                echo '<input type="text" class="form-control" name="date_envoi" id="date_envoi" placeholder="" value="" disabled>';
            echo '</div>';

        }elseif($profil_tasks['date_envoi']!=""){
            echo '<div class="col-sm-3">';
                echo '<label for="date_envoi" class="form-label" disabled>Date d\'envoi équipement informatique (JJ/MM/AAAA)</label>';
                echo '<input type="text" class="form-control" name="date_envoi" id="date_envoi" placeholder="" value="'. date('d-m-Y', strtotime($profil_tasks['date_envoi'])) .'" disabled>';
            echo '</div>';
        }

    echo '</div>';

    echo "<div class='form-check'>";
        if ($profil_tasks['attribution_vehicule'] == "A faire") {
            echo '<label class="form-check-label" for="attribution_vehicule">Attribution d\'un véhicule </label>';
            echo "<input type='checkbox' class='form-check-input' id='attribution_vehicule' name='attribution_vehicule' value='valeur1' disabled>";
        }elseif ($profil_tasks['attribution_vehicule'] == "Terminée") {
            echo '<label class="form-check-label" for="attribution_vehicule">Attribution d\'un véhicule </label>';
            echo "<input type='checkbox' class='form-check-input' id='attribution_vehicule' name='attribution_vehicule' value='valeur2' checked disabled>";
        }elseif ($profil_tasks['attribution_vehicule'] == "Pas besoin") {
            echo '<label style="color:red;" class="form-check-label" for="attribution_vehicule">Attribution d\'un véhicule </label>';
            echo "<input type='checkbox' class='form-check-input' id='attribution_vehicule' name='attribution_vehicule' value='valeur2' checked disabled>";
        }
    echo "</div>";

    echo "<div class='form-check'>";
        if ($profil_tasks['attribution_badge'] == "A faire") {
            echo '<label class="form-check-label" for="attribution_badge">Attribution d\'un badge </label>';
            echo "<input type='checkbox' class='form-check-input' id='attribution_badge' name='attribution_badge' value='valeur1' disabled>";
        }elseif ($profil_tasks['attribution_badge'] == "Terminée") {
            echo '<label class="form-check-label" for="attribution_badge">Attribution d\'un badge </label>';
            echo "<input type='checkbox' class='form-check-input' id='attribution_badge' name='attribution_badge' value='valeur2' checked disabled>";
        }elseif ($profil_tasks['attribution_badge'] == "Pas besoin") {
            echo '<label style="color:red;" class="form-check-label" for="attribution_badge">Attribution d\'un badge </label>';
            echo "<input type='checkbox' class='form-check-input' id='attribution_badge' name='attribution_badge' value='valeur2' checked disabled>";
        }
    echo "</div>";

    echo "<div class='form-check'>";
        if ($profil_tasks['attribution_cles'] == "A faire") {
            echo '<label class="form-check-label" for="attribution_cles">Attribution clés des batiments </label>';
            echo "<input type='checkbox' class='form-check-input' id='attribution_cles' name='attribution_cles' value='valeur1'>";
        }elseif ($profil_tasks['attribution_cles'] == "Terminée") {
            echo '<label class="form-check-label" for="attribution_cles">Attribution clés des batiments </label>';
            echo "<input type='checkbox' class='form-check-input' id='attribution_cles' name='attribution_cles' value='valeur2' checked>";
        }elseif ($profil_tasks['attribution_cles'] == "Pas besoin") {
            echo '<label style="color:red;" class="form-check-label" for="attribution_cles">Attribution clés des batiments </label>';
            echo "<input type='checkbox' class='form-check-input' id='attribution_cles' name='attribution_cles' value='valeur2' checked disabled>";
        }
    echo "</div>";

    echo "<div class='form-check'>";
        if ($profil_tasks['attribution_vetements'] == "A faire") {
            echo '<label class="form-check-label" for="attribution_vetements">Attribution de vêtements</label>';
            echo "<input type='checkbox' class='form-check-input' id='attribution_vetements' name='attribution_vetements' value='valeur1' disabled>";
        }elseif ($profil_tasks['attribution_vetements'] == "Terminée") {
            echo '<label class="form-check-label" for="attribution_vetements">Attribution de vêtements</label>';
            echo "<input type='checkbox' class='form-check-input' id='attribution_vetements' name='attribution_vetements' value='valeur2' checked disabled>";
        }elseif ($profil_tasks['attribution_vetements'] == "Pas besoin") {
            echo '<label style="color:red;" class="form-check-label" for="attribution_vetements">Attribution de vêtements</label>';
            echo "<input type='checkbox' class='form-check-input' id='attribution_vetements' name='attribution_vetements' value='valeur2' checked disabled>";
        }
    echo "</div>";

    echo "<div class='card' style='width: 25%;'>";
        echo '<div class="card-header">';
            echo "Taille des vêtements à attribués";
        echo '</div>';
        echo "<div class='card-body'>";
            echo nl2br(str_replace(', ', "\n", $profil['commentaire_vetement']));
        echo "</div>";
    echo "</div>";
    echo "<div class='form-check'>";

        if ($profil_tasks['attribution_chaussures'] == "A faire") {
            echo '<label class="form-check-label" for="attribution_chaussures">Attribution de Chaussures</label>';
            echo "<input type='checkbox' class='form-check-input' id='attribution_chaussures' name='attribution_chaussures' value='valeur1' disabled>";
        }elseif ($profil_tasks['attribution_vetements'] == "Terminée") {
            echo '<label class="form-check-label" for="attribution_vetements">Attribution de vêtements</label>';
            echo "<input type='checkbox' class='form-check-input' id='attribution_chaussures' name='attribution_chaussures' value='valeur2' checked disabled>";
        }elseif ($profil_tasks['attribution_vetements'] == "Pas besoin") {
            echo '<label style="color:red;" class="form-check-label" for="attribution_chaussures">Attribution de Chaussures</label>';
            echo "<input type='checkbox' class='form-check-input' id='attribution_chaussures' name='attribution_chaussures' value='valeur2' checked disabled>";
        }
    echo "</div>";
    echo "<div class='card' style='width: 25%;'>";
        echo '<div class="card-header">';
            echo "Pointure";
        echo '</div>';
        echo "<div class='card-body'>";
            echo nl2br(str_replace(', ', "\n", $profil['pointure']));
        echo "</div>";
    echo "</div>";

    echo '<button style="margin-top: 30px; margin-bottom: 20px;background:#EF7837;color:white;" class="w-20 btn btn-lg" type="submit">Sauvegarder</button>';
echo "</form>";

?>