function validate(){
    var nombre_usuario = document.getElementById('nombre_usuario');
    var email = document.getElementById('email'); 
    var password = document.getElementById('password');
    var form = document.getElementById('id_form');

    var id_usuario_parrafo = document.getElementById('id_usuario_parrafo');
    var id_email_parrafo = document.getElementById('id_email_parrafo');
    var id_contraseña_parrafo = document.getElementById('id_contraseña_parrafo');

    // Limpiar estilos de errores previos
    nombre_usuario.classList.remove('validation-error');
    email.classList.remove('validation-error');
    password.classList.remove('validation-error');
    id_usuario_parrafo.style.display = 'none';
    id_email_parrafo.style.display = 'none';
    id_contraseña_parrafo.style.display = 'none';

    var valid = true;

    if (nombre_usuario.value === ''){
        nombre_usuario.classList.add('validation-error');
        id_usuario_parrafo.style.display = 'block';
        valid = false;
    }

    if (email.value === ''){
        email.classList.add('validation-error');
        id_email_parrafo.style.display = 'block';
        valid = false;
    }

    if (password.value === ''){
        password.classList.add('validation-error');
        id_contraseña_parrafo.style.display = 'block';
        valid = false;
    }

    return valid;
}
