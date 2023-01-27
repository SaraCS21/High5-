<?php

    namespace Controllers;

    use PDO;
    use PDOException;
    use Config\ConnectDB;

    /**
         * Clase encargada de controlar los posts
         * 
         * Se encarga de controlar lo referido al CRUD 
         * de los posts dentro de la Base de Datos
     */
    class Post {
        /**
            * Creación de un post por defecto
            *
            * Esta función se conecta a la Base de Datos para insertar
            * los valores de un nuevo post escrito por el administrador
            *
            * Esta función solo se ejecuta siempre para tener, 
            * al menos, un post en nuestra web
        */
        public static function createDefaultPost(){
            try {
                $connection = ConnectDB::connect();

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
                $error = $error->getMessage();
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
        public static function createPost(){
            try {
                $connection = ConnectDB::connect();

                $querySQL = $connection->prepare
                ("INSERT INTO post (content, title, theme, publicationDate, idUser, numViews) VALUES
                (:content, :title, :theme, :publicationDate, :idUser, :numViews)");

                $querySQL->bindValue(':content', $_REQUEST["content"], PDO::PARAM_STR);
                $querySQL->bindValue(':title', $_REQUEST["title"], PDO::PARAM_STR);
                $querySQL->bindValue(':theme', $_REQUEST["theme"], PDO::PARAM_STR);
                $querySQL->bindValue(':publicationDate', date('Y-m-d'), PDO::PARAM_STR); // Fecha actual
                $querySQL->bindValue(':idUser', $_SESSION["idUser"], PDO::PARAM_INT); // Usuario con sesión activa
                $querySQL->bindValue(':numViews', 0, PDO::PARAM_INT); // Número de vistas al principio

                $querySQL->execute();

            } catch(PDOException $error) {
                $error = $error->getMessage();
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
        public static function updatePost(){
            try {
                $connection = ConnectDB::connect();

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
                $error = $error->getMessage();
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
        public static function deletePost(){
            try {
                $connection = ConnectDB::connect();

                $sql = "DELETE FROM post WHERE id = :idPost";

                $querySQL = $connection->prepare($sql);

                $idPost = (isset($_REQUEST["delete"])) ? $_REQUEST["delete"] : $_REQUEST["deletePost"];
                $querySQL->bindValue(':idPost', $idPost, PDO::PARAM_INT);

                $querySQL->execute();

            } catch(PDOException $error) {
                $error = $error->getMessage();
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
            * @return -> false en caso de que el post no exista,
            * un array con los valores en caso de que el post si exista
        */
        public static function selectPost($idPost){
            try {
                $connection = ConnectDB::connect();

                $sql = "SELECT * FROM post WHERE id = :idPost";

                $querySQL = $connection->prepare($sql);
                $querySQL->bindParam(':idPost', $idPost, PDO::PARAM_INT);

                $querySQL->execute();

                $postValues = $querySQL->fetchAll();
                $continue = ($postValues && $querySQL->rowCount()>0) ? true : false;

                return ($continue) ? $postValues : false;

            } catch(PDOException $error) {
                $error = $error->getMessage();
            }
        }

        /**
            * Selección de todos los posts
            *
            * Esta función se conecta a la Base de Datos para buscar 
            * todos los posts y devolverlos.
            *
            * @return array $posts -> array con los valores de todos los posts
        */
        public static function selectAllPost(){
            try {
                $connection = ConnectDB::connect();

                $querySQL = "SELECT * FROM post";
                $sentence = $connection->prepare($querySQL);
                $sentence->execute();
        
                $posts = $sentence->fetchAll();
                $continuePost = ($posts && $sentence->rowCount()>0) ? true : false;

                return $posts;

            } catch(PDOException $error) {
                $error = $error->getMessage();
            }
        }

        /**
            * Selección del número de vistas de un post
            *
            * Esta función se conecta a la Base de Datos para buscar 
            * a un post por su "id" y recoger la cantidad de número 
            * de vistas que tiene.
            *
            * @param int @idPost -> clave del post que queramos buscar
            *
            * @return int $views[0][0] -> número de visitas del post
        */
        public static function getViews($idPost){
            try {
                $connection = ConnectDB::connect();

                $sql = "SELECT numViews FROM post WHERE id = :idPost";

                $querySQL = $connection->prepare($sql);
                $querySQL->bindParam(':idPost', $idPost, PDO::PARAM_INT);

                $querySQL->execute();

                $views = $querySQL->fetchAll();
                $continue = ($views && $querySQL->rowCount()>0) ? true : false;

                return $views[0][0];

            } catch(PDOException $error) {
                $error = $error->getMessage();
            }
        }

        /**
            * Incrementación del número de vistas de un post
            *
            * Esta función se conecta a la Base de Datos para buscar 
            * a un post por su "id" e incrementar su valor en 1.
            *
            * @param int @idPost -> clave del post que queramos buscar
        */
        public static function incrementViews($idPost){
            try {
                $connection = ConnectDB::connect();
    
                $querySQL = $connection->prepare
                ("UPDATE post SET numViews = :numViews  WHERE id = :idPost");
    
                $querySQL->bindValue(':idPost', $idPost, PDO::PARAM_INT);
    
                $numViews = Post::getViews($idPost) + 1;
                $querySQL->bindValue(':numViews', $numViews, PDO::PARAM_INT);
    
                $querySQL->execute();
    
            } catch(PDOException $error) {
                $error = $error->getMessage();
            }
        }
    }
?>