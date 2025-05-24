function validate_nombre_usuario(event){
    console.log(event.target.value);
    $.ajax({
        url: "controladores/usuarios/usuarios_ajax.controlador.php",
        type: "post",
        data: {
            'nombre_usuario': event.target.value,
            'action': 'ajax'
        } ,
        success: function (response){
            let data = JSON.parse(response);
            if (data.data == 'error'){
                alert('el usuario ya existe');
                document.getElementById('nombre_usuario').value = '';
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus, errorThrown);
        }
    });
}
function validate_email(){
    alert('Porfavor ingrese el email correctamente');
}