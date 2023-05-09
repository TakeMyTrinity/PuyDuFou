<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    require('views/v_header.php');
    ?>
    <title>Connection</title>
</head>

<body>

    <div style="display: flex; justify-content: center; align-items: center; height: 100vh;">
        <section class="Form my-4 mx-5" style="margin: auto;">
            <div class="container">
                <div class="row no-gutters">
                    <div class="col-lg-5 d-none d-lg-block">
                        <img src="http://localhost/PuyDuFou/utils/images/connection.webp"
                            alt="image impossible a chargé"
                            style="transform: scaleX(-1); object-fit: cover; max-width: 100%; height: 100%;">
                    </div>
                    <div class="col-lg-7 px-5 pt-5">
                        <img src="utils/images/logo.svg" class="img-connect py-3" alt="Logo" width="250" height="100">
                        <h4>Connectez-vous à votre compte</h4>
                        <form action="index.php?uc=connection&action=confirmConnection" method="post">
                            <div class="form-row">
                                <div class="col-lg-7">
                                    <input type="mail" name="username" placeholder="Adresse Mail"
                                        class="form-control my-3 p-4" required>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-lg-7">
                                    <input type="password" name="password" placeholder="Mot de passe"
                                        class="form-control my-3 p-4" required>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-lg-7">
                                    <button type="submit" class="btn1 mt-3 mb-5">Connection</button>
                                    <button class="btn-danger" type="reset">Annuler</button>
                                </div>
                            </div>
                            <a href="#">Mot de passe oublié ?</a>
                            <p>Tu n'as pas de compte ? <a href="index.php?uc=connection&action=inscription">Inscrit toi
                                    ici :</a></p>
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