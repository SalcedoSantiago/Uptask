<?php

    $tarea = $_POST['tarea'];
    $id_proyecto = (int) $_POST['id_proyecto'];
    $accion = $_POST['accion'];
    $id = (int) $_POST['id'];
    $estado = (int) $_POST['estado'];


    if( $accion == 'crear'){
        require '../funciones/db.php';
        try{
            $stmt = $conn->prepare("INSERT INTO tareasphp (tarea,proyecto_id) VALUES (?,?)");
            $stmt->bind_param("si",$tarea,$id_proyecto);
            $stmt->execute();
            if($stmt->affected_rows > 0){
                $respuesta = array(
                    'respuesta' => 'correcto',
                    'tarea' => $tarea,
                    'id' => $stmt->insert_id
                );
            }
            $stmt->close();
            $conn->close();
        }catch( Exception $e){
            $respuesta = array(
                'error' => $e->getMessage()
            );
        }
    }

    if($accion == 'editar'){

        require_once '../funciones/db.php';

        try{
            $stmt = $conn->prepare(" UPDATE tareasphp SET estado = ? WHERE id = ?");
            $stmt->bind_param("ii",$estado, $id);
            $stmt->execute();
            if($stmt->affected_rows > 0){
                $respuesta = array(
                    'respuesta' => 'correcto',
                    'id' => $id,
                    'estado' => $estado
                );
            }
            $stmt->close();
            $conn->close();
            
        }catch( Exception $e){
            $respuesta = array(
                'error' => 'error'
            );
        }

    }

    if($accion == 'borrar'){

    require_once '../funciones/db.php';

    try {
        $stmt = $conn->prepare(" DELETE FROM tareasphp WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            $respuesta = array(
                'respuesta' => 'correcto',
                'id' => $id
            );
        }
        $stmt->close();
        $conn->close();
    } catch (Exception $e) {
        $respuesta = array(
            'error' => 'error'
        );
    }
    }

    echo json_encode($respuesta);

