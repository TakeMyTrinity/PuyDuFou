<?php
include('view/v_head.php');
?>

<body>
    <?php
    include('view/v_navbar.php');
    ?>
    <!-- VIDEO RESPONSIVE -->
    <div class="embed-responsive embed-responsive-16by9">
        <video class="embed-responsive-item" playsinline autoplay="autoplay" loop="loop" muted="muted">
            <source
                src="https://www.puydufou.com/france/sites/default/files/homepage/video/puy-du-fou-homepage-2023.mp4"
                type="video/mp4" data-fid="25730">
        </video>
    </div>
    <?php
    $type = "success";
    $message = "Vous êtes maintenant incrit !";
    include('view/v_toast.php');
    include('view/v_footer.php');
    include('view/v_foot.php');
    ?>
</body>