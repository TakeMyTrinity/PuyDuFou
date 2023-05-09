<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    include('views/v_header.php');
    ?>
    <title>Profile</title>
</head>

<body>

    <script src="https://kit.fontawesome.com/b99e675b6e.js"></script>

    <section class="profil">
        <div class="sidebar">
            <div class="logo-details">
                <div class="logo_name">Votre Profil</div>
                <i class="fas fa-stream" id="btn"></i>
            </div>
            <ul class="nav-list">
                <li>
                    <a href="index.php">
                        <i class="fas fa-home"></i>
                        <span class="links_name">Accueil</span>
                    </a>
                    <span class="tooltip">Accueil</span>
                </li>
                <li>
                    <a href="#">
                        <i class="fas fa-sliders-h"></i>
                        <span class="links_name">Dashboard</span>
                    </a>
                    <span class="tooltip">Dashboard</span>
                </li>
                <li>
                    <a href="#">
                        <i class="fas fa-table"></i>
                        <span class="links_name">Programme</span>
                    </a>
                    <span class="tooltip">Programme</span>
                </li>
                <li>
                    <a href="index.php?uc=account&action=profilview">
                        <i class="fas fa-user-alt"></i>
                        <span class="links_name">Profil</span>
                    </a>
                    <span class="tooltip">Profil</span>
                </li>
                <li class="profile">
                    <div class="profile-details">
                        <!--<img src="profile.jpg" alt="profileImg">-->
                        <div class="name_job">
                            <div class="name"><?= $laLigne['nomvisiteur'] ?></div>
                            <div class="job"><?= $laLigne['mailvisiteur'] ?></div>
                        </div>
                    </div>
                    <a href="index.php?uc=connection&action=deconnexion"><i class="fas fa-sign-out-alt" id="log_out"></i></a>
                </li>
            </ul>
        </div>
        <section class="home-section">
            <div class="text">Accueil Administrateur</div>

            <div class="card-deck">
                <div class="card border border-dark">
                    <img class="card-img-top" style="height: 300px; width: 100%" src="http://localhost/PuyDuFou/utils/images/profile-card1.jpg" alt="Card image cap">
                    <div class="card-body">
                        <h5 class="card-title">Gérez les spectacles</h5>
                        <a href="index.php?uc=admin&action=spectacle" class="btn btn-outline-primary">Allez-y</a>
                    </div>
                </div>
                <div class="card border border-dark">
                    <img class="card-img-top" style="height: 300px; width: 100%" src="http://localhost/PuyDuFou/utils/images/profile-card2.jpg" alt="Card image cap">
                    <div class="card-body">
                        <h5 class="card-title">Créer votre programme</h5>
                        <a href="index.php?uc=parcours&action=createParcours" class="btn btn-outline-primary">allez-y</a>
                    </div>
                </div>
            </div>
        </section>

        <script>
            let sidebar = document.querySelector(".sidebar");
            let closeBtn = document.querySelector("#btn");
            let searchBtn = document.querySelector(".bx-search");

            closeBtn.addEventListener("click", () => {
                sidebar.classList.toggle("open");
                menuBtnChange(); //calling the function(optional)
            });

            searchBtn.addEventListener("click", () => { // Sidebar open when you click on the search iocn
                sidebar.classList.toggle("open");
                menuBtnChange(); //calling the function(optional)
            });

            // following are the code to change sidebar button(optional)
            function menuBtnChange() {
                if (sidebar.classList.contains("open")) {
                    closeBtn.classList.replace("bx-menu", "bx-menu-alt-right"); //replacing the iocns class
                } else {
                    closeBtn.classList.replace("bx-menu-alt-right", "bx-menu"); //replacing the iocns class
                }
            }
        </script>
    </section>

</body>

<?php
include('views/v_footer_res.php');
?>

</html>