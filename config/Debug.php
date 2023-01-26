<?php

    namespace Config;

    class Debug {
        /**
            * Debug de arrays
            *
            * Esta función recibe un array y devuelve una cadena de texto
            * con los elementos de este
            *
            * @param array @array -> array que queramos comprobar
        */
        public static function debugArray($array){
            foreach ($array as $key => $value) {
                echo "$key - $value<br>";
            }
        }
    }

?>