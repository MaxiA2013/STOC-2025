<?php
/*funcion creada para la verficación del lado del servidor usando expresiones regulares y preg_match
preg_match recibe el filtro y la cadena y los compara
las expresiones regulares son patrones de busqueda, tambien son conocidos como regex
*/
function verificar_cadenas($filtro, $cadena){
    if (preg_match($filtro, $cadena)) {
        return true;
    }else{
        return false;
    }
}

?>