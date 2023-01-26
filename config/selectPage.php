<?php

    namespace Config;

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
            if (!isset($_SESSION["idUser"]) || $_SESSION["idUser"] === 0){
                $url = "login";
                $_SESSION["idUser"] = 0;

            // En caso de tener un usuario administrador
            } else if ($_SESSION["idUser"] === 1) {
                $url = "adminPanel";

            // En caso de tener un usuario normal
            } else {
                $url = "landing";
            }
            // Modificamos la url a la que nos vamos a dirigir
            Header("Location: ./index.php?url=$url");
        }
    }

?>