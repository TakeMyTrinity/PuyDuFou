<?php
//Ouverture de Session
session_start();
// inclusion en une seule fois du fichier des fonctions et du modèle
require_once('model/class.pdoPuyDuFou.inc.php');

if (!isset($_REQUEST['uc']))
    $uc = 'accueil';
else
    $uc = $_REQUEST['uc'];

// On fait un appel à l'instance de la class PdoPuyDuFou
$pdo = PdoPuyDuFou::getPdoPuyDuFou();

//Switch pour récuperer la valeur du uc dans l'url
switch ($uc) {
    case 'accueil': {
            include("view/v_accueil.php");
            break;
        }
    case 'connection': {
            include("controller/c_connection.php");
            break;
        }

    case 'account': {
            include("controller/c_profil.php");
            break;
        }
}