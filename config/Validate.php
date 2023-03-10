<?php

    namespace Config;

    use Controllers\Person;

    /**
         * Clase encargada de validar parámetros
         * 
         * Se encarga de validar ciertos parámetros 
         * pasados por los distintos formularios de la web
     */
    class Validate {
        /**
            * Validación de email
            *
            * Esta función recibe un email y se encarga de validar que
            * esté escrito correctamente
            *
            * @param string @email -> Dirección de correo 
            *
            * @return string -> vacío en caso de que se cumpla y con un mensaje de error en caso de que no se cumpla
        */
        public static function validateEmail($email){
            $errors = require "./static/constant/errors.php";

            $result = (filter_var($email, FILTER_VALIDATE_EMAIL)) ? "" : $errors["errors"]["email"];
            return $result;
        }

        /**
            * Validación de contraseña
            *
            * Esta función recibe dos contraseñas, verifica que la primera cumpla con
            * ciertas pautas y, tras eso, la compara con la otra contraseña
            * para ver si son, o no, iguales
            *
            * @param string @pass -> Contraseña 1 
            * @param string @confirmPass -> Contraseña 2
            *
            * @return string -> vacío en caso de que se cumpla y con un mensaje de error en caso de que no se cumpla
        */
        public static function validatePassword($pass, $confirmPass){
            $errors = require "./static/constant/errors.php";
            $result = "";

            // Entre 8 y 16 caracteres. Mínimo una mayúscula, una minúscula, un número y un carácter especial.
            if(preg_match("/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%#?&])[A-Za-z\d@$!%*#?&]{8,16}$/", $pass)){
                $result = ($pass === $confirmPass) ? "" : $errors["errors"]["confirmPassword"];
                return $result;
            } 
            $result = ($pass === $confirmPass) ? "" : $errors["errors"]["password"];
            return $result;
        }

        /**
            * Validación de datos escritos por formularios
            *
            * Esta función recibe varios parámetros para comprobar si
            * los valores insertados en los formularios están, o no, 
            * escritos correctamente
            *
            * @param string @keysInsert -> todas las claves del formulario
            * @param string @typeForm -> tipo de formulario en el que nos encontramos
            * @param por $_POST -> [password, confirmPassword, email]
            *
            * @global $_REQUEST
            *
            * @return string -> vacío en caso de que se cumpla y con un mensaje de error en caso de que no se cumpla
        */
        public static function comprobeInsert($keysInsert, $typeForm){
            $errors = require "./static/constant/errors.php";

            // Eliminamos el último elemento del array, el botón
            unset($_REQUEST[$typeForm]);
            $result = "";

            // Recorremos todas las claves de nuestro formulario
            for ($k = 0; $k < count($keysInsert); $k++){
                $keyInsert = $keysInsert[$k];
                
                // Comprobamos que no hay claves de más, de menos o que alguna de ellas contenga valores vacíos
                if (!isset($_REQUEST["$keyInsert"]) || count(array_diff(array_keys($_REQUEST), $keysInsert)) !== 0 || empty($_REQUEST["$keyInsert"])){
                    return $errors["errors"]["empty"];

                } else {

                    // En caso de estar en un formulario de registro o de actualización de datos...
                    if ($typeForm === "register" || $typeForm === "update" ){

                        if ($typeForm === "register" && in_array($_REQUEST["email"], Person::allEmailPerson()[0])){
                            $result = $errors["errors"]["emailExists"];

                        } else if (self::validateEmail($_REQUEST["email"]) !== ""){
                            $result = self::validateEmail($_REQUEST["email"]);

                        } else if (self::validatePassword($_REQUEST["password"], $_REQUEST["confirmPassword"]) !== ""){
                            $result = self::validatePassword($_REQUEST["password"], $_REQUEST["confirmPassword"]);
                        } 

                    // En caso de estar en un formulario de login...
                    } else if ($typeForm === "login"){

                        if (self::validateEmail($_REQUEST["email"]) !== ""){
                            $result = self::validateEmail($_REQUEST["email"]);

                        } else if (Person::selectBlockPerson() === "block") {
                            $result = $errors["errors"]["blockUser"];
                        }

                    // En caso de estar en un formulario de creación de nuevos temas o envío de datos...
                    } else if ($typeForm === "createTheme" || $typeForm === "send" ){
                        $result = "";
                    }
                }
            }
            return $result;
        }

        /**
            * Validación de datos por formularios
            *
            * Esta función recibe varios parámetros para comprobar si
            * los valores insertados están escritos correctamentes y si
            * están todos los valores necesarios
            *
            * @param string @keysInsert -> todas las claves del formulario
            * @param string @typeForm -> tipo de formulario en el que nos encontramos
            * 
            * @return string -> vacío en caso de que se cumpla y con un mensaje de error en caso de que no se cumpla
        */
        public static function validate($typeForm, $keysInsert){
            $errors = require "./static/constant/errors.php";
            $keys = array_keys($_REQUEST);

            $result = in_array($typeForm, $keys) ? ((self::comprobeInsert($keysInsert, $typeForm) === "") ? "" : self::comprobeInsert($keysInsert, $typeForm)) : $errors["errors"]["inArray"];
            return $result;
        }

        /**
            * Codificación de contraseña
            *
            * Esta función recibe una contraseña y la encripta usando el 
            * algoritmo PASSWORD_BCRYPT
            *
            * @param string @password -> contraseña
            *
            * @return string -> contraseña encriptada
        */
        public static function hashPassword($password){
            return password_hash($password, PASSWORD_BCRYPT);
        }

        /**
            * Verificación de contraseña
            *
            * Esta función recibe una contraseña y la compara con la contraseña
            * encriptada que tiene el mismo usuario en la Base de Datos
            *
            * @param string @password -> contraseña
            * @param string @passwordHash -> contraseña encriptada
            *
            * @return boolean -> true en caso de que ambas contraseñas coincidan, false en caso contrario
        */
        public static function verifyPassword($password, $passwordHash){
            $result = (password_verify($password, $passwordHash)) ? true : false;
            return $result;
        }

        /**
            * Sanitización de datos
            *
            * Esta función recibe un valor de un formulario y lo sanitiza para
            * eliminar los espacios y el código HTML que se ha podido insertar
            *
            * @param @value -> valor de un formulario
            *
            * @return -> el valor sanitizado
        */
        public static function sanitize($value){
            return trim(strip_tags($value));
        }

    }
?>