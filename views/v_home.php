<!DOCTYPE html>
<html lang="fr">

<head>
    <?php include('views/v_header.php'); ?>
    <title>Accueil</title>
</head>

<header>
    <?php include('views/v_navbar.php'); ?>
</header>

<body>

    <!-- VIDEO RESPONSIVE -->
    <div class="embed-responsive embed-responsive-16by9">
        <video class="embed-responsive-item" playsinline autoplay="autoplay" loop="loop" muted="muted">
            <source src="https://www.puydufou.com/france/sites/default/files/homepage/video/puy-du-fou-homepage-2023.mp4" type="video/mp4" data-fid="25730">
        </video>
    </div>

</body>

<footer>
    <?php include('views/v_footer.php'); ?>
</footer>

<!-- Include foot ressources -->
<?php include('views/v_footer_res.php'); ?>

</html>