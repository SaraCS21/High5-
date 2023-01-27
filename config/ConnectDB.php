<?php

    namespace Config;

    use PDO;
    use PDOException;
    use Dotenv;
    use Controllers\Person;
    use Controllers\Post;

    $dotenv=Dotenv\Dotenv:: createImmutable(__DIR__ . "\..");
    $dotenv->safeLoad();

    /**
         * Clase encargada de conectarse a la Base de Datos
         * 
         * Se encarga de controlar lo referido a las conexiones
         * con la Base de Datos y la instalación de esta
     */
    class ConnectDB {
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
        public static function create(){
            $options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];
            $connection = new PDO("mysql:host=" . $_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASS'], $options);
            return $connection;
        }

        /**
            * Devuelve una conexión con la Base de Datos
            *
            * Esta función se encarga de conectarse a la base de
            * datos, con los parámetros guardados en config.php.
            *
            * @param por $_ENV -> valores para la conexión con la base de datos [host, dbname, user, password]
            *
            * @global $_ENV
            *
            * @return object $connection -> Objeto de la conexión
        */
        public static function connect(){
            $options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];
            $connection = new PDO("mysql:host=" . $_ENV['DB_HOST'] . ";dbname=" . $_ENV['DB_DB'], $_ENV['DB_USER'], $_ENV['DB_PASS'], $options);
            return $connection;
        }

        /**
            * Instala la Base de Datos
            *
            * Esta función se encarga de instalar la Base de Datos
            * y de crear ciertos valores por defecto.
        */
        public static function install(){
            // Instalación de la Base de Datos
            try{
                $connection = self::create();
                $sql = file_get_contents("./static/database/db.sql");
                $connection->exec($sql);
        
            } catch (PDOException $error){
                echo $error->getMessage();
            }
        
            // Creaciones de valores por defecto
            Person::createAdmin();
            Post::createDefaultPost();
        }

    }
?>