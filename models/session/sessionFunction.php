<?php
class sessionFunction
{
    // creer une session
    public function createSession($id, $nom, $prenom, $mail, $phone, $speed, $admin, $spectacles_id, $date_Selected)
    {
        $account = [
            "id" => $id,
            "nomvisiteur" => $nom,
            "prenomvisiteur" => $prenom,
            "mailvisiteur" => $mail,
            "numtelephonevisiteur" => $phone,
            "vitessemarche" => $speed,
            "isAdmin" => $admin,
            "spectacles_id" => $spectacles_id,
            "date_Selected" => $date_Selected
        ];
        $_SESSION["session"] = $account;
    }

    // verifier si une session est active
    public function isConnected()
    {
        if (isset($_SESSION["session"])) {
            return true;
        } else {
            return false;
        }
    }

    // supprimer une session
    public function disconnectSession()
    {
        $_SESSION["session"] = null;
        session_unset();
        session_destroy();
    }

    public function check_timout($inactivity_timout)
    {
        if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > $inactivity_timout)) {
            // If no activity has taken place during the specified inactivity period, destroy the session
            session_unset();
            session_destroy();
            header('Location: index.php');
            exit();
        }
        $_SESSION['LAST_ACTIVITY'] = time();
    }
}
