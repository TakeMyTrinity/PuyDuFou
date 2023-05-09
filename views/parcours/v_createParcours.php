<!DOCTYPE html>
<html lang="en">

<head> <?php include('views/v_header.php'); ?> <title>Choisissez les spectacles</title>
    <!-- Inclure les fichiers CSS de Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet" />
    <!-- Google Font Link for Icons -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">
    <!-- Inclure le fichier CSS personnalisé -->
    <link rel="stylesheet" href="./utils/css/parcoursEtape.css">
    <link rel="stylesheet" href="./utils/css/toast/toastr.css">
</head>

<body>
    <div class="container">

        <!-- Etape 1 : Choix des spectacles -->
        <div class="etape active" id="etape1">
            <h1>Choisissez les spectacles que vous souhaitez voir :</h1> <br>
            <div class="row"> <?php foreach ($spectacles as $spectacle) { ?> <div class="col-md-4">
                    <div class="card"> <img class="card-img-top" src="<?php echo $spectacle['image']; ?>"
                            alt="<?php echo $spectacle['nomspectacle']; ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $spectacle['nomspectacle']; ?></h5>
                            <div class="form-check"> <input class="form-check-input" type="checkbox"
                                    value="<?php echo $spectacle['Id_Spectacle']; ?>"
                                    id="<?php echo $spectacle['Id_Spectacle']; ?>"> <label class="form-check-label"
                                    for="<?php echo $spectacle['Id_Spectacle']; ?>">Sélectionner</label> </div>
                        </div>
                    </div>
                </div> <?php } ?> </div> <button id="btn-suivant" onclick="nextEtape()">Suivant</button>
        </div>

        <!-- Etape 2 : Choix de la date -->
        <div class="etape" id="etape2">
            <h1>Choisissez la date de votre visite :</h1>
            <br>
            <div class="wrapper">
                <header>
                    <p class="current-date"></p>
                    <div class="icons">
                        <span id="prev" class="material-symbols-rounded">chevron_left</span>
                        <span id="next" class="material-symbols-rounded">chevron_right</span>
                    </div>
                </header>
                <div class="calendar">
                    <ul class="weeks">
                        <li>Dim</li>
                        <li>Lun</li>
                        <li>Mar</li>
                        <li>Mer</li>
                        <li>Jeu</li>
                        <li>Ven</li>
                        <li>Sam</li>
                    </ul>
                    <ul class="days"></ul>
                </div>
            </div>
            <br>
            <button id="btn-retour" onclick="prevEtape()">Retour</button>
            <button id="btn-valider" onclick="sendCheckedValues(formattedDate)">Valider</button>
        </div>

        <!-- Scripts -->
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
        <script src="http://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="./utils/js/toast/toastr.min.js"></script>
        <script>
        // Sélection des éléments HTML nécessaires
        const daysTag = document.querySelector(".days"),
            currentDate = document.querySelector(".current-date"),
            prevNextIcon = document.querySelectorAll(".icons span");

        // Initialisation des variables pour l'année et le mois actuels
        let date = new Date(),
            currYear = date.getFullYear(),
            currMonth = date.getMonth();

        // Tableau des noms de mois
        const months = ["Janvier", "Fevrier", "Mars", "Avril", "Mai", "Juin", "Juillet",
            "Aout", "Septembre", "Octobre", "Novembre", "Decembre"
        ];

        // Fonction pour vérifier si une date est sauvegardée dans la base de données
        const checkSavedDate = (day, month, year) => {
            const savedDates = <?php echo json_encode($databaseController->getLesDates()); ?>;
            const timezoneOffset = new Date().getTimezoneOffset() * 60 *
                1000; // Convertir le décalage horaire en millisecondes
            const date = new Date(year, month, day);
            const formattedDate = new Date(date.getTime() - timezoneOffset).toISOString().slice(0,
                10); // Ajuster la date en fonction du décalage horaire
            return savedDates.some(savedDate => savedDate.Dateparc === formattedDate);
        };

        // Fonction pour générer le calendrier
        const renderCalendar = () => {
            // Calcul des informations nécessaires pour le mois en cours
            let firstDayofMonth = new Date(currYear, currMonth, 1).getDay(),
                lastDateofMonth = new Date(currYear, currMonth + 1, 0).getDate(),
                lastDayofMonth = new Date(currYear, currMonth, lastDateofMonth).getDay(),
                lastDateofLastMonth = new Date(currYear, currMonth, 0).getDate();
            let liTag = "";
            // Génération des éléments HTML pour les jours du mois en cours
            for (let i = firstDayofMonth; i > 0; i--) {
                liTag += `<li class="inactive">${lastDateofLastMonth - i + 1}</li>`;
            }
            for (let i = firstDayofMonth; i < lastDateofMonth + firstDayofMonth; i++) {
                const date = new Date(currYear, currMonth, i - firstDayofMonth + 1);
                const formattedDate = date.toISOString().slice(0, 10);
                let isSavedDate = checkSavedDate(i - firstDayofMonth + 1, currMonth, currYear);
                liTag +=
                    `<li class="${isSavedDate ? ' savedDate' : ' notSavedDate'}" data-date="${formattedDate}">${i - firstDayofMonth + 1}</li>`;
            }
            for (let i = lastDayofMonth; i < 6; i++) {
                liTag += `<li class="inactive">${i - lastDayofMonth + 1}</li>`;
            }

            // Affichage du calendrier dans la page HTML
            currentDate.innerText = `${months[currMonth]} ${currYear}`;
            daysTag.innerHTML = liTag;
        };

        // Appel initial de la fonction pour générer le calendrier
        renderCalendar();

        // Ajout d'événements aux icônes "précédent" et "suivant"
        prevNextIcon.forEach(icon => {
            icon.addEventListener("click", () => {
                // Mise à jour de l'année et du mois actuels en fonction de l'icône cliquée
                currMonth = icon.id === "prev" ? currMonth - 1 : currMonth + 1;
                if (currMonth < 0 || currMonth >
                    11) {
                    date = new Date(currYear, currMonth, new Date().getDate());
                    currYear = date.getFullYear();
                    currMonth = date.getMonth();
                } else {
                    date = new Date();
                }
                // Génération du calendrier mis à jour
                renderCalendar();
                // Affichage de la date sélectionnée
                showSelectedDate();
            });
        });

        // Variable globale pour stocker la date sélectionnée
        let selectedDate;

        // Fonction pour afficher la date sélectionnée
        const showSelectedDate = () => {
            const savedDateTags = document.querySelectorAll(".days li.savedDate");
            savedDateTags.forEach(dateTag => {
                dateTag.addEventListener("click", () => {
                    const timezoneOffset = new Date().getTimezoneOffset() * 60 * 1000;
                    selectedDate = new Date(dateTag.dataset.date);
                    selectedDate.setTime(selectedDate.getTime() - timezoneOffset);
                    selectedDate.setDate(selectedDate.getDate() + 1); // Add 1 to the day value
                    const formattedDate = selectedDate.toISOString().slice(0, 10);
                    toastr.success(`Date selectionner : ${formattedDate}`);
                });
            });
        };
        showSelectedDate();

        // Fonction pour envoyer les valeurs des cases cochées vers une autre page
        function sendCheckedValues() {
            var checkboxes = document.querySelectorAll('input[type="checkbox"]:checked');
            var values = [];
            for (var i = 0; i < checkboxes.length; i++) {
                values.push(checkboxes[i].value);
            }

            // Vérifier si une date a été sélectionnée
            if (typeof selectedDate === "undefined") {
                toastr.error("Veuillez sélectionner une date.");
                return;
            }

            const formattedDate = selectedDate.toISOString().slice(0, 10);

            window.location.href = "index.php?uc=parcours&action=ParcoursProcess&values=" + values + "&date=" +
                formattedDate;
        }

        // Ajout d'un événement au bouton de validation
        const validationButton = document.getElementById("btn-valider");
        validationButton.addEventListener("click", sendCheckedValues);
        </script>
        <script src="./utils/js/parcoursEtape.js"></script>
</body>

</html>