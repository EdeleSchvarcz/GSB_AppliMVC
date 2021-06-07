<?php
/**
 * Controleur Valider Frais
 *
 * PHP Version 7
 *
 * @category  PPE
 * @package   GSB
 * @author    Edele Schvarcz
 */

$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);
$idVisiteur = $_SESSION['idUtilisateur'];
$mois=getMois(date('d/m/Y'));
switch ($action) {
    case 'choixVM':
       $lesVisiteurs=$pdo->getLesVisiteurs();
        
       $lesCles1=array_keys($lesVisiteurs);
      $visiteurASelectionner=$lesCles1[0];
       $lesMois = getLesDouzeDerniersMois($mois);
       $lesCles2=array_keys($lesMois);
       $moisASelectionner=$lesCles2[0];
         include 'vues/v_choixVM.php';
        
        break;
    case 'afficheFrais':
        $idVisiteur = filter_input(INPUT_POST, 'lstVisiteurs', FILTER_SANITIZE_STRING);
       $lesVisiteurs=$pdo->getLesVisiteurs();  
       $visiteurASelectionner=$idVisiteur;
       $leMois = filter_input(INPUT_POST, 'lstMois', FILTER_SANITIZE_STRING);
       $lesMois = getLesDouzeDerniersMois($mois);
       $moisASelectionner=$leMois;
       $lesFraisForfait = $pdo->getLesFraisForfait($idVisiteur, $leMois);
       $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $leMois);
       $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($idVisiteur, $leMois);
       if(!is_array($lesInfosFicheFrais)){
           ajouterErreur('Pas de fiche de frais pour ce visiteur ce mois');
           include 'vues/v_erreurs.php';
           include 'vues/v_choixVM.php';
       }else{ 
            $nbJustificatifs = filter_input(INPUT_POST, 'nbJust', FILTER_SANITIZE_STRING);
               include 'vues/v_afficheFrais.php';
           }
            break;
    case "validerMajFraisForfait": 
         $idVisiteur = filter_input(INPUT_POST, 'lstVisiteurs', FILTER_SANITIZE_STRING);
         $lesVisiteurs=$pdo->getLesVisiteurs();
         $visiteurASelectionner=$idVisiteur;
         $leMois = filter_input(INPUT_POST, 'lstMois', FILTER_SANITIZE_STRING);
         $lesMois = getLesDouzeDerniersMois($mois);
         $moisASelectionner=$leMois;
         $lesFrais  = filter_input(INPUT_POST, 'lesFrais', FILTER_DEFAULT, FILTER_FORCE_ARRAY);
         $pdo->majFraisForfait($idVisiteur, $leMois, $lesFrais);
          $lesFraisForfait = $pdo->getLesFraisForfait($idVisiteur, $leMois);
       $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $leMois);
         $nbJustificatifs = filter_input(INPUT_POST, 'nbJust', FILTER_SANITIZE_STRING);
         include 'vues/v_afficheFrais.php';
         break;
     
    case 'validerMajFraisHorsForfait':
         $lesVisiteurs=$pdo->getLesVisiteurs();
         $idVisiteur = filter_input(INPUT_POST, 'lstVisiteurs', FILTER_SANITIZE_STRING);
         $visiteurASelectionner=$idVisiteur;
         $leMois = filter_input(INPUT_POST, 'lstMois', FILTER_SANITIZE_STRING);
         $lesMois = getLesDouzeDerniersMois($mois);
         $moisASelectionner=$leMois;
         $montant= filter_input(INPUT_POST, 'montant', FILTER_SANITIZE_STRING);
         $libelle= filter_input(INPUT_POST, 'libelle', FILTER_SANITIZE_STRING);
         $date= filter_input(INPUT_POST, 'date', FILTER_SANITIZE_STRING);
         $idFHF= filter_input(INPUT_POST, 'idFHF', FILTER_SANITIZE_STRING);
         
            
       if(isset($_POST['corriger'])){
           valideInfosFrais($date, $libelle, $montant);
           if (nbErreurs() != 0) {
               include 'vues/v_erreurs.php';
           } else{
                $pdo->majFraisHorsForfait( $idVisiteur, $leMois,  $libelle, $date, $montant, $idFHF );
           }
           $lesFraisForfait = $pdo->getLesFraisForfait($idVisiteur, $leMois);
           $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $leMois);
           
           include 'vues/v_afficheFrais.php';

        }
        if(isset($_POST['reporter'])){
            $moisSuivant= getMoisSuivant($leMois);
            $pdo->refuseLibelle($idFHF);
            if ($pdo->estPremierFraisMois($idVisiteur, $moisSuivant)==false) {
               $pdo->creeNouvellesLignesFrais($idVisiteur, $moisSuivant);
                $lesFraisForfait = $pdo->getLesFraisForfait($idVisiteur, $leMois);
           $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $leMois);
           } 
            $pdo->creeNouveauFraisHorsForfait($idVisiteur,$moisSuivant,$libelle,$date, $montant);
             include 'vues/v_afficheFrais.php';
        }
       $nbJustificatifs = filter_input(INPUT_POST, 'nbJust', FILTER_SANITIZE_STRING);

         break;
       
    case 'validerFicheFrais':
        $idVisiteur = filter_input(INPUT_POST, 'lstVisiteurs', FILTER_SANITIZE_STRING);
        $lesVisiteurs=$pdo->getLesVisiteurs();  
        $visiteurASelectionner=$idVisiteur;
        $leMois = filter_input(INPUT_POST, 'lstMois', FILTER_SANITIZE_STRING);
        $lesMois = getLesDouzeDerniersMois($mois);
        $moisASelectionner=$leMois;
        $nbJustificatifs=filter_input(INPUT_POST, 'nbJust', FILTER_SANITIZE_STRING);
        $pdo->majNbJustificatifs($idVisiteur, $leMois, $nbJustificatifs);
        $etat='VA';
        $pdo-> majEtatFicheFrais($idVisiteur, $leMois, $etat);
        $sumFraisForfait= $pdo-> getSumFraisForfait($idVisiteur, $leMois);
        $sumFraisHorsForfait= $pdo-> getSumFraisHorsForfait($idVisiteur, $leMois);
        $sumTotal= $sumFraisForfait[0][0]+ $sumFraisHorsForfait[0];
        $pdo-> setMontantValide($idVisiteur, $leMois,$sumTotal);
        echo "la fiche a bien eté validé";
        $fichesFrais= $pdo-> getLesFichesFrais($mois);
        $fichesValides= $pdo->getLesFichesValide($leMois,$idVisiteur,$etat);
        $pourcentage= $pdo-> getLePourcentage();
        echo "vous avez tant de fiches";
        break;   
       }
