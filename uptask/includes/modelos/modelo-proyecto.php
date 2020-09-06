<?php
    $nombre = filter_var($_POST['nombre'], FILTER_SANITIZE_STRING);
    $accion = $_POST['accion'];

    if($accion == 'crear'){

        require_once '../funciones/db.php';

        try{
            $stmt = $conn->prepare("INSERT INTO proyectos (nombre) VALUES (?)");
            $stmt->bind_param("s", $nombre);
            $stmt->execute();
            if($stmt->affected_rows > 0){
                $respuesta = array(
                    'respuesta' => 'correcto',
                    'datos' => array(
                        'nombre' => $nombre,
                        'id' => $stmt->insert_id
                    )
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

    echo json_encode($respuesta);