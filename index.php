<?php
// Ouverture d'une session
session_start();

// Inclusion en une seule fois des fichiers nécessaires
require_once 'models/bdd/bddManager.php';
require_once 'models/bdd/databaseController.php';
require_once 'models/session/sessionFunction.php';
require_once 'models/userFunctions/userFunctions.php';
require_once 'models/parcoursFunctions/fonctions_parcours.php';

// Instanciation des objets nécessaires
$databaseController = new DatabaseController();
$fsession = new sessionFunction();
$fuser = new userFunctions();

// Récupération de la valeur du uc dans l'URL
$uc = $_REQUEST['uc'] ?? 'accueil';

// Inclusion du fichier correspondant à la valeur du uc
switch ($uc) {
    case 'accueil':
        include 'views/v_home.php';
        break;
    case 'connection':
        include 'controller/c_connection.php';
        break;
    case 'account':
        include 'controller/profil/c_profil.php';
        break;
    case 'profil':
        include 'controller/profil/c_updateProfil.php';
        break;
    case 'admin':
        include 'controller/admin/c_admin.php';
        break;
    case 'parcours':
        include 'controller/parcours/c_parcours.php';
        break;
    default:
        include 'views/v_home.php';
        break;
}
