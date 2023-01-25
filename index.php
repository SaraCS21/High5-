<!-- Llamada al header de la web -->
<?php require "./parts/header.php" ?>

<!-- Instalación de la Base de Datos y algunos datos predeterminados -->
<?php require "./config/install.php" ?>

<?php 
    // Definimos la ruta a la que queremos movernos dentro de la web
    $routes = require "./config/routes.php";

    // En caso de no tener una url a la que ir, o que el 
    // usuario no haya iniciado sesión...
    if (!isset($_REQUEST["url"]) || !isset($_SESSION["idUser"])){
        require "./config/selectPage.php";

    // En caso de tener una url a la que movernos...
    } else {
        require $routes["routes"][$_REQUEST["url"]];
    }
?>

<!-- Llamada al footer de la web -->
<?php require "./parts/footer.php" ?> 