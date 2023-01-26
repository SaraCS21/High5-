<?php

    // Instalación de la Base de Datos
    try{
        $connection = create();
        $sql = file_get_contents("./static/database/db.sql");
        $connection->exec($sql);

    } catch (PDOException $error){
        echo $error->getMessage();
    }

    // Creaciones de valores por defecto
    Person::createAdmin();
    Post::createDefaultPost();

?>