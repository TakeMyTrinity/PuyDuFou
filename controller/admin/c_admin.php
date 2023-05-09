<?php
// Constantes
define('ACTION_MAIN', 'spectacle');
define('ACTION_UPDATE', 'updateSpectacle');
define('ACTION_UPDATESPECTACLE', 'update');
define('ACTION_CONFIRMUPDATE', 'confirmUpdate');
define('ACTION_DELETE', 'deleteSpectacle');

// Traitement de l'action
switch ($_REQUEST['action']) {
    case ACTION_MAIN:
        $lesSpectacles = $databaseController->getLesSpectacles();
        include('views/admin/spectacles/v_spectacle.php');
        break;
    case ACTION_UPDATE:
        $id = $_REQUEST['id'];
        $leSpectacle = $databaseController->getSpectacle($id);
        include('views/admin/spectacles/v_updateSpectacle.php');
        break;
    case ACTION_UPDATESPECTACLE:
        $id = $_REQUEST['id'];
        include('views/admin/spectacles/v_updateSpectaclePage.php');
        break;
    case ACTION_CONFIRMUPDATE:
        $id = $_REQUEST['id'];
        $nom = $_REQUEST['nomSpectacle'];
        $duree = $_REQUEST['dureeSpectacle'];
        $dureeAttente = $_REQUEST['dureeAttenteSpectacle'];
        $nbrplace = $_REQUEST['nbrPlaceSpectacle'];

        $update = $databaseController->modifierSpectacle($id, $nom, $duree, $dureeAttente, $nbrplace);

        if ($update) {
            header('Location: index.php?uc=admin&action=spectacle');
        } else {
            $type = "error";
            $message = "Erreur lors de la modification du spectacle";
            include('views/v_toast.php');
        }
        break;
    case ACTION_DELETE:
        $id = $_REQUEST['id'];
        try {
            // Supprimer le spectacle
            $databaseController->supprimerSpectacle($id);
        } catch (PDOException $e) {
            // Gérer l'erreur de suppression
            if ($e->getCode() == '23000') {
                echo "Impossible de supprimer le spectacle car il est référencé dans la table programme.";
            } else {
                echo "Une erreur s'est produite lors de la suppression du spectacle : " . $e->getMessage();
            }
        }
        break;
    default:
        // Action par défaut
        break;
}
