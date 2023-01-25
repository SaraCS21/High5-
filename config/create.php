<?php

    /**
        * Crea la Base de Datos y devuelve su conexión
        *
        * Esta función se encarga de crear la base de
        * datos, con los parámetros guardados en config.php,
        * y realizar un conexión con esta.
        *
        * @return $connection
    */
    function create(){
        $config = require "./config/config.php";
        [
            "host" => $host,
            "user" => $user,
            "pass" => $pass,
            "name" => $name,
            "options" => $options
        ] = $config["db"];

        $connection = new PDO("mysql:host=$host", $user, $pass, $options);
        return $connection;
    }

    /**
        * Devuelve una conexión con la Base de Datos
        *
        * Esta función se encarga de conectarse la base de
        * datos, con los parámetros guardados en config.php.
        *
        * @return $connection
    */
    function connect(){
        $config = require "./config/config.php";
        [
            "host" => $host,
            "user" => $user,
            "pass" => $pass,
            "name" => $name,
            "options" => $options
        ] = $config["db"];

        $connection = new PDO("mysql:host=$host;dbname=$name", $user, $pass, $options);
        return $connection;
    }

?>