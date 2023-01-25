<?php

    /**
        * Creación de comentarios en post
        *
        * Esta función se conecta a la Base de Datos para insertar
        * los valores de un nuevo comentario, pasados por un formulario,
        * en su respectiva tabla.
        *
        * @param por $_POST -> algunos valores del comentario [content, idPost, idUser]
        *
        * @global $_REQUEST
    */
    function createComent(){
        try {
            $connection = connect();

            $coment = [
                "idUser" => $_SESSION["idUser"],
                "idPost" => $_REQUEST["idPost"],
                "content" => $_REQUEST["content"],
                "publicationDate" => date('Y-m-d') // Fecha actual
            ];

            $querySQL = "INSERT INTO coment (idUser, idPost, content, publicationDate)";
            $querySQL .= "VALUES (:" . implode(", :", array_keys($coment)) . ")";

            $sentence = $connection->prepare($querySQL);
            $sentence->execute($coment);

        } catch(PDOException $error) {
            $result["error"] = true;
            $result["mensaje"] = $error->getMessage();
        }
    }

        /**
        * Actualización de los datos de un comentario
        *
        * Esta función se conecta a la Base de Datos para buscar 
        * un comentario por su "id" y actualizar sus datos
        *
        * @param por $_POST -> valores del comentario [id, content, publicationDate, idPost, idUser]
        *
        * @global $_REQUEST
    */
    function updateComent(){
        try {

            $connection = connect();

            $querySQL = $connection->prepare
            ("UPDATE coment SET content = :content, idPost = :idPost,
            publicationDate = :publicationDate, idUser = :idUser WHERE id = :idComent");

            $querySQL->bindValue(':idComent', $_REQUEST["id"], PDO::PARAM_INT);
            $querySQL->bindValue(':content', $_REQUEST["content"], PDO::PARAM_STR);
            $querySQL->bindValue(':publicationDate', $_REQUEST["publicationDate"], PDO::PARAM_STR);
            $querySQL->bindValue(':idPost', $_REQUEST["idPost"], PDO::PARAM_INT);
            $querySQL->bindValue(':idUser', $_REQUEST["idUser"], PDO::PARAM_INT);

            $querySQL->execute();

        } catch(PDOException $error) {
            $result["error"] = true;
            $result["mensaje"] = $error->getMessage();
        }
    }

    /**
        * Borrado de un comentario
        *
        * Esta función se conecta a la Base de Datos para buscar 
        * a un comentario por su "id" y eliminarlo 
        *
        * @param por $_POST -> id del comentario [id]
        *
        * @global $_REQUEST
    */
    function deleteComent(){
        try {
            $connection = connect();

            $sql = "DELETE FROM coment WHERE id = :idComent";

            $querySQL = $connection->prepare($sql);
            $querySQL->bindValue(':idComent', $_REQUEST["id"], PDO::PARAM_INT);

            $querySQL->execute();

        } catch(PDOException $error) {
            $result["error"] = true;
            $result["mensaje"] = $error->getMessage();
        }
    }

    /**
        * Selección de comentarios
        *
        * Esta función se conecta a la Base de Datos para buscar 
        * comentarios por el "idPost" y devolver sus datos
        *
        * @param int @idPost -> clave del post del que queramos buscar comentarios
    */
    function selectComentPost($idPost){
        try {
            $connection = connect();

            $querySQL = "SELECT * FROM coment WHERE idPost = $idPost";
            $sentence = $connection->prepare($querySQL);
            $sentence->execute();

            $coment = $sentence->fetchAll();
            $continue = ($coment && $sentence->rowCount()>0) ? true : false;

            return $coment;

        } catch(PDOException $error) {
            $error = $error->getMessage();
        }
    }

    function contComents($idPost){
        try {
            $connection = connect();

            $querySQL = "SELECT * FROM coment WHERE idPost = $idPost";
            $sentence = $connection->prepare($querySQL);
            $sentence->execute();

            $coment = $sentence->fetchAll();
            $continue = ($coment && $sentence->rowCount()>0) ? true : false;

            return $sentence->rowCount();

        } catch(PDOException $error) {
            $error = $error->getMessage();
        }
    }

?>