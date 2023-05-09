<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Inclure les fichiers CSS de Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet" />
    <!-- Google Font Link for Icons -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">
    <!-- Inclure le fichier CSS personnalisé -->
    <link rel="stylesheet" href="./utils/css/parcours/parcours.css">
    <title>Votre Programme</title>
</head>

<body>
    <div class="background-image-container">
        <img class="background-image" src="https://prod-sites-pdf-webdata.s3.eu-west-1.amazonaws.com/france/files/s3fs-public/styles/home_main_image_desktop/public/homepage/main-image/visuel_page_accueil_pdf_france_v2.jpg.webp?VersionId=BBtO6ybCZMQZal0uHTQ5oGfZIOrBYjRK&itok=WvkI1sqi" alt="Visuel Vikings Puy Du Fou 2023">
    </div>

    <div id="program-generated">
        <div class="container">
            <h1>Vos différents parcours</h1>
            <p>pour votre visite le <span><?= $datesaisie ?></span></p>
            <?php
            $req = "SELECT Id_Parcours from parcours
    where Dateparc='" . $datesaisie . "' and Id_Visiteur =" . $idvisiteur . ";";

            // Récupération des parcours pour le visiteur et la date donnée
            $req = "SELECT Id_Parcours from parcours
        where Dateparc='" . $datesaisie . "' and Id_Visiteur =" . $idvisiteur . ";";

            // Boucle pour afficher chaque parcours
            foreach ($pdo->query($req) as $parcours) {
            ?>
                <div class="card mx-auto col-md-6">
                    <div class="card-header" id="heading<?php echo $parcours['Id_Parcours']; ?>">
                        <h5 class="mb-0">
                            <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapse<?php echo $parcours['Id_Parcours']; ?>" aria-expanded="true" aria-controls="collapse<?php echo $parcours['Id_Parcours']; ?>">
                                Parcours <?php echo $parcours['Id_Parcours']; ?>
                            </button>
                        </h5>
                    </div>

                    <div id="collapse<?php echo $parcours['Id_Parcours']; ?>" class="collapse" aria-labelledby="heading<?php echo $parcours['Id_Parcours']; ?>" data-parent="#accordion">
                        <div class="card-body">
                            <p>Heure ouverture parc <?php echo $planning['houverture']; ?></p>
                            <?php
                            // Récupération des étapes pour le parcours donné
                            $req = "SELECT rang,spectacle.Id_Spectacle,spectacle.nomspectacle,etape.Horaire,chemin,duree,dureeattente,Debutimmersif from etape 
                inner join spectacle on etape.Id_Spectacle=spectacle.Id_Spectacle
                inner join seance on seance.Id_Spectacle=spectacle.Id_Spectacle and etape.Horaire=seance.Horaire and etape.Dateparc=seance.Dateparc
                where etape.Dateparc='" . $datesaisie . "' and Id_Visiteur =" . $idvisiteur . " and Id_Parcours="
                                . $parcours['Id_Parcours'] . " order by rang;";

                            // Boucle pour afficher chaque étape
                            foreach ($pdo->query($req) as $etape) {
                                $s = $etape['Id_Spectacle'];
                                if ($etape['rang'] == 1) {
                                    $a = 0;
                                }
                                // Calcul de la distance et du temps de marche entre les étapes
                                $dist = 0;
                                $chemarray = array();
                                [$dist, $cheminarray] = calc_chemin($a, $s);
                                $tempschemin = ceil($dist / $vitesse * 60);
                            ?>
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="card-subtitle mb-2 text-muted">Etape <?php echo $etape['rang']; ?>></h6>
                                    </div>
                                    <div class="card-body">
                                        <p class="card-text">Nom : <?php echo $etape['nomspectacle']; ?></p>
                                        <p class="card-text">Temps de marche : <?php echo $tempschemin; ?> minutes par le
                                            chemin
                                            <?php echo $etape['chemin']; ?></p>
                                        <p class="card-text">Attente :
                                            <?php echo date('i', strtotime($etape['dureeattente'])); ?>
                                            minutes</p>
                                        <?php if ($etape['Debutimmersif'] == '00:00:00') { ?>
                                            <p class="card-text">Début spectacle à <?php echo $etape['Horaire']; ?> durée
                                                <?php echo $etape['duree']; ?></p>
                                        <?php } else { ?>
                                            <p class="card-text">Spectacle immersif de <?php echo $etape['Debutimmersif']; ?> à
                                                <?php echo $etape['Horaire']; ?></p>
                                        <?php } ?>
                                    </div>
                                </div>
                                <?php
                                // Si c'est la dernière étape, calcul de la distance et du temps de marche jusqu'à la sortie
                                if ($etape['rang'] == $nbspectacles) {
                                    $dist = 0;
                                    $cheminarray = array();
                                    [$dist, $cheminarray] = calc_chemin($s, 0);
                                    $tempschemin = ceil($dist / $vitesse * 60);
                                ?>
                                    <p class="card-text">temps de marche vers la sortie <?php echo $tempschemin; ?> minutes par
                                        le
                                        chemin
                                        <?php echo implode(",", $cheminarray); ?></p>
                                    <p class="card-text">Heure fermeture parc <?php echo $planning['hfermeture']; ?></p>
                            <?php
                                }
                                echo "<br>";
                                $a = $s;
                            }
                            ?>
                        </div>
                    </div>
                </div>
            <?php
            }
            ?>
        </div>
    </div>


    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="http://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="./utils/js/toast/toastr.min.js"></script>
</body>

</html>