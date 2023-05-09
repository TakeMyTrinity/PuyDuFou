<head>
    <meta charset="UTF-8">
    <title>Page de profil</title>
    <!-- Charger les fichiers CSS de Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Charger les fichiers JS de Bootstrap -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Charger le fichier CSS personnalisé -->
    <link rel="stylesheet" href="style.css">
</head>

<body style="background-image: url('https://images.unsplash.com/photo-1541361906996-cadbfa49eb2a');">
    <div class="container my-5">
        <div class="row">
            <div class="col-md-4 offset-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <img src="utils/images/logo.svg" class="img-fluid mb-3" alt="Logo Puy du Fou">
                        <h3 class="card-title">Mofidier le spectacle</h3>
                        <p class="card-text">Entrez les nouvelles informations</p>
                        <form method="post" action="index.php?uc=admin&action=confirmUpdate&id=<?= $id ?>">
                            <div class="form-group">
                                <label for="inputNom">Nom :</label>
                                <input type="text" class="form-control" id="inputNom" name="nomSpectacle" required>
                            </div>
                            <div class="form-group">
                                <label for="inputAge">Durée :</label>
                                <input type="text" class="form-control" id="inputAge" name="dureeSpectacle" required>
                            </div>
                            <div class="form-group">
                                <label for="inputVille">Durée attente :</label>
                                <input type="text" class="form-control" id="inputVille" name="dureeAttenteSpectacle" required>
                            </div>
                            <div class="form-group">
                                <label for="inputPays">Nombre de place :</label>
                                <input type="text" class="form-control" id="inputPays" name="nbrPlaceSpectacle" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Enregistrer les
                                modifications</button>
                        </form>
                    </div>
                    <div class="card-footer text-center">
                        <a href="index.php?uc=account&action=profile" class="btn btn-secondary"><i class="fa fa-arrow-left"></i>
                            Retour à mon
                            profil</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>