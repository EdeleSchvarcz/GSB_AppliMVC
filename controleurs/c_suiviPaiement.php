<?php

/**
 * Controleur suivi du paiement des frais.
 * 
 * PHP Versio/**n 7
 *
 * @category  PPE
 * @package   GSB
 * @author    Beth Sefer, Edele Schvarcz
 */
$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);
$idVisiteur = $_SESSION['idUtilisateur'];
$mois = getMois(date('d/m/Y'));
switch ($action) {
    case 'choixVM':
        $lesVisiteurs = $pdo->getLesVisiteursValide();
        $lesCles1 = array_keys($lesVisiteurs);
        $visiteurASelectionner = $lesCles1[0];
        $lesMois = $pdo->getLesMoisValide();
        $lesCles2 = array_keys($lesMois);
        $moisASelectionner = $lesCles2[0];
        include 'vues/v_suiviPaiement.php';


        break;
    case 'afficheFrais':
        $idVisiteur = filter_input(INPUT_POST, 'lstVisiteurs', FILTER_SANITIZE_STRING);
        $lesVisiteurs = $pdo->getLesVisiteurs();
        $visiteurASelectionner = $idVisiteur;
        $leMois = filter_input(INPUT_POST, 'lstMois', FILTER_SANITIZE_STRING);
        $lesMois = getLesDouzeDerniersMois($mois);
        $moisASelectionner = $leMois;
        $lesFraisForfait = $pdo->getLesFraisForfait($idVisiteur, $leMois);
        $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $leMois);
        $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($idVisiteur, $leMois);
        $numAnnee = substr($leMois, 0, 4);
        $numMois = substr($leMois, 4, 2);
        $libEtat = $lesInfosFicheFrais['libEtat'];
        $montantValide = $lesInfosFicheFrais['montantValide'];
        $nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
        $dateModif = dateAnglaisVersFrancais($lesInfosFicheFrais['dateModif']);
        if (!is_array($lesInfosFicheFrais)) {
            ajouterErreur('Pas de fiche de frais pour ce visiteur ce mois');
            include 'vues/v_erreurs.php';
            include 'vues/v_choixVM.php';
        } else {
            include 'vues/v_etatFrais.php';
            include 'vues/v_miseEnPaiement.php';
        }
        break;
    case 'paiement':

        $idVisiteur = filter_input(INPUT_POST, 'lstVisiteurs', FILTER_SANITIZE_STRING);
        $lesVisiteurs = $pdo->getLesVisiteurs();
        $visiteurASelectionner = $idVisiteur;
        $leMois = filter_input(INPUT_POST, 'lstMois', FILTER_SANITIZE_STRING);
        $lesMois = getLesDouzeDerniersMois($mois);
        $moisASelectionner = $leMois;
        $etat="RB";
        $pdo->majEtatFicheFrais($idVisiteur, $leMois, $etat);
        echo "la fiche a bien etait remboursée";

        break;
}