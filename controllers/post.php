<?php

    /**
        * Creación de un post por defecto
        *
        * Esta función se conecta a la Base de Datos para insertar
        * los valores de un nuevo post escrito por el administrador
        *
        * Esta función solo se ejecuta siempre para tener, 
        * al menos, un post en nuestra web
    */
    function createDefaultPost(){
        try {
            $connection = connect();

            $querySQL = "SELECT * FROM post";
            $sentenceQuery = $connection->prepare($querySQL);
            $sentenceQuery->execute();
    
            $values = $sentenceQuery->fetchAll();
    
            // Comprobamos que no existan posts
            $create = ($sentenceQuery->rowCount() >= 1) ? false : true;

            if ($create){
                $post = [
                    "content" => "Como Amazon no se digna ni a dar una estimación, seguimiento ni nada me he decidido a ver si interesa q entre todos podamos ver si hay algún criterio y podamos estimar cuando nos la van a mandar.",
                    "title" => "Seguimiento reservas",
                    "theme" => "Videojuegos",
                    "publicationDate" => date('Y-m-d'), // Fecha actual
                    "idUser" => 1,
                    "numViews" => 0
                ];

                $insertSQL = "INSERT INTO post (content, title, theme, publicationDate, idUser, numViews)";
                $insertSQL .= "VALUES (:" . implode(", :", array_keys($post)) . ")";

                $sentenceInsert = $connection->prepare($insertSQL);
                $sentenceInsert->execute($post);
            }
            
        } catch(PDOException $error) {
            $result["error"] = true;
            $result["mensaje"] = $error->getMessage();
        }
    }

    /**
        * Creación de un post
        *
        * Esta función se conecta a la Base de Datos para insertar
        * los valores de un nuevo post escrito por el usuario cuya
        * id está guardada en la sesión
        *
        * @param por $_POST -> valores del post [content, title, theme]
        * @param por $_SESSION -> id del usuario actual [idUser]
        *
        * @global $_REQUEST, $_SESSION
    */
    function createPost(){
        try {
            $connection = connect();

            $post = [
                "content" => $_REQUEST["content"],
                "title" => $_REQUEST["title"],
                "theme" => $_REQUEST["theme"],
                "publicationDate" => date('Y-m-d'), // Fecha actual
                "idUser" => $_SESSION["idUser"], // Usuario con sesión activa
                "numViews" => 0 // Número de vistas al principio
            ];

            $querySQL = "INSERT INTO post (content, title, theme, publicationDate, idUser, numViews)";
            $querySQL .= "VALUES (:" . implode(", :", array_keys($post)) . ")";

            $sentence = $connection->prepare($querySQL);
            $sentence->execute($post);

        } catch(PDOException $error) {
            $result["error"] = true;
            $result["mensaje"] = $error->getMessage();
        }
    }

    /**
        * Actualización de los datos de un post
        *
        * Esta función se conecta a la Base de Datos para buscar 
        * un post por su "id" y actualizar sus datos
        *
        * @param por $_POST -> valores del post [idPost, content, title, theme, publicationDate e idUser]
        *
        * @global $_REQUEST
    */
    function updatePost(){
        try {
            $connection = connect();

            $querySQL = $connection->prepare
            ("UPDATE post SET content = :content, title = :title,
            theme = :theme, publicationDate = :publicationDate, idUser = :idUser WHERE id = :idPost");

            $querySQL->bindValue(':idPost', $_REQUEST["idPost"], PDO::PARAM_INT);
            $querySQL->bindValue(':content', $_REQUEST["content"], PDO::PARAM_STR);
            $querySQL->bindValue(':title', $_REQUEST["title"], PDO::PARAM_STR);
            $querySQL->bindValue(':theme', $_REQUEST["theme"], PDO::PARAM_STR);
            $querySQL->bindValue(':publicationDate', $_REQUEST["publicationDate"], PDO::PARAM_STR);
            $querySQL->bindValue(':idUser', $_REQUEST["idUser"], PDO::PARAM_INT);

            $querySQL->execute();

        } catch(PDOException $error) {
            $result["error"] = true;
            $result["mensaje"] = $error->getMessage();
        }
    }

    /**
        * Borrado de un post
        *
        * Esta función se conecta a la Base de Datos para buscar 
        * a un post por su "id" y eliminarlo 
        *
        * @param por $_POST -> el botón de tipo submit de "delete" o de "deletePost"
        *
        * @global $_REQUEST
    */
    function deletePost(){
        try {
            $connection = connect();

            $sql = "DELETE FROM post WHERE id = :idPost";

            $querySQL = $connection->prepare($sql);

            $idPost = (isset($_REQUEST["delete"])) ? $_REQUEST["delete"] : $_REQUEST["deletePost"];
            $querySQL->bindValue(':idPost', $idPost, PDO::PARAM_INT);

            $querySQL->execute();

        } catch(PDOException $error) {
            $result["error"] = true;
            $result["mensaje"] = $error->getMessage();
        }
    }

    /**
        * Selección de los datos de un post
        *
        * Esta función se conecta a la Base de Datos para buscar 
        * a un post por su "id" y recoger todos sus valores.
        *
        * @param int @idPost -> clave del post que queramos buscar
        *
        * @return array -> devuelve un array con los valores del post seleccionado
    */
    function selectPost($idPost){
        try {
            $connection = connect();

            $sql = "SELECT * FROM post WHERE id = :idPost";

            $querySQL = $connection->prepare($sql);
            $querySQL->bindParam(':idPost', $idPost, PDO::PARAM_INT);

            $querySQL->execute();

            $postValues = $querySQL->fetchAll();
            $continue = ($postValues && $querySQL->rowCount()>0) ? true : false;

            return ($continue) ? $postValues : false;

        } catch(PDOException $error) {
            $result["error"] = true;
            $result["mensaje"] = $error->getMessage();
        }
    }

    function getViews($idPost){
        try {
            $connection = connect();

            $sql = "SELECT numViews FROM post WHERE id = :idPost";

            $querySQL = $connection->prepare($sql);
            $querySQL->bindParam(':idPost', $idPost, PDO::PARAM_INT);

            $querySQL->execute();

            $views = $querySQL->fetchAll();
            $continue = ($views && $querySQL->rowCount()>0) ? true : false;

            return $views[0][0];

        } catch(PDOException $error) {
            $result["error"] = true;
            $result["mensaje"] = $error->getMessage();
        }
    }

    function incrementViews($idPost){
        try {
            $connection = connect();

            $querySQL = $connection->prepare
            ("UPDATE post SET numViews = :numViews  WHERE id = :idPost");

            $querySQL->bindValue(':idPost', $idPost, PDO::PARAM_INT);

            $numViews = getViews($idPost) + 1;
            $querySQL->bindValue(':numViews', $numViews, PDO::PARAM_INT);

            $querySQL->execute();

        } catch(PDOException $error) {
            $result["error"] = true;
            $result["mensaje"] = $error->getMessage();
        }
    }
?>