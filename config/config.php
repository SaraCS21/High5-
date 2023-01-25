<?php

    // Datos de configuración para conectarnos a la Base de Datos
    return [
        "db" => [
            "host" => "localhost",
            "user" => "root",
            "pass" => "",
            "name" => "Foro",
            "options" => [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]
        ]
    ];
?>