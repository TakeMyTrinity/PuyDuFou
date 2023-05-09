<?php
// Classe contrôleur gérant les interactions avec la base de données
class DatabaseController
{
    private $model;

    public function __construct()
    {
        $this->model = new bddManager();
    }

    // ------------------- SPECTACLE -------------------

    // retourne les spectacles
    public function getLesSpectacles()
    {
        $conn = $this->model->connect();
        $stmt = $conn->prepare("SELECT * FROM `spectacle`");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // retourne un spectacle en fonction de son id
    public function getSpectacle($id)
    {
        $conn = $this->model->connect();
        $stmt = $conn->prepare("SELECT * FROM `spectacle` WHERE Id_Spectacle = :id");
        $stmt->bindValue('id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    // ajouter un spectacle
    public function ajouterSpectacle($nom, $duree, $dureeAttente, $nbrplace)
    {
        $conn = $this->model->connect();
        $stmt = $conn->prepare('INSERT INTO spectacle (nomspectacle, duree, dureeattente, nbrplace) VALUES( :nom, :duree, :dureeAttente, :nbrplace)');
        $stmt->bindValue('nom', $nom);
        $stmt->bindValue('duree', $duree);
        $stmt->bindValue('dureeAttente', $dureeAttente);
        $stmt->bindValue('nbrplace', $nbrplace);
        $stmt->execute();
    }

    // modifier un spectacle
    public function modifierSpectacle($id, $nom, $duree, $dureeAttente, $nbrplace)
    {
        $conn = $this->model->connect();
        $stmt = $conn->prepare('UPDATE spectacle SET nomspectacle = :nom, duree = :duree, dureeattente = :dureeAttente, nbrplace = :nbrplace WHERE Id_Spectacle = :id');
        $stmt->bindValue('id', $id);
        $stmt->bindValue('nom', $nom);
        $stmt->bindValue('duree', $duree);
        $stmt->bindValue('dureeAttente', $dureeAttente);
        $stmt->bindValue('nbrplace', $nbrplace);
        $stmt->execute();
    }

    // supprimer un spectacle
    public function supprimerSpectacle($id)
    {
        $conn = $this->model->connect();
        $stmt = $conn->prepare('DELETE FROM spectacle WHERE Id_Spectacle = :id');
        $stmt->bindValue('id', $id);
        $stmt->execute();
    }

    // Retourne les Seances des spectacles
    public function getLesSeances()
    {
        $conn = $this->model->connect();
        $stmt = $conn->prepare("SELECT * FROM `seance`");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // ------------------- DATE PARC -------------------

    // Retourne les date d'ouverture et de fermeture du parc avec sa date
    public function getLesActudays()
    {
        $conn = $this->model->connect();
        $stmt = $conn->prepare("SELECT * FROM `parc_dates`");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // retourn en tableau les date du parc
    public function getLesDates()
    {
        $conn = $this->model->connect();
        $stmt = $conn->prepare("SELECT Dateparc FROM `parc_dates`");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Retourne la date d'ouverture du parc et de fermerture du parc selon la date
    public function getActuday($datesaisie)
    {
        $conn = $this->model->connect();
        $stmt = $conn->prepare("SELECT houverture, hfermeture FROM `parc_dates` WHERE Dateparc = :date");
        $stmt->bindValue('date', $datesaisie);
        $stmt->execute();
        return $stmt->fetch();
    }

    // ------------------- ACCOUNT -------------------

    // Verifie si un compte existe en fonction de son mail et de son mot de passe
    public function accountExist($mail, $password)
    {
        $conn = $this->model->connect();
        $stmt = $conn->prepare("SELECT * FROM visiteur WHERE mailvisiteur = :mail LIMIT 1");
        $stmt->bindValue('mail', $mail);
        $stmt->execute();
        $account = $stmt->fetch();
        if (!empty($account)) {
            if (password_verify($password, $account["password"])) {
                return $account;
            } else {
                return null;
            }
        } else {
            return null;
        }
    }

    // ------------------- VISITEUR -------------------

    // Creer un visiteur
    public function creerVisiteur($nom, $prenom, $mail, $passwordHashed)
    {
        $conn = $this->model->connect();
        $stmt = $conn->prepare('INSERT INTO visiteur (nomvisiteur, 
			prenomvisiteur, mailvisiteur, password) VALUES( :nom, 
			:prenom, :mail, :password)');
        $stmt->bindValue('nom', $nom);
        $stmt->bindValue('prenom', $prenom);
        $stmt->bindValue('mail', $mail);
        $stmt->bindValue('password', $passwordHashed);
        $stmt->execute();
    }

    // Retourne un visiteur en fonction de son id
    public function getVisiteur($id)
    {
        $conn = $this->model->connect();
        $stmt = $conn->prepare("SELECT * FROM `visiteur` WHERE Id_Visiteur = :id");
        $stmt->bindValue('id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function getVitesse($idvisiteur)
    {
        $conn = $this->model->connect();
        $stmt = $conn->prepare("SELECT vitessemarche FROM `visiteur` WHERE Id_Visiteur = :id");
        $stmt->bindValue('id', $idvisiteur);
        $stmt->execute();
        return $stmt->fetch();
    }

    // creer une selection de spectacle pour un visiteur selon ces préférences
    public function creerSelection($id_spec, $date_Selected, $id_user)
    {
        $conn = $this->model->connect();
        $stmt = $conn->prepare('INSERT INTO selection (Id_Spectacle, 
			Dateparc, Id_Visiteur) VALUES( :idspectacle, 
			:dateSpectacle, :idVisiteur)');
        $stmt->bindValue('idspectacle', $id_spec);
        $stmt->bindValue('dateSpectacle', $date_Selected);
        $stmt->bindValue('idVisiteur', $id_user);
        $stmt->execute();
    }

    // recuperer la selection d'un visiteur
    public function getSelection($datesaisie, $idvisiteur)
    {
        $conn = $this->model->connect();
        $stmt = $conn->prepare("SELECT spectacle.Id_Spectacle,duree,dureeattente from selection inner join spectacle on selection.Id_Spectacle=spectacle.Id_Spectacle
		where Id_Visiteur = :id AND Dateparc = :date");
        $stmt->bindValue('id', $idvisiteur);
        $stmt->bindValue('date', $datesaisie);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // creer une visite pour un visiteur
    public function creerVisite($date_Selected, $id_user)
    {
        $conn = $this->model->connect();
        $stmt = $conn->prepare('INSERT INTO visite (Dateparc, 
			Id_Visiteur) VALUES(:dateSpectacle, :idVisiteur)');
        $stmt->bindValue('dateSpectacle', $date_Selected);
        $stmt->bindValue('idVisiteur', $id_user);
        $stmt->execute();
    }

    // modifier un visiteur
    public function modifierVisiteur($id, $nom, $prenom, $mail, $marche)
    {
        $conn = $this->model->connect();
        $stmt = $conn->prepare('UPDATE visiteur SET nomvisiteur = :nom, prenomvisiteur = :prenom, mailvisiteur = :mail, 
        vitessemarche = :marche WHERE Id_Visiteur = :id');
        $stmt->bindValue('id', $id);
        $stmt->bindValue('nom', $nom);
        $stmt->bindValue('prenom', $prenom);
        $stmt->bindValue('mail', $mail);
        $stmt->bindValue('marche', $marche);
        $stmt->execute();
    }

    // ------------------- ETAPE -------------------
    public function supprimerEtapes($idvisiteur, $datesaisie)
    {
        $conn = $this->model->connect();
        $stmt = $conn->prepare('DELETE FROM etape WHERE Id_Visiteur = :idvisiteur AND Dateparc = :datesaisie');
        $stmt->bindValue('idvisiteur', $idvisiteur);
        $stmt->bindValue('datesaisie', $datesaisie);
        $stmt->execute();
    }

    // insert etape dans la base de données
    public function creerEtape($p, $datesaisie, $idvisiteur, $j, $tabparcours)
    {
        $conn = $this->model->connect();
        $stmt = $conn->prepare('INSERT INTO etape (Dateparc_1,Id_Parcours,Dateparc,Id_Visiteur,rang,chemin,Id_Spectacle,Horaire)
        VALUES(:Dateparc1,:Id_Parcours,:Dateparc,:Id_Visiteur,:rang,:chemin,:Id_Spectacle,:Horaire)');
        $stmt->bindValue('Dateparc1', $p);
        $stmt->bindValue('Id_Parcours', $j);
        $stmt->bindValue('Dateparc', $datesaisie);
        $stmt->bindValue('Id_Visiteur', $idvisiteur);
        $stmt->bindValue('rang', $j + 1);
        $stmt->bindValue('chemin', $tabparcours[$j][2]);
        $stmt->bindValue('Id_Spectacle', $tabparcours[$j][0]);
        $stmt->bindValue('Horaire', $tabparcours[$j][1]);
        $stmt->execute();
    }

    // permet de récupérer les informations sur les étapes d'un parcours spécifique pour un visiteur donné à une date donnée
    public function getEtapes($datesaisie, $idvisiteur, $parcours)
    {
        $conn = $this->model->connect();
        $stmt = $conn->prepare("SELECT rang,spectacle.Id_Spectacle,spectacle.nomspectacle,etape.Horaire,chemin,duree,dureeattente,Debutimmersif from etape 
		inner join spectacle on etape.Id_Spectacle=spectacle.Id_Spectacle
		inner join seance on seance.Id_Spectacle=spectacle.Id_Spectacle and etape.Horaire=seance.Horaire and etape.Dateparc=seance.Dateparc
		where etape.Dateparc= :datesaisie and Id_Visiteur = :idvisiteur and Id_Parcours= :idparcours order by rang;");
        $stmt->bindValue('datesaisie', $datesaisie);
        $stmt->bindValue('idvisiteur', $idvisiteur);
        $stmt->bindValue('idparcours', $parcours['Id_Parcours']);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // ------------------- PARCOURS -------------------
    public function supprimerParcours($idvisiteur, $datesaisie)
    {
        $conn = $this->model->connect();
        $stmt = $conn->prepare('DELETE FROM parcours WHERE Id_Visiteur = :idvisiteur AND Dateparc = :datesaisie');
        $stmt->bindValue('idvisiteur', $idvisiteur);
        $stmt->bindValue('datesaisie', $datesaisie);
        $stmt->execute();
    }

    // insert parcours dans la base de données
    public function creerParcours($p, $datesaisie, $idvisiteur)
    {
        $conn = $this->model->connect();
        $stmt = $conn->prepare('INSERT INTO parcours (Id_Parcours,Dateparc,Id_Visiteur) values 
		(:Id_Parcours,:Dateparc,:Id_Visiteur)');
        $stmt->bindValue('Id_Parcours', $p);
        $stmt->bindValue('Dateparc', $datesaisie);
        $stmt->bindValue('Id_Visiteur', $idvisiteur);
        $stmt->execute();
    }

    // get parcours with date and idvisiteur
    public function getParcours($datesaisie, $idvisiteur)
    {
        $conn = $this->model->connect();
        $stmt = $conn->prepare("SELECT Id_Parcours from parcours where Dateparc = :date AND Id_Visiteur = :id");
        $stmt->bindValue('date', $datesaisie);
        $stmt->bindValue('id', $idvisiteur);
        $stmt->execute();
        return $stmt->fetch();
    }

    // ------------------- Distance -------------------

    // Retourne la distance entre deux spectacle
    public function chargerDistances()
    {
        $conn = $this->model->connect();
        $stmt = $conn->prepare("SELECT Id_Spectacle,Id_Spectacle_1,kilometre from distance");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // ------------------- SEANCE -------------------
    public function getSeance($s, $heureparcours)
    {
        $conn = $this->model->connect();
        $stmt = $conn->prepare("SELECT Debutimmersif,Horaire from seance where Id_Spectacle = :id AND Horaire = :heureparcours ORDER BY Horaire limit 1");
        $stmt->bindValue('id', $s);
        $stmt->bindValue('heureparcours', $heureparcours);
        $stmt->execute();
        return $stmt->fetch();
    }
}
