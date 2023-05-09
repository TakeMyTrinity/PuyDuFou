<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Page de profil</title>
    <!-- Charger les fichiers CSS de Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Charger le fichier CSS personnalisé -->
    <link rel="stylesheet" href="style.css">
</head>

<body style="background-image: url('https://images.unsplash.com/photo-1541361906996-cadbfa49eb2a');">
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <img src="utils/images/logo.svg" class="img-fluid mb-3" alt="Logo Puy du Fou">
                        <h3 class="card-title">Mon profil</h3>
                        <p class="card-text">Informations de base sur vous.</p>
                        <form>
                            <div class="form-group">
                                <label for="inputNom">Nom :</label>
                                <input type="text" class="form-control" id="inputNom" name="nomvisiteur" value="<?= $laLigne['nomvisiteur']; ?>" disabled>
                            </div>
                            <div class="form-group">
                                <label for="inputAge">Prenom :</label>
                                <input type="text" class="form-control" id="inputAge" name="prenomvisiteur" value="<?= $laLigne['prenomvisiteur']; ?>" disabled>
                            </div>
                            <div class="form-group">
                                <label for="inputVille">Mail :</label>
                                <input type="text" class="form-control" id="inputVille" name="mailvisiteur" value="<?= $laLigne['mailvisiteur']; ?>" disabled>
                            </div>
                            <div class="form-group">
                                <label for="inputPays">Vitesse de marche :</label>
                                <input type="text" class="form-control" id="inputPays" name="vitessemarche" value="<?= intval($laLigne['vitessemarche']); ?>" disabled>
                            </div>
                            <a href="index.php?uc=profil&action=update" class="btn btn-primary">Modifier</a>
                        </form>
                    </div>
                    <div class="card-footer text-center">
                        <a href="index.php?uc=account&action=profile" class="btn btn-secondary"><i class="fa fa-arrow-left"></i>
                            Retour à mon profil</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Charger les fichiers JS de Bootstrap -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>