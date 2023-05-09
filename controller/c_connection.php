<?php

// Constantes
define('ACTION_SE_CONNECTER', 'seConnecter');
define('ACTION_CONFIRM_CONNECTION', 'confirmConnection');
define('ACTION_DECONNEXION', 'deconnexion');
define('ACTION_INSCRIPTION', 'inscription');
define('ACTION_CONFIRM_INSCRIPTION', 'confirmInscription');

// Variables
$mail = "";
$password = "";

// Traitement de l'action
switch ($_REQUEST['action']) {
    case ACTION_SE_CONNECTER:
        if (!$fsession->isConnected()) {
            include('views/auth/v_seConnecter.php');
        } else {
            include('views/visitor/v_profil.php');
            $type = "success";
            $message = "Vous êtes connecté !";
            include('views/v_toast.php');
        }
        break;

    case ACTION_CONFIRM_CONNECTION:
        $mail = $_REQUEST['username'];
        $password = $_REQUEST['password'];

        if (isset($mail) && isset($password)) {
            // Vérifie si le compte existe
            $account = $databaseController->accountExist($mail, $password);
            if (!empty($account)) {
                $id = $account['Id_Visiteur'];
                $nom = $account['nomvisiteur'];
                $prenom = $account['prenomvisiteur'];
                $mail = $account['mailvisiteur'];
                $phone = $account['numtelephonevisiteur'];
                $speed = $account['vitessemarche'];
                $admin = $account['isAdmin'];

                // Création de la session
                $fsession->createSession($id, $nom, $prenom, $mail, $phone, $speed, $admin, $spectacles_id, $date_Selected);

                // Redirection vers la page de profil
                header('Location: index.php?uc=account&action=profile');
            } else {
                $type = "error";
                $message = "Identifiants incorrects.";
            }
        } else {
            $type = "error";
            $message = "Veuillez remplir tous les champs.";
        }
        include('views/auth/v_seConnecter.php');
        include('views/v_toast.php');
        break;

    case ACTION_DECONNEXION:
        if ($fsession->isConnected()) {
            $fsession->disconnectSession();
            header('Location: index.php');
            $type = "info";
            $message = "Vous êtes déconnecté.";
            include('views/v_toast.php');
        }
        break;

    case ACTION_INSCRIPTION:
        include('views/auth/v_inscription.php');
        break;

    case ACTION_CONFIRM_INSCRIPTION:
        if (isset($_POST['create'])) {
            $nom = $_REQUEST['nomS'];
            $prenom = $_REQUEST['prenomS'];
            $mail = $_REQUEST['mailS'];
            $password = $_REQUEST['passwordS'];
            $passwordHashed = password_hash($password, PASSWORD_DEFAULT);
            $result = $databaseController->creerVisiteur($nom, $prenom, $mail, $passwordHashed);
            $type = "success";
            $message = "Vous êtes maintenant inscrit !";
            include('views/v_toast.php');
            include('views/auth/v_seConnecter.php');
        }
        break;

    default:
        // Action par défaut
        break;
}
