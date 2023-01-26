<?php

    namespace Controllers;

    use PDO;
    use PDOException;
    use Config\ConnectDB;

    class Likes {
        /**
            * Contador de likes
            *
            * Esta función se conecta a la Base de Datos para buscar likes 
            * por el "idPost" y devolver la cantidad de likes que tiene ese post
            *
            * @param int @idPost -> clave del post del que queramos buscar likes
            *
            * @return int $querySQL->rowCount() -> número de likes encontrados
        */
        public static function countLikesPost($idPost){
            try {
                $connection = ConnectDB::connect();

                $sql = "SELECT * FROM likes WHERE idPost = :idPost";

                $querySQL = $connection->prepare($sql);
                $querySQL->bindValue(':idPost', $idPost, PDO::PARAM_INT);
                $querySQL->execute();
                
                return $querySQL->rowCount();

            } catch(PDOException $error) {
                $error = $error->getMessage();
            }
        }

        /**
            * Selección de likes
            *
            * Esta función se conecta a la Base de Datos para buscar 
            * likes por el "idPost" y devolver sus datos
            *
            * @param int @idPost -> clave del post del que queramos buscar likes
            *
            * @return array $likes -> un array con los valores de los likes encontrados
        */
        public static function getLikesPost($idPost){
            try {
                $connection = ConnectDB::connect();

                $sql = "SELECT idUser FROM likes WHERE idPost = :idPost";

                $querySQL = $connection->prepare($sql);
                $querySQL->bindValue(':idPost', $idPost, PDO::PARAM_INT);
                $querySQL->execute();
                
                $likes = $querySQL->fetchAll(PDO::FETCH_ASSOC);
                return $likes;

            } catch(PDOException $error) {
                $error = $error->getMessage();
            }
        }

        /**
            * Creación de likes 
            *
            * Esta función se conecta a la Base de Datos para insertar
            * los valores de un nuevo like en su respectiva tabla.
            *
            * @param por $_POST -> el id del post [idPost]
            * @param por $_SESSION -> el id del usuario [idUser]
            *
            * @global $_REQUEST, $_SESSION
        */
        public static function setLike(){
            try {
                $connection = ConnectDB::connect();

                $querySQL = $connection->prepare
                ("INSERT INTO likes (idUser, idPost) VALUES
                (:idUser, :idPost)");

                $querySQL->bindValue(':idUser', $_SESSION["idUser"], PDO::PARAM_INT);
                $querySQL->bindValue(':idPost', $_REQUEST["idPost"], PDO::PARAM_INT);

                $querySQL->execute();

            } catch(PDOException $error) {
                $error = $error->getMessage();
            }
        }

        /**
            * Borrado de un like
            *
            * Esta función se conecta a la Base de Datos para buscar 
            * a un like por el "idUser" y el "idPost" y eliminarlo 
            *
            * @param por $_POST -> el id del post [idPost]
            * @param por $_SESSION -> el id del usuario [idUser]
            *
            * @global $_REQUEST, $_SESSION
        */
        public static function deleteLike(){
            try {
                $connection = ConnectDB::connect();

                $querySQL = $connection->prepare
                ("DELETE FROM likes WHERE idUser = :idUser AND idPost = :idPost");

                $querySQL->bindValue(':idUser', $_SESSION["idUser"], PDO::PARAM_INT);
                $querySQL->bindValue(':idPost', $_REQUEST["idPost"], PDO::PARAM_INT);

                $querySQL->execute();

            } catch(PDOException $error) {
                $error = $error->getMessage();
            }
        }
    }
?>