<?php

    // En caso de que no exista un usuario con sesión iniciada...
    if (!isset($_SESSION["idUser"]) || $_SESSION["idUser"] === 0){
        $url = "login";
        $_SESSION["idUser"] = 0;

    // En caso de tener un usuario administrador
    } else if ($_SESSION["idUser"] === 1) {
        $url = "adminPanel";

    // En caso de tener un usuario normal
    } else {
        $url = "landing";
    }

    // Modificamos la url a la que nos vamos a dirigir
    Header("Location: ./index.php?url=$url");

?>