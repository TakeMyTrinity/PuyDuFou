<?php
// Constantes
define('ACTION_PARCOURS', 'createParcours');
define('ACTION_PROCESS', 'ParcoursProcess');
define('ACTION_GENERATE', 'parcoursGenerate');


// Traitement de l'action
switch ($_REQUEST['action']) {
    case ACTION_PARCOURS:
        // recuperation de tous les spectacles
        $spectacles = $databaseController->getLesSpectacles();
        // Inclusion du fichier correspondant à l'action parcours
        include 'views/parcours/v_createParcours.php';
        break;

    case ACTION_PROCESS:
        // variable with delay for the maintenance page
        $delay = 5000; // 5 secondes
        // get the list of spectacles id and date in the URL
        $spectacles_id = $_GET['values'];
        $date_Selected = $_GET['date'];

        // update session with this 2 variables
        $_SESSION['session']['spectacles_id'] = $spectacles_id;
        $_SESSION['session']['date_Selected'] = $date_Selected;

        $id_user = $_SESSION['session']['id'];
        // split the spectacles id into an array
        $id_array = explode(',', $spectacles_id);

        // create the visite and check if the visite is already created and if the date is already taken display an error message
        try {
            $visite = $databaseController->creerVisite($date_Selected, $id_user);
        } catch (PDOException $e) {
            if ($e->getCode() == '23000') {
                // Handle the duplicate entry error here
                echo "<div class='alert-danger'>La date est déjà prise</div>";
            } else {
                // Handle other PDOException errors here
                echo "Une erreur s'est produite lors de la création de la visite : " . $e->getMessage();
            }
        }

        // create the selection of the spectacles for the visite
        foreach ($id_array as $id_spec) {
            $databaseController->creerSelection($id_spec, $date_Selected, $id_user);
        }

        include 'views/parcours/v_maintenance.php';

        break;

    case ACTION_GENERATE:
        $datesaisie = $_SESSION['session']['date_Selected'];
        $idvisiteur = $_SESSION['session']['id'];

        try {
            if ($_SERVER['SERVER_NAME'] == 'localhost') {
                $pdo = new PDO('mysql:host=localhost;dbname=puydufou', 'root', 'M180417*');
            } else {
                $pdo = new PDO('mysql:host=db672809286.db.1and1.com;dbname=db672809286', 'dbo672809286', 'BMw1234*');
            }
        } catch (Exception $e) {
            die('erreur :' . $e->getMessage());
        }

        $pdo->exec('SET NAMES utf8');
        // suppression des parcours avant recalcul
        $req = "delete from etape where Id_Visiteur =" . $idvisiteur . " and Dateparc='" . $datesaisie . "';";
        $result = $pdo->query($req);
        $req = "delete from parcours where Id_Visiteur =" . $idvisiteur . " and Dateparc='" . $datesaisie . "';";
        $result = $pdo->query($req);
        // chargement des distances dans le tableau $_distances 
        // chargement de la symétrie au cas où ce ne soit pas fait en base
        $_distances = array();
        $req = 'SELECT Id_Spectacle,Id_Spectacle_1,kilometre from distance';
        foreach ($pdo->query($req) as $distance) {
            $_distances[$distance['Id_Spectacle']][$distance['Id_Spectacle_1']] = $distance['kilometre'];
            $_distances[$distance['Id_Spectacle_1']][$distance['Id_Spectacle']] = $distance['kilometre'];
        }
        // vitesse du visiteur
        $req = "SELECT vitessemarche from visiteur where Id_Visiteur =" . $idvisiteur . ";";
        $result = $pdo->query($req);
        $visiteur = $result->fetch();
        $vitesse = $visiteur['vitessemarche'];

        //  heures du parc						
        $req = "SELECT houverture,hfermeture from parc_dates where Dateparc='" . $datesaisie . "'";
        $result = $pdo->query($req);
        $planning = $result->fetch();

        // chargement des spectacles sélectionnés et de leur durée et attente
        $req = "SELECT spectacle.Id_Spectacle,duree,dureeattente from selection inner join spectacle on selection.Id_Spectacle=spectacle.Id_Spectacle
                where Dateparc='" . $datesaisie . "' and Id_Visiteur =" . $idvisiteur . ";";

        foreach ($pdo->query($req) as $selection) {
            $ids = $selection['Id_Spectacle'];
            $sel[] = $ids;
            $duree[$ids] = $selection['duree'];
            $attente[$ids] = $selection['dureeattente'];
        }
        // calcul nombre de spectacles  
        $nbspectacles = count($sel);

        // calcul des permutations dans $tabper		

        $tabper = array();
        permutations($sel);
        $nbpermut = count($tabper);

        // nombre de parcours $p à zero
        $p = 0;

        // boucle sur les permutations
        for ($i = 0; $i < $nbpermut; $i++) {
            $a = 0;   // point précédent = 0 entrée sortie
            $heureparcours = $planning['houverture']; // heure début initialisée à l'heure d'ouverture du parc
            $ok = 1;            // flag ok mis à 1         
            $tabparcours = []; // table des étapes du parcours initialisée
            //boucle sur les les spectacles de la permutation si toujours ok

            for ($j = 0; ($j < $nbspectacles and $ok == 1); $j++) {
                $s = $tabper[$i][$j];  // spectacle concerné
                $b = $s;

                //recherche du chemin jusqu'à $b = $s
                $dist = 0;
                $cheminarray = array();
                [$dist, $cheminarray] = calc_chemin($a, $b); // calcul du chemin

                $tempschemin = ceil($dist / $vitesse * 60);  // en minutes
                $chem = implode(",", $cheminarray);  // recupération du chemin en chaine de caractères
                $heureparcours = add_time($heureparcours, $tempschemin); // ajout du temps de parcours
                list($hours, $minutes) = explode(':', $attente[$s]);
                $att = ((60 * $hours) + $minutes);
                $heureparcours = add_time($heureparcours, $att); // ajout de l'attente
                // recherche de la séance la plus tôt
                $req = "SELECT Debutimmersif,Horaire from seance where id_Spectacle =" . $s . " and Horaire >= '"
                    . $heureparcours . "' order by Horaire limit 1;";
                $result = $pdo->query($req);
                $seance = $result->fetch();
                if ($seance) // séance trouvée 
                {
                    if ($seance['Debutimmersif'] <> '00:00:00') {
                        $heureparcours = max($heureparcours, $seance['Debutimmersif']);
                    } else {
                        $heureparcours = $seance['Horaire'];
                    }
                    // heure suivante du parcours 

                    // calcul du chemin pour sortir
                    $dists = 0;
                    $cheminarrays = array();
                    [$dists, $cheminarrays] = calc_chemin($b, 0);
                    $tempssortie = ceil($dists / $vitesse * 60);
                    // calcul duree  
                    list($hours, $minutes) = explode(':', $duree[$s]);
                    $dur = ((60 * $hours) + $minutes);
                    $heureparcours = add_time($heureparcours, $dur); // ajout à l'heure du parcours

                    if (add_time($heureparcours, $tempssortie) <= $planning['hfermeture']) // test si dépassement
                    {
                        $a = $s;  // on conserve le spectacle précédent
                        // stockage du spectacle de la séance et du chemin

                        $parcours = [$s, $seance['Horaire'], $chem];
                        $tabparcours[] = $parcours;
                    } else // trop tard
                    {
                        $ok = 0;
                    }
                } else // pas de séance trouvée
                {
                    $ok = 0;
                }
            }
            // fin de la permutation si ok on insère le parcours et les étapes en bdd

            if ($ok == 1) {
                $p++;              // incrémentation num parcours 
                $res = $pdo->prepare('insert into parcours (Id_Parcours,Dateparc,Id_Visiteur) values 
                (:Id_Parcours,:Dateparc,:Id_Visiteur)');
                $res->bindValue(':Id_Parcours', $p);
                $res->bindValue(':Dateparc', $datesaisie);
                $res->bindValue(':Id_Visiteur', $idvisiteur);
                $res->execute();
                for ($j = 0; ($j < $nbspectacles); $j++) {
                    $res = $pdo->prepare('insert into etape (Dateparc_1,Id_Parcours,Dateparc,Id_Visiteur,rang,chemin,Id_Spectacle,Horaire)
                    values 
                    (:Dateparc1,:Id_Parcours,:Dateparc,:Id_Visiteur,:rang,:chemin,:Id_Spectacle,:Horaire)');
                    $res->bindValue(':Id_Parcours', $p);
                    $res->bindValue(':Dateparc', $datesaisie);
                    $res->bindValue(':Dateparc1', $datesaisie);
                    $res->bindValue(':Id_Visiteur', $idvisiteur);
                    $res->bindValue(':rang', $j + 1);
                    $res->bindValue(':chemin', $tabparcours[$j][2]);
                    $res->bindValue(':Id_Spectacle', $tabparcours[$j][0]);
                    $res->bindValue(':Horaire', $tabparcours[$j][1]);
                    $res->execute();
                }
            }
        }

        include("views/parcours/v_parcours.php");
        break;

    default:
        include 'views/parcours/v_createParcours.php';
        break;
}
