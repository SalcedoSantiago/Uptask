
eventListeners();

function eventListeners(){

    document.querySelector('#formulario').addEventListener('submit', enviarFormulario);

}


function enviarFormulario(e){
    e.preventDefault();
    console.log("entro");

    var usuario = document.querySelector('#usuario').value,
        password = document.querySelector('#password').value,
        accion = document.querySelector('#tipo').value;
    
    var datos = new FormData();
    
    datos.append('usuario', usuario);
    datos.append('password', password);
    datos.append('accion', accion);

    if( usuario === '' || password === ''){

        Swal.fire(
            'Error',
            'Todos los campos deben llenarse',
            'error'
        );
    }
    else{
       
        var xhr = new XMLHttpRequest();

        xhr.open('POST', 'includes/modelos/modelo-usuario.php', true);

        xhr.onload = function () {
            if (this.status === 200) {
                var respuesta = JSON.parse(xhr.responseText);    
                    if (accion === 'crear') {
                        if( respuesta.respuesta === 'existe'){
                            Swal.fire(
                                'Error',
                                `${usuario} ya esta en uso`,
                                'error'
                            );
                        }else{
                            if (respuesta.respuesta === 'correcto') {
                                Swal.fire(
                                    'Te has registrado',
                                    `${respuesta.nombre} bienvenido, Ahora puedes ingresar`,
                                    'success'
                                ).then((results) => {
                                    window.location.href = 'login.php'
                                })
                            } else {
                                Swal.fire(
                                    'Error',
                                    'Ha ocurrido un error...',
                                    'error'
                                );
                            }
                        }
                    }/// cierre Crear
                    
                if(accion === 'login'){
                    console.log(respuesta);
                    if(respuesta.respuesta === 'correcto'){
                        Swal.fire(
                            'Has ingresado con existo',
                            `${respuesta.nombre} bienvenido`,
                            'success'
                        ).then((results) => {
                            window.location.href = 'index.php'
                        })
                    } else if (respuesta.error === 'pass_error'){
                        Swal.fire(
                            'Error',
                            'Ha ocurrido un error...',
                            'error'
                        );
                    }else{
                        Swal.fire(
                            'Error',
                            'No existe',
                            'warning'
                        );
                    }
                }
            }
        }
        xhr.send(datos)
    
    }
}

