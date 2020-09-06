<?php
 
    function obtenerPaginaActual(){
        $archivo = basename($_SERVER['PHP_SELF']);
        $pagina = str_replace(".php","",$archivo);
       return  $pagina;
    }
  
    function ObetenerProyectos(){
        require_once 'db.php';
        try{
            return $conn->query('SELECT id,nombre FROM proyectos');
        }catch( Exception $e){
            return false;
        }
    }

    function obetenerProyecto($id = NULL){
        include 'db.php';
        try {
            return $conn->query("SELECT nombre FROM proyectos WHERE id = {$id}");
        } catch (Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }

    function obtenerTareas($id){
        include 'db.php';
        try{
        return $conn->query("SELECT id,tarea,estado FROM tareasphp WHERE proyecto_id = {$id}");
        }catch( Exception $e){
            echo $e->getMessage();
            return false;
        }
    }
