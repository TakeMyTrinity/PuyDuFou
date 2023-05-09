<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./utils/css/process/process.css">
    <title>Maintenance page</title>
</head>

<body>

    <img src="utils/images/logo.svg" alt="Logo">
    <h1>Veuillez attendre que vos Parcours ce genère</h1>
    <div class="loader"></div>

</body>

<script>
    setTimeout(function() {
        window.location.href = "index.php?uc=parcours&action=parcoursGenerate";
    }, <?php echo $delay; ?>);
</script>

</html>