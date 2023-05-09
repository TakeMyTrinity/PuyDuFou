<?php
echo "Calcul des parcours";
echo "<br>";

/* saisie */
ob_implicit_flush();
$datesaisie = '2022-09-29';
$idvisiteur = 1;

try {
	if ($_SERVER['SERVER_NAME'] == 'localhost') {
		$pdo = new PDO('mysql:host=localhost;dbname=puydufou', 'root', 'M180417*');
	} else {
		$pdo = new PDO('mysql:host=db763571447.hosting-data.io;dbname=db763571447', 'dbo763571447', '*********');
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

echo "nb spectacles " . $nbspectacles . " : " . implode(',', $sel);
echo "<br/>";

// calcul des permutations dans $tabper		

$tabper = array();
$fparcours->permutations($sel);
$nbpermut = count($tabper);

echo "nb permutation " . $nbpermut;
ob_flush();

$fparcours->Initialize(50, 60, 200, 30, '#000000', '#FFCC00', '#006699');  // initialisation de la barre de progression

// nombre de parcours $p à zero
$p = 0;

// boucle sur les permutations
for ($i = 0; $i < $nbpermut; $i++) {
	$fparcours->ProgressBar(ceil(($i / $nbpermut) * 100)); // barre de progression
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
		[$dist, $cheminarray] = $fparcours->calc_chemin($a, $b); // calcul du chemin

		$tempschemin = ceil($dist / $vitesse * 60);  // en minutes
		$chem = implode(",", $cheminarray);  // recupération du chemin en chaine de caractères
		$heureparcours = $fparcours->add_time($heureparcours, $tempschemin); // ajout du temps de parcours
		list($hours, $minutes) = explode(':', $attente[$s]);
		$att = ((60 * $hours) + $minutes);
		$heureparcours = $fparcours->add_time($heureparcours, $att); // ajout de l'attente
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
			[$dists, $cheminarrays] = $fparcours->calc_chemin($b, 0);
			$tempssortie = ceil($dists / $vitesse * 60);
			// calcul duree  
			list($hours, $minutes) = explode(':', $duree[$s]);
			$dur = ((60 * $hours) + $minutes);
			$heureparcours = $fparcours->add_time($heureparcours, $dur); // ajout à l'heure du parcours

			if ($fparcours->add_time($heureparcours, $tempssortie) <= $planning['hfermeture']) // test si dépassement
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
// fin des permutations, combien de parcours possibles
echo "<br>";
echo "<br>";
echo "<br>";
echo "<br>";
echo "nombre de parcours " . $p;
echo "<br>";


// Affichage des parcours
echo "visiteur  : " . $idvisiteur . " date : " . $datesaisie;
echo "<br>";
$req = "SELECT Id_Parcours from parcours
		where Dateparc='" . $datesaisie . "' and Id_Visiteur =" . $idvisiteur . ";";

foreach ($pdo->query($req) as $parcours) {
	echo "------------------ Parcours " . $parcours['Id_Parcours'];
	echo "<br>";
	echo "Heure ouverture parc " . $planning['houverture'];
	echo "<br>";
	$req = "SELECT rang,spectacle.Id_Spectacle,spectacle.nomspectacle,etape.Horaire,chemin,duree,dureeattente,Debutimmersif from etape 
		inner join spectacle on etape.Id_Spectacle=spectacle.Id_Spectacle
		inner join seance on seance.Id_Spectacle=spectacle.Id_Spectacle and etape.Horaire=seance.Horaire and etape.Dateparc=seance.Dateparc
		where etape.Dateparc='" . $datesaisie . "' and Id_Visiteur =" . $idvisiteur . " and Id_Parcours="
		. $parcours['Id_Parcours'] . " order by rang;";

	foreach ($pdo->query($req) as $etape) {
		$s = $etape['Id_Spectacle'];
		if ($etape['rang'] == 1) {
			$a = 0;
		}
		$dist = 0;
		$cheminarray = array();
		[$dist, $cheminarray] = $fparcours->calc_chemin($a, $s);
		$tempschemin = ceil($dist / $vitesse * 60);

		echo " ----> Etape " . $etape['rang'] . " Spectacle " . $s .
			" nom " . $etape['nomspectacle'];
		echo "<br>";
		echo "Temps de marche " . $tempschemin . " minutes par le chemin " . $etape['chemin'];
		echo "<br>";

		echo "Attente de " . date('i', strtotime($etape['dureeattente'])) . " minutes";
		echo "<br>";
		if ($etape['Debutimmersif'] == '00:00:00') {
			echo "Début spectacle à " . $etape['Horaire'] . " durée " . $etape['duree'];
			echo "<br>";
		} else {
			echo "Spectacle immersif de " . $etape['Debutimmersif'] . " à " . $etape['Horaire'];
			echo "<br>";
		}
		if ($etape['rang'] == $nbspectacles) {
			$dist = 0;
			$cheminarray = array();
			[$dist, $cheminarray] = $fparcours->calc_chemin($s, 0);
			$tempschemin = ceil($dist / $vitesse * 60);
			echo "temps de marche vers la sortie " . $tempschemin . " minutes par le chemin " . implode(",", $cheminarray);
			echo "<br>";
			echo "Heure fermeture parc " . $planning['hfermeture'];
			echo "<br>";
		}
		echo "<br>";
		$a = $s;
	}
}
