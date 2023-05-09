<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Spectacles</title>
    <!-- Lien vers Bootstrap 5 CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.0.0-beta3/css/bootstrap.min.css"
        integrity="sha512-+JZJvJZzJzJzJzJzJzJzJzJzJzJzJzJzJzJzJzJzJzJzJzJzJzJzJzJzJzJzJzJzJzJzJzJzJzJw=="
        crossorigin="anonymous" />
    <style>
    .spectacle {
        margin: 10px;
        border: 1px solid #dee2e6;
        border-radius: 5px;
        overflow: hidden;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .spectacle img {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }

    .spectacle h3 {
        margin: 10px;
        font-size: 1.2rem;
        font-weight: bold;
    }

    .spectacle .buttons {
        display: flex;
        justify-content: center;
        margin-bottom: 10px;
    }

    .spectacle .buttons a {
        margin: 0 5px;
    }

    .spectacle .buttons button {
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .spectacle .buttons .modifier {
        background-color: #007bff;
        color: white;
    }

    .spectacle .buttons .supprimer {
        background-color: #dc3545;
        color: white;
    }

    /* Style pour les boutons */
    .btn-menu {
        margin: 10px;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        background-color: #007bff;
        color: white;
    }
    </style>
</head>

<body>
    <div class="container">
        <div class="row">
            <?php
            foreach ($lesSpectacles as $spectacle) {
                echo '<div class="col-md-4 col-sm-6">';
                echo '<div class="spectacle">';
                echo '<a href="modifier_spectacle.php?id=' . $spectacle['Id_Spectacle'] . '">';
                echo '<img src="' . $spectacle['image'] . '" alt="' . $spectacle['nomspectacle'] . '">';
                echo '<h3>' . $spectacle['nomspectacle'] . '</h3>';
                echo '</a>';
                echo '<div class="buttons">';
                echo '<a href="index.php?uc=admin&action=updateSpectacle&id=' . $spectacle['Id_Spectacle'] . '"><button class="modifier">Modifier</button></a>';
                echo '<a href="index.php?uc=admin&action=deleteSpectacle&id=' . $spectacle['Id_Spectacle'] . '"><button class="supprimer">Supprimer</button></a>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
            }
            ?>
        </div>

        <!-- Boutons pour retourner au menu de base -->
        <div class="row justify-content-center">
            <a href="http://localhost/PuyDuFou/index.php?uc=account&action=profile"><button class="btn-menu">Retourner
                    au menu de base</button></a>
        </div>
    </div>

    <!-- Lien vers Bootstrap 5 JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.0.0-beta3/js/bootstrap.bundle.min.js"
        integrity="sha512-+JZJvJZzJzJzJzJzJzJzJzJzJzJzJzJzJzJzJzJzJzJzJzJzJzJzJzJzJzJzJzJzJzJzJzJzJzJw=="
        crossorigin="anonymous"></script>
</body>

</html>