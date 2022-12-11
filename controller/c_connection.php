<?php
// $action :variable d'aiguillage
$action = $_REQUEST['action'];
switch ($action) {
    case 'seConnecter': {
            if (!$_SESSION['session']) {
                $mail = null;
                $password = null;
                include('view/v_seConnecter.php');
            } else {
                include('view/v_profil.php');
                $type = "success";
                $message = "Vous êtes connectée !";
                include('view/v_toast.php');
            }
            break;
        }
    case 'confirmConnection': {
            $mail = $_REQUEST['username'];
            $password = $_REQUEST['password'];
            if (isset($mail) && isset($password)) {
                $account = $pdo->accountExist($mail, $password);
                if (!empty($account)) {
                    $id = $account['Id_Visiteur'];
                    $pdo->createSession($id);
                    header('Location: index.php?uc=account&action=profile');
                    $type = "success";
                    $message = "Vous êtes connectée !";
                    include('view/v_toast.php');
                } else {
                    include("view/v_seConnecter.php");
                    $type = "error";
                    $message = "Identifiants incorrects.";
                    include('view/v_toast.php');
                }
            } else {
            }
            break;
        }

    case 'deconnexion': {
            if ($pdo->isConnected()) {
                $pdo->disconnectSession();
                $mail = null;
                $password = null;
                include("view/v_accueil.php");
                $type = "info";
                $message = "Vous vous êtes deconnectée";
                include('view/v_toast.php');
                break;
            }
            break;
        }

    case 'inscription': {
            include('view/v_inscription.php');
            break;
        }
    case 'confirmInscription': {
            if (isset($_POST['create'])) {
                $nom = $_REQUEST['nomS'];
                $prenom = $_REQUEST['prenomS'];
                $mail = $_REQUEST['mailS'];
                $password = $_REQUEST['passwordS'];
                $passwordHashed = password_hash($password, PASSWORD_DEFAULT);
                $result = $pdo->creerVisiteur($nom, $prenom, $mail, $passwordHashed);
                include('view/v_seConnecter.php');
                $type = "success";
                $message = "Vous êtes maintenant incrit !";
                include('view/v_toast.php');
            }
            break;
        }
}