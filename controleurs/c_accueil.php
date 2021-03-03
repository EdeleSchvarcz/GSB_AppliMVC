<?php
/**
 * Gestion de l'accueil
 *
 * PHP Version 7
 *
 * @category  PPE
 * @package   GSB
 * @author    Beth Sefer, Edele Schvarcz
 */
$estVisiteurConnecte= estVisiteurConnecte();
$estComptableConnecte= estComptableConnecte() ;      
if ($estVisiteurConnecte) {
    include 'vues/v_accueil.php';
} else if($estComptableConnecte) 
{
  include 'vues/v_accueilComptable.php';  
}
    else{
    include 'vues/v_connexion.php';
}
