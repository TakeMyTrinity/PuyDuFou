<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    require('views/v_header.php');
    ?>
    <title>Inscription</title>
</head>

<body>

    <div style="display: flex; justify-content: center; align-items: center; height: 100vh;">
        <section class="Form my-4 mx-auto">
            <div class="container">
                <div class="row no-gutters justify-content-center">
                    <div class="col-lg-5 d-none d-lg-block">
                        <img src="http://localhost/PuyDuFou/utils/images/connection.webp"
                            style="object-fit: cover; max-width: 100%; height: 100%; transform: scaleX(-1);">
                    </div>
                    <div class="col-lg-7 px-5 pt-5">
                        <img src="utils/images/logo.svg" class="img-connect py-3" alt="Logo" width="250" height="100">
                        <h4>Créer votre compte</h4>
                        <form action="index.php?uc=connection&action=confirmInscription" method="post">
                            <div class="form-row">
                                <div class="col-lg-7">
                                    <input type="text" name="nomS" placeholder="Nom" class="form-control my-3 p-4"
                                        required>
                                    <input type="text" name="prenomS" placeholder="Prénom" class="form-control my-3 p-4"
                                        required>
                                </div>
                                <div class="col-lg-7">
                                    <input type="mail" name="mailS" placeholder="Adresse Mail"
                                        class="form-control my-3 p-4" required>
                                    <input type="password" name="passwordS" placeholder="Mot de passe"
                                        class="form-control my-3 p-4" required>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-lg-7">
                                    <input name="create" type="submit" class="btn1 mt-3 mb-5"></input>
                                    <button class="btn-danger" type="reset">Annuler</button>
                                </div>
                            </div>
                            <p>tu veux revenir à l'accueil ?<a href="index.php">Clique ici :</a></p>
                            <p>Tu es déjà un visiteur ?<a href="index.php?uc=connection&action=seConnecter">Connecte toi
                                    ici
                                    :</a></p>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>

</body>

<?php
include('views/v_footer_res.php');
?>

</html>