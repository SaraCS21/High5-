<?php

    namespace Controllers;

    use PDO;
    use PDOException;
    use Config\ConnectDB;

    /**
         * Clase encargada de controlar los comentarios
         * 
         * Se encarga de controlar lo referido al CRUD 
         * de los comentarios dentro de la Base de Datos
     */
    class Coment {
        /**
            * Creación de comentarios en post
            *
            * Esta función se conecta a la Base de Datos para insertar
            * los valores de un nuevo comentario, pasados por un formulario,
            * en su respectiva tabla.
            *
            * @param por $_POST -> algunos valores del comentario [content, idPost]
            * @param por $_SESSION -> algunos valores del comentario [idUser]
            *
            * @global $_REQUEST, $_SESSION
        */
        public static function createComent(){
            try {
                $connection = ConnectDB::connect();

                $querySQL = $connection->prepare
                ("INSERT INTO coment (idUser, idPost, content, publicationDate) VALUES
                (:idUser, :idPost, :content, :publicationDate)");

                $querySQL->bindValue(':idUser', $_SESSION["idUser"], PDO::PARAM_INT);
                $querySQL->bindValue(':idPost', $_REQUEST["idPost"], PDO::PARAM_INT);
                $querySQL->bindValue(':content', $_REQUEST["content"], PDO::PARAM_STR);
                $querySQL->bindValue(':publicationDate', date('Y-m-d'), PDO::PARAM_STR); // Fecha actual

                $querySQL->execute();

            } catch(PDOException $error) {
                $error = $error->getMessage();
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
        public static function updateComent(){
            try {

                $connection = ConnectDB::connect();

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
                $error = $error->getMessage();
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
        public static function deleteComent(){
            try {
                $connection = ConnectDB::connect();

                $sql = "DELETE FROM coment WHERE id = :idComent";

                $querySQL = $connection->prepare($sql);
                $querySQL->bindValue(':idComent', $_REQUEST["id"], PDO::PARAM_INT);

                $querySQL->execute();

            } catch(PDOException $error) {
                $error = $error->getMessage();
            }
        }

        /**
            * Selección de comentarios
            *
            * Esta función se conecta a la Base de Datos para buscar 
            * comentarios por el "idPost" y devolver sus datos
            *
            * @param int @idPost -> clave del post del que queramos buscar comentarios
            *
            * @return array $coment -> un array con los valores de los comentarios encontrados
        */
        public static function selectComentPost($idPost){
            try {
                $connection = ConnectDB::connect();

                $sql = "SELECT * FROM coment WHERE idPost = :idPost";

                $querySQL = $connection->prepare($sql);
                $querySQL->bindValue(':idPost', $idPost, PDO::PARAM_INT);
                $querySQL->execute();

                $coment = $querySQL->fetchAll();

                return $coment;

            } catch(PDOException $error) {
                $error = $error->getMessage();
            }
        }

        /**
            * Contador de comentarios
            *
            * Esta función se conecta a la Base de Datos para buscar comentarios 
            * por el "idPost" y devolver la cantidad de comentarios que tiene ese post
            *
            * @param int @idPost -> clave del post del que queramos buscar comentarios
            *
            * @return int $querySQL->rowCount() -> número de comentarios encontrados
        */
        public static function contComents($idPost){
            try {
                $connection = ConnectDB::connect();

                $sql = "SELECT * FROM coment WHERE idPost = :idPost";

                $querySQL = $connection->prepare($sql);
                $querySQL->bindValue(':idPost', $idPost, PDO::PARAM_INT);
                $querySQL->execute();
                
                return $querySQL->rowCount();

            } catch(PDOException $error) {
                $error = $error->getMessage();
            }
        }

    }

?>