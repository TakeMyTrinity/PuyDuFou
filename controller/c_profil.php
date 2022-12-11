<?php
// $action :variable d'aiguillage
$action = $_REQUEST['action'];
switch ($action) {
    case 'profile': {
            $id = $_SESSION['session']['id'];
            $laLigne = $pdo->getVisiteur($id);
            include('view/v_profil.php');
            break;
        }
    case 'profilview': {
            echo 'test';
        }
}