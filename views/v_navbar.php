<header>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">
            <img src="utils/images/logo.svg" alt="Logo" width="150" height="100">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link text-dark" href="index.php?uc=accueil">Accueil <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark" href="#">À propos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark" href="#">Contact</a>
                </li>
            </ul>

            <ul class="navbar-nav ml-auto mg-3">
                <li class="nav-item">
                    <a href="#">Le Grand Tour</a>
                </li>
                <li class="nav-item">
                    <a href="#">Nos autres offres</a>
                </li>

                <?php if ($fsession->isConnected()) { ?>
                    <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-user"></i></a>
                        <ul class="dropdown-menu">
                            <li><a href="index.php?uc=account&action=profile">Profil</a></li>
                            <li><a href="index.php?uc=connection&action=deconnexion">Deconnection</a></li>
                        </ul>
                    </li>
            </ul>
        <?php } else { ?>
            <li class="nav-item">
                <a href="index.php?uc=connection&action=seConnecter"><i class="fa fa-user"></i></a>
            </li>
        <?php } ?>
    </nav>
</header>