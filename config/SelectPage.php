<?php

    namespace Config;
    
    use Controllers\Person;

    /**
         * Clase encargada de devolver la url correspondiente
         * 
         * Se encarga de controlar las urls a las que se debe 
         * dirigir nuestra web tras la instalación.
     */
    class SelectPage {

        /**
            * Selección de página
            *
            * Esta función comprueba ciertos parámetros de la sesión 
            * para dirigirse a alguna parte dentro de la web.
            *
            * @param por $_SESSION -> id del usuario [idUser]
            *
            * @global $_SESSION
        */
        public static function selectPage(){
            // En caso de que no exista un usuario con sesión iniciada...
            if (Person::selectTypePerson() === "user" && isset($_SESSION["idUser"])){
                $url = "landing";

            // En caso de tener un usuario administrador
            } else if (Person::selectTypePerson() === "admin" && isset($_SESSION["idUser"])) {
                $url = "adminPanel";

            // En caso de tener un usuario normal
            } else {
                $url = "login";
                $_SESSION["idUser"] = 0;
            }

            // Modificamos la url a la que nos vamos a dirigir
            Header("Location: ./index.php?url=$url");
        }
    }

?>