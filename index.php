<!-- Llamamos al autoload -->
<?php require __DIR__ . "./vendor/autoload.php"; ?> 

<!-- Llamada al header de la web -->
<?php require "./parts/header.php" ?>

<!-- Instalación de la Base de Datos y algunos datos predeterminados -->
<?php 
    use Config\ConnectDB;
    use Config\SelectPage;

    ConnectDB::install();
?>

<?php
    // Definimos la ruta a la que queremos movernos dentro de la web
    $routes = require "./static/constant/routes.php";

    // En caso de no tener una url a la que ir, o que el usuario no haya iniciado sesión...
    if (!isset($_REQUEST["url"]) || !isset($_SESSION["idUser"])){
        SelectPage::selectPage();

    // En caso de tener una url a la que movernos o que ingresemos una url que no existe...
    } else {
        require array_key_exists($_REQUEST["url"], $routes["routes"]) ? $routes["routes"][$_REQUEST["url"]] : $routes["routes"]["landing"] ; 
    }
?>

<!-- Llamada al footer de la web -->
<?php require "./parts/footer.php" ?>