
const proyectos = document.querySelector('#proyectos')

EventListeners();
barraProgreso();

function EventListeners(){
    document.querySelector('.crear-proyecto a').addEventListener('click',AddProyecto);
    document.querySelector('#agregar-tarea').addEventListener('submit',AddTarea );
    document.querySelector('.listado-pendientes').addEventListener('click', reconocerTarea);

}

function AddProyecto(e){
    e.preventDefault();
    var newInput = document.createElement('li');
    newInput.innerHTML= '<input type="text" id="nuevos-proyectos">'
    proyectos.appendChild(newInput);
    newInput.addEventListener('keyup',(e)=>{
       
        if(e.keyCode === 13 || e.which === 13){
            e.preventDefault();
            var nombreProyecto = newInput.childNodes[0].value;
            EnviarProyecto(nombreProyecto)
            newInput.remove();
        }
    });
}

function EnviarProyecto(nombre){
    var datos = new FormData();
    datos.append('nombre',nombre);
    datos.append('accion','crear');

    var xhr = new XMLHttpRequest();
    xhr.open('POST','includes/modelos/modelo-proyecto.php',true);
    xhr.onload = function(){
        if(this.status === 200){
            var respuesta = JSON.parse(xhr.responseText),
                nombre = respuesta.datos.nombre;
                id = respuesta.datos.id;

            /// Creo el A 
            var nuevoProyecto = document.createElement('a');
            nuevoProyecto.id = 'proyecto:' + id;
            nuevoProyecto.href = 'index.php?id_proyecto=' + id;
            nuevoProyecto.innerText = nombre;
            /// creo el Li
            var nuevoList= document.createElement('li');
            nuevoList.appendChild(nuevoProyecto);
            /// Lo imprimo en el html
            proyectos.appendChild(nuevoList);
        }
    }
    xhr.send(datos);

}

function AddTarea(e){
    e.preventDefault();
    var id = document.querySelector('#id_proyecto').value,
        tarea = document.querySelector('#tarea').value;
    if(id === ''){
        Swal.fire(
            'Error',
            'Debes seleccionar un proyecto o crear uno',
            'error'
        );
    }else{
        if(tarea === ''){
            Swal.fire(
                'Error',
                'Debes poner algo en el campo',
                'error'
            );
        }else{
        var datos = new FormData();
        datos.append('tarea',tarea);
        datos.append('id_proyecto',id);
        datos.append('accion','crear');
        crearTarea(datos);
        }
    }
   
}


function crearTarea(datos){
    var xhr = new XMLHttpRequest();

    xhr.open('POST','includes/modelos/modelo-tarea.php',true);

    xhr.onload = function(){
        if(this.status === 200){
            var respuesta = JSON.parse(xhr.responseText);
                tarea = respuesta.tarea,
                id = respuesta.id;
            if(respuesta.respuesta === 'correcto'){
                var listaTarea = document.querySelector('#lista-tareas');
                var nuevaTarea = document.createElement('li');
                var listaVacia = document.querySelectorAll('.tarea');
                nuevaTarea.classList.add('tarea');
                nuevaTarea.id = 'tarea:' + id;
                nuevaTarea.innerHTML = `
                    <p>${tarea}</p>
                    <div class="acciones">
                        <i class="far fa-check-circle"></i>
                        <i class="fas fa-trash"></i>
                    </div> `;
                listaTarea.appendChild(nuevaTarea);


                
                if (listaVacia.length === 0 ){
                    document.querySelector('.null-tareas').remove();
                }
                barraProgreso();
            }
        }
    }
    xhr.send(datos)
}

function reconocerTarea(e){
    var id = e.target.parentElement.parentElement.id.split(':');
    id = Number(id[1]);

    var tarea = e.target.parentElement.parentElement;
    if(e.target.classList.contains('fa-check-circle')){

        if (e.target.classList.contains('completo')){
            enviarEstado(id, 0);
            e.target.classList.remove('completo');
        }else{
            enviarEstado(id, 1);
            e.target.classList.add('completo');
        }
    }

    if (e.target.classList.contains('fa-trash')){
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            
            if (result.value) {
                borrarTarea(id, tarea);
                Swal.fire(
                    'Deleted!',
                    'Your file has been deleted.',
                    'success'
                )
            }
        })
    }
}

function enviarEstado(tarea, estado){
    var datos = new FormData();
    datos.append('id',tarea);
    datos.append('estado',estado);
    datos.append('accion','editar');

    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'includes/modelos/modelo-tarea.php', true);
    
    xhr.onload = function(){
        if(this.status === 200){
            barraProgreso();
        }
    }

    xhr.send(datos);

}


function borrarTarea(id,tarea){

    var datos = new FormData();
    datos.append('id',id);
    datos.append('accion','borrar');


    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'includes/modelos/modelo-tarea.php', true);

    xhr.onload = function () {
        if (this.status === 200) {
            var respuesta = JSON.parse(xhr.responseText);

            if(respuesta.respuesta === 'correcto'){
                tarea.remove();
                barraProgreso();
                if (!document.querySelector('#lista-tareas li')) {
                   document.querySelector('#lista-tareas').innerHTML = '<p class="null-tareas">No hay tareas pendientes</p>';
                }
            }

        }
    }

    xhr.send(datos);

}

function barraProgreso(){

    var tareas = document.querySelectorAll('.tarea').length;
    var completos = document.querySelectorAll('.completo').length;
    

    var total = Math.round((completos / tareas) * 100);
 
    document.querySelector('.progreso').style.width = total + '%';
    if(total === 100){
        Swal.fire(
            'Buen trabajo',
            'Has completado todas las tareas',
            'success'
        )
    }
}
