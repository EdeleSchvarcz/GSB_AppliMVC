<?php
/**
 * Vue bouton mettre en paiement
 *
 * PHP Version 7
 *
 * @category  PPE
 * @package   GSB
 * @author    Edele Schvarcz
 */
?>
<form action="index.php?uc=suiviPaiement&action=paiement"
      method="post" role="form">
    <input name="lstMois" type="hidden" id="lstMois" class="form-control" value="<?php echo $moisASelectionner ?>">
    <input name="lstVisiteurs" type="hidden" id="lstVisiteurs" class="form-control" value="<?php echo $visiteurASelectionner ?>">
    <input id="ok" type="submit" value="Mettre en paiement" class="btn btn-success"
           role="button">
</form>