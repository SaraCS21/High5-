<?php

    namespace Controllers;

    use PDO;
    use PDOException;
    use Config\Validate;
    use Config\ConnectDB;

    /**
         * Clase encargada de controlar las personas
         * 
         * Se encarga de controlar lo referido al CRUD 
         * de las personas dentro de la Base de Datos
     */
    class Person {
        /**
            * Creación de un administrador
            *
            * Esta función se conecta a la Base de Datos para insertar
            * los valores de una nueva persona con el tipo "admin"
            *
            * Esta función solo se ejecuta al principio para tener, 
            * al menos, un administrador en nuestra web
        */
        public static function createAdmin(){
            try {
                $connection = ConnectDB::connect();
        
                $querySQL = "SELECT * FROM person WHERE type = 'admin'";
                $sentenceQuery = $connection->prepare($querySQL);
                $sentenceQuery->execute();
        
                $values = $sentenceQuery->fetchAll();
        
                // Comprobamos que no exitan más administradores para no duplicar datos
                $create = ($sentenceQuery->rowCount() >= 1) ? false : true;
        
                if ($create){
                    $querySQL = $connection->prepare
                    ("INSERT INTO person (name, surname, email, password, age, type, block) VALUES
                    (:name, :surname, :email, :password, :age, :type, :block)");
        
                    $querySQL->bindValue(':name', "Admin", PDO::PARAM_STR);
                    $querySQL->bindValue(':surname', "Admin", PDO::PARAM_STR);
                    $querySQL->bindValue(':email', "admin@high5.com", PDO::PARAM_STR);
                    $querySQL->bindValue(':password', Validate::hashPassword("Daw1234!"), PDO::PARAM_STR);
                    $querySQL->bindValue(':age', 25, PDO::PARAM_INT);
                    $querySQL->bindValue(':type', "admin", PDO::PARAM_STR);
                    $querySQL->bindValue(':block', "unblock", PDO::PARAM_STR);
        
                    $querySQL->execute();
                }
        
            } catch(PDOException $error) {
                $error = $error->getMessage();
            }
        }

        /**
            * Creación de un usuario
            *
            * Esta función se conecta a la Base de Datos para insertar
            * los valores de una nueva persona de tipo usuario 
            *
            * @param por $_POST -> valores de la persona [name, surname, email, password y age]
            *
            * @global $_REQUEST
        */
        public static function createPerson(){
            try {
                $connection = ConnectDB::connect();

                $querySQL = $connection->prepare
                ("INSERT INTO person (name, surname, email, password, age, type, block) VALUES
                (:name, :surname, :email, :password, :age, :type, :block)");

                $querySQL->bindValue(':name', Validate::sanitize($_REQUEST["name"]), PDO::PARAM_STR);
                $querySQL->bindValue(':surname', Validate::sanitize($_REQUEST["surname"]), PDO::PARAM_STR);
                $querySQL->bindValue(':email', Validate::sanitize($_REQUEST["email"]), PDO::PARAM_STR);
                $querySQL->bindValue(':password', Validate::hashPassword(Validate::sanitize($_REQUEST["password"])), PDO::PARAM_STR);
                $querySQL->bindValue(':age', Validate::sanitize($_REQUEST["age"]), PDO::PARAM_INT);
                $querySQL->bindValue(':type', "user", PDO::PARAM_STR);
                $querySQL->bindValue(':block', "unblock", PDO::PARAM_STR);

                $querySQL->execute();

            } catch(PDOException $error) {
                $error = $error->getMessage();
            }
        }

        /**
            * Comprobación de la contraseña de una persona
            *
            * Esta función se conecta a la Base de Datos para buscar 
            * la contraseña de una persona, a través de su email, y así
            * comprobar que la contraseña que le estamos pasando por
            * el formulario de login es correcta. Además también comprobamos
            * que exista ese email en la base de datos.
            *
            * @param por $_POST -> algunos valores de la persona [email, password]
            *
            * @global $_REQUEST
            *
            * @return string $result -> vacío en caso de que esté correcto, 
            * un mensaje de error en caso de que algo falle
        */
        public static function comprobePerson(){
            $errors = require "./static/constant/errors.php";

            try {
                $connection = ConnectDB::connect();

                $sql = "SELECT password FROM person WHERE email = :email";

                $querySQL = $connection->prepare($sql);
                $querySQL->bindValue(':email', Validate::sanitize($_REQUEST['email']), PDO::PARAM_STR);
                $querySQL->execute();

                $values = $querySQL->fetchAll();

                $result = "";
                // En caso de que obtengamos algún valor acorde...
                $exist = ($querySQL->rowCount() !== 1) ? false : true; 

                if ($exist){
                    // Verificamos la contraseña
                    $result = (Validate::verifyPassword(Validate::sanitize($_REQUEST["password"]), $values[0][0])) ? "" : $errors["errors"]["incorrectPassword"];
                } else {
                    $result = $errors["errors"]["emailNoExists"];
                }
                return $result;

            } catch(PDOException $error) {
                $error = $error->getMessage();
            }
        }

        /**
            * Actualización de los datos de una persona
            *
            * Esta función se conecta a la Base de Datos para buscar 
            * a un usuario por su "id" y actualizar sus datos
            *
            * @param por $_POST -> valores de la persona [name, surname, email, password, age y type (opcionalmente)]
            * @param por $_POST -> el botón de tipo submit de "editUser" 
            * @param por $_SESSION -> id del usuario actual [idUser]
            *
            * @global $_REQUEST, $_SESSION
        */
        public static function updatePerson(){
            try {
                $connection = ConnectDB::connect();

                $querySQL = $connection->prepare
                ("UPDATE person SET name = :name, surname = :surname, email = :email, 
                password = :password, age = :age, type = :type, block = :block WHERE id = :idUser");

                $idUser = (isset($_REQUEST["editUser"])) ? $_REQUEST["editUser"] : $_SESSION["idUser"];
                $querySQL->bindValue(':idUser', Validate::sanitize($idUser), PDO::PARAM_INT);

                $querySQL->bindValue(':name', Validate::sanitize($_REQUEST["name"]), PDO::PARAM_STR);
                $querySQL->bindValue(':surname', Validate::sanitize($_REQUEST["surname"]), PDO::PARAM_STR);
                $querySQL->bindValue(':email', Validate::sanitize($_REQUEST["email"]), PDO::PARAM_STR);

                $password = $_REQUEST["updatePerson"] ? $_REQUEST["password"] : Validate::hashPassword($_REQUEST["password"]);
                $querySQL->bindValue(':password', Validate::sanitize($password), PDO::PARAM_STR);
                $querySQL->bindValue(':age', Validate::sanitize($_REQUEST["age"]), PDO::PARAM_INT);

                $type = (isset($_REQUEST["type"])) ? $_REQUEST["type"] : "user";
                $querySQL->bindValue(':type', Validate::sanitize($type), PDO::PARAM_STR);

                $block = (isset($_REQUEST["block"])) ? $_REQUEST["block"] : "unblock";
                $querySQL->bindValue(':block', Validate::sanitize($block), PDO::PARAM_STR);

                $querySQL->execute();

            } catch(PDOException $error) {
                $error = $error->getMessage();
            }
        }

        /**
            * Borrado de una persona
            *
            * Esta función se conecta a la Base de Datos para buscar 
            * a un usuario por su "id" y eliminarlo 
            *
            * @param por $_POST -> el botón de tipo submit de "deleteUser" 
            * @param por $_SESSION -> id del usuario actual [idUser]
            *
            * @global $_REQUEST, $_SESSION
        */
        public static function deletePerson(){
            try {
                $connection = ConnectDB::connect();

                $sql = "DELETE FROM person WHERE id = :idUser";

                $querySQL = $connection->prepare($sql);

                $idUser = (isset($_REQUEST["deleteUser"])) ? $_REQUEST["deleteUser"] : $_SESSION["idUser"];
                $querySQL->bindValue(':idUser', Validate::sanitize($idUser), PDO::PARAM_INT);

                $querySQL->execute();

            } catch(PDOException $error) {
                $error = $error->getMessage();
            }
        }

        /**
            * Selección del tipo de una persona
            *
            * Esta función se conecta a la Base de Datos para buscar 
            * a un usuario por su "id" y devolver su tipo
            *
            * @param por $_SESSION -> id del usuario actual [idUser]
            *
            * @global $_SESSION
            *
            * @return $type -> false en caso de que el usuario no exista, 
            * una string con el tipo en caso de que el usuario si exista
        */
        public static function selectTypePerson(){
            try {
                $connection = ConnectDB::connect();

                $idUser = isset($_SESSION["idUser"]) ? $_SESSION["idUser"] : 0;

                $sql = "SELECT type FROM person WHERE id = :idUser";

                $querySQL = $connection->prepare($sql);
                $querySQL->bindValue(':idUser', Validate::sanitize($idUser), PDO::PARAM_INT);

                $querySQL->execute();

                $userValues = $querySQL->fetchAll();
                $continue = ($userValues && $querySQL->rowCount()>0) ? true : false;

                $type = $continue ? $userValues[0]["type"] : false;

                return $type;

            } catch(PDOException $error) {
                $error = $error->getMessage();
            }
        }

        /**
            * Selección del bloqueo de una persona
            *
            * Esta función se conecta a la Base de Datos para buscar 
            * a un usuario por su "email" y devolver si está o no bloqueada
            *
            * @param string $email -> email del usuario actual
            *
            * @global $_REQUEST
            *
            * @return $block -> false en caso de que el usuario no exista, 
            * una string con el bloqueo en caso de que el usuario si exista
        */
        public static function selectBlockPerson($email = ""){
            try {
                $connection = ConnectDB::connect();

                $sql = "SELECT block FROM person WHERE email = :email";

                $querySQL = $connection->prepare($sql);
                $email = isset($_REQUEST["email"]) ? $_REQUEST["email"] : $email;
                $querySQL->bindValue(':email', Validate::sanitize($email), PDO::PARAM_STR);

                $querySQL->execute();
                $userValues = $querySQL->fetchAll();

                $continue = ($userValues && $querySQL->rowCount()>0) ? true : false;
                $block = $continue ? $userValues[0]["block"] : false;

                return $block;

            } catch(PDOException $error) {
                $error = $error->getMessage();
            }
        }

        /**
            * Selección del nombre de una persona
            *
            * Esta función se conecta a la Base de Datos para buscar 
            * a una persona por su "idUser" y devolver su nombre
            *
            * @param int @idUser -> clave de la persona 
            *
            * @return array $name -> array con el nombre del usuario
        */
        public static function selectNamePerson($idUser){
            try {
                $connection = ConnectDB::connect();

                $sql = "SELECT name FROM person WHERE id = :idUser";

                $querySQL = $connection->prepare($sql);
                $querySQL->bindValue(':idUser', Validate::sanitize($idUser), PDO::PARAM_INT);

                $querySQL->execute();
                $name = $querySQL->fetchAll();

                return $name;

            } catch(PDOException $error) {
                $error = $error->getMessage();
            }
        }

        /**
            * Selección del email de una persona
            *
            * Esta función se conecta a la Base de Datos para buscar 
            * a una persona por su "idUser" y devolver su email
            *
            * @param por $_SESSION -> id del usuario actual [idUser]
            *
            * @global $_SESSION
            *
            * @return array $email -> array con el email del usuario
        */
        public static function selectEmailPerson(){
            try {
                $connection = ConnectDB::connect();

                $sql = "SELECT email FROM person WHERE id = :idUser";

                $querySQL = $connection->prepare($sql);
                $querySQL->bindValue(':idUser', Validate::sanitize($_SESSION["idUser"]), PDO::PARAM_INT);

                $querySQL->execute();
                $email = $querySQL->fetchAll();

                return $email;

            } catch(PDOException $error) {
                $error = $error->getMessage();
            }
        }

        /**
            * Obtención de todos los emails registrados
            *
            * Esta función se conecta a la Base de Datos para buscar 
            * a todos los email y devolverlos
            *
            * @return array $email -> array todos los emails
        */
        public static function allEmailPerson(){
            try {
                $connection = ConnectDB::connect();

                $emailQuerySQL = "SELECT email FROM person";

                $sentenceEmail = $connection->prepare($emailQuerySQL);
                $sentenceEmail->execute();
        
                $email = $sentenceEmail->fetchAll();

                return $email;

            } catch(PDOException $error) {
                $error = $error->getMessage();
            }
        }

        /**
            * Selección de una persona
            *
            * Esta función se conecta a la Base de Datos para buscar 
            * a un usuario por su "id" y devolver sus datos
            *
            * @param int @idUser -> clave de la persona que queramos buscar
            *
            * @return -> false en caso de que el usuario no exista, 
            * un array de los valores del usuario en caso de que exista
        */
        public static function selectPerson($idUser){
            try {
                $connection = ConnectDB::connect();

                $sql = "SELECT * FROM person WHERE id = :idUser";

                $querySQL = $connection->prepare($sql);
                $querySQL->bindValue(':idUser', Validate::sanitize($idUser), PDO::PARAM_INT);

                $querySQL->execute();

                $userValues = $querySQL->fetchAll();
                $continue = ($userValues && $querySQL->rowCount()>0) ? true : false;

                return ($continue) ? $userValues : false;

            } catch(PDOException $error) {
                $error = $error->getMessage();
            }
        }

        /**
            * Selección de todas las personas
            *
            * Esta función se conecta a la Base de Datos para buscar 
            * a todas las personas y devolverlos
            *
            * @return array $users -> array de los valores de todos los usuarios
        */
        public static function selectAllPerson(){
            try {
                $connection = ConnectDB::connect();

                $querySQL = "SELECT * FROM person";
                $sentence = $connection->prepare($querySQL);
                $sentence->execute();
        
                $users = $sentence->fetchAll();
                $continueUser = ($users && $sentence->rowCount()>0) ? true : false;

                return $users;

            } catch(PDOException $error) {
                $error = $error->getMessage();
            }
        }

        /**
            * Selección del id de una persona
            *
            * Esta función se conecta a la Base de Datos para buscar 
            * a una persona por su "email" y devolver su id
            *
            * @param por $_REQUEST -> email del usuario [email]
            *
            * @global $_REQUEST
            *
            * @return array $name -> array con el nombre del usuario
        */
        public static function selectIdPerson(){
            try {
                $connection = ConnectDB::connect();

                $sql = "SELECT id FROM person WHERE email = :email";

                $querySQL = $connection->prepare($sql);
                $querySQL->bindValue(':email', Validate::sanitize($_REQUEST["email"]), PDO::PARAM_STR);

                $querySQL->execute();
                $id = $querySQL->fetchAll();

                return $id;

            } catch(PDOException $error) {
                $error = $error->getMessage();
            }
        }
    }
?>