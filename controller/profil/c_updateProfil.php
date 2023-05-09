<?php
// $action :variable d'aiguillage
$action = $_REQUEST['action'];

switch ($action) {
    case 'update':
        include('views/visitor/v_updateProfil.php');
        break;

    case 'confirmUpdate':
        // on récupère les données du formulaire
        $id = $_SESSION['session']['id'];
        $nom = $_POST['nomvisiteur'];
        $prenom = $_POST['prenomvisiteur'];
        $mail = $_POST['mailvisiteur'];
        $marche = $_POST['vitessemarche'];

        // on fait des tests sur les données
        $testNom = preg_match("#^[a-zA-Z]{2,20}$#", $nom);
        $testPrenom = preg_match("#^[a-zA-Z]{2,20}$#", $prenom);
        $testMail = preg_match("#^[a-zA-Z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#", $mail);
        $testMarche = preg_match("#^[0-9]{1,2}$#", $marche);

        // si les données sont correctes
        if ($testNom && $testPrenom && $testMail && $testMarche) {
            // on met à jour les données dans la base
            $databaseController->modifierVisiteur($id, $nom, $prenom, $mail, $marche);

            // on met à jour les données dans la session
            $_SESSION['session'] = [
                'id' => $id,
                'nomvisiteur' => $nom,
                'prenomvisiteur' => $prenom,
                'mailvisiteur' => $mail,
                'vitessemarche' => $marche,
                'isAdmin' => $_SESSION['session']['isAdmin']
            ];

            // on redirige vers la page de profil
            header('Location: index.php?uc=account&action=profile');
            exit;
        }

        // sinon on affiche la page d'erreur
        $type = "error";
        $message = "Les données saisies ne sont pas correctes";
        include("views/v_toast.php");
        include('views/visitor/v_updateProfil.php');
        break;

    default:
        // Action par défaut
        break;
}
