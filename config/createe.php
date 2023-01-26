<?php

    $dotenv=Dotenv\Dotenv:: createImmutable(__DIR__ . "\..");
    $dotenv->safeLoad();

    /**
        * Crea la Base de Datos y devuelve su conexión
        *
        * Esta función se encarga de crear la base de
        * datos, con los parámetros guardados en config.php,
        * y realizar un conexión con esta.
        *
        * @param por $_ENV -> valores para la conexión con la base de datos [host, user, password]
        *
        * @global $_ENV
        *
        * @return object $connection -> Objeto de la conexión
    */
    function create(){
        // $config = require "./config/config.php";
        // [
        //     "host" => $host,
        //     "user" => $user,
        //     "pass" => $pass,
        //     "name" => $name,
        //     "options" => $options
        // ] = $config["db"];

        // $connection = new PDO("mysql:host=$host", $user, $pass, $options);
        // return $connection;

        $options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];
        $connection = new PDO("mysql:host=" . $_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASS'], $options);
        return $connection;
    }

    /**
        * Devuelve una conexión con la Base de Datos
        *
        * Esta función se encarga de conectarse la base de
        * datos, con los parámetros guardados en config.php.
        *
        * @param por $_ENV -> valores para la conexión con la base de datos [host, dbname, user, password]
        *
        * @global $_ENV
        *
        * @return object $connection -> Objeto de la conexión
    */
    function connect(){
        // $config = require "./config/config.php";
        // [
        //     "host" => $host,
        //     "user" => $user,
        //     "pass" => $pass,
        //     "name" => $name,
        //     "options" => $options
        // ] = $config["db"];

        // $connection = new PDO("mysql:host=$host;dbname=$name", $user, $pass, $options);
        // return $connection;

        $options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];
        $connection = new PDO("mysql:host=" . $_ENV['DB_HOST'] . ";dbname=" . $_ENV['DB_DB'], $_ENV['DB_USER'], $_ENV['DB_PASS'], $options);
        return $connection;
    }

?>