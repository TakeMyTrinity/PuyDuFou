<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Charger les fichiers CSS de Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Charger le fichier CSS personnalisé -->
    <link rel="stylesheet" href="style.css">
    <title> <?php echo isset($leSpectacle['nomspectacle']) ? $leSpectacle['nomspectacle'] : 'Spectacle'; ?> </title>
</head>

<body>
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <img src="utils/images/logo.svg" class="img-fluid mb-3" alt="Logo Puy du Fou">
                        <h3 class="card-title">Information du spectacle</h3>
                        <p class="card-text">Informations de base le spectacle <br><?= $leSpectacle['nomspectacle']; ?>.
                        </p>
                        <form>
                            <div class="form-group">
                                <label for="inputNom">Nom :</label>
                                <input type="text" class="form-control" id="inputNom" name="nomvisiteur" value="<?= $leSpectacle['nomspectacle']; ?>" disabled>
                            </div>
                            <div class="form-group">
                                <label for="inputAge">Durée :</label>
                                <input type="text" class="form-control" id="inputAge" name="prenomvisiteur" value="<?= $leSpectacle['duree']; ?>" disabled>
                            </div>
                            <div class="form-group">
                                <label for="inputVille">Durée d'attente :</label>
                                <input type="text" class="form-control" id="inputVille" name="mailvisiteur" value="<?= $leSpectacle['dureeattente']; ?>" disabled>
                            </div>
                            <div class="form-group">
                                <label for="inputPays">Nombre de place :</label>
                                <input type="text" class="form-control" id="inputPays" name="vitessemarche" value="<?= intval($leSpectacle['nbrplace']); ?>" disabled>
                            </div>
                            <a href="index.php?uc=admin&action=update&id=<?= $leSpectacle['Id_Spectacle']; ?>" class="btn btn-primary">Modifier</a>
                        </form>
                    </div>
                    <div class="card-footer text-center">
                        <a href="index.php?uc=admin&action=spectacle" class="btn btn-secondary"><i class="fa fa-arrow-left"></i>
                            Retour à la page des spectacles</a>
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