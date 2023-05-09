<?php
// Constantes
define('ACTION_PROFILE', 'profile');
define('ACTION_FROFILEVIEW', 'profilview');

// Variables
$idVisiteur = $_SESSION['session']['id'];
$nomVisiteur = $_SESSION['session']['nomvisiteur'];
$prenomVisiteur = $_SESSION['session']['prenomvisiteur'];
$mailVisiteur = $_SESSION['session']['mailvisiteur'];
$vitesseMarche = $_SESSION['session']['vitessemarche'];
$isAdmin = $fuser->isAdmin($_SESSION['session']['isAdmin']);

// Traitement de l'action
switch ($_REQUEST['action']) {
    case ACTION_PROFILE:
        $laLigne = $databaseController->getVisiteur($idVisiteur);
        $spectacles = $databaseController->getLesSpectacles();
        if ($isAdmin) {
            include('views/admin/v_profil.php');
        } else {
            include('views/visitor/v_profil.php');
        }
        break;

    case ACTION_FROFILEVIEW:
        $laLigne = $databaseController->getVisiteur($idVisiteur);
        include('views/visitor/v_viewProfil.php');
        break;
    default:
        // Action par défaut
        break;
}
