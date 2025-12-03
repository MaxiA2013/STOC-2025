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

<<<<<<< HEAD
/* Función para mostrar mensajes con SweetAlert2 */
function mostrarAlerta($icon, $title, $text, $redirect) {
    echo "<!DOCTYPE html>
    <html lang='es'>
    <head>
        <meta charset='UTF-8'>
        <title>Mensaje</title>
        <script src='../assets/js/sweetalert2@11.js'></script>
    </head>
    <body>
        <script>
        Swal.fire({
            icon: '$icon',
            title: '$title',
            text: '$text'
        }).then(() => {
            window.location.href = '$redirect';
        });
        </script>
    </body>
    </html>";
    exit();
}
?>

=======
>>>>>>> origin/mi-ramita
?>