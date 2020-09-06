<?php
    
    $usuario = $_POST['usuario'];
    $password = $_POST['password'];
    $accion = $_POST['accion'];

    $respuesta = array();

    if($accion == 'crear'){

        require_once '../funciones/db.php';
        try {
        $stmt = $conn->prepare(" SELECT usuario FROM usuarios WHERE usuario = ?");
        $stmt->bind_param("s", $usuario);
        $stmt->execute();
        $stmt->bind_result($newUser);
        $stmt->fetch();
        if ($newUser ) {
            $ok = false;
            $respuesta = array(
                'respuesta' => 'existe'
            );
            $conn->close();
        } else {
            $ok = true;
        }
        $stmt->close();
        }catch( Exception $e){
            $respuesta = array(
                'error' => $e->getMessage()
            );
        }

        if( $ok ){
    
            $opciones = array(
                'cost' => 12
            );
            $hashPassword = password_hash($password, PASSWORD_BCRYPT, $opciones);

            try{
                $stmt = $conn->prepare("INSERT INTO usuarios (usuario , contraseña ) VALUES (?,?) ");
                $stmt->bind_param("ss", $usuario , $hashPassword);
                $stmt->execute();
                if($stmt->affected_rows == 1 ){
                    $respuesta = array(
                        'respuesta' => 'correcto',
                        'usuario' => $usuario,
                        'accion' => $accion,
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
    }

    if($accion == 'login'){

        require_once '../funciones/db.php';

        try{
            $stmt = $conn->prepare(" SELECT usuario, id , contraseña FROM usuarios WHERE usuario = ?");
            $stmt->bind_param("s",$usuario);
            $stmt->execute();
            $stmt->bind_result($nombre_usuario, $id_usuario , $pass_usuario);
            $stmt->fetch();
            if($nombre_usuario){
                if(password_verify($password,$pass_usuario)){
                    session_start();
                    $_SESSION['nombre'] = $nombre_usuario;
                    $_SESSION['id'] = $id_usuario;
                    $_SESSION['ingreso'] = true;
                    $respuesta = array(
                        'respuesta' => 'correcto',
                        'nombre' => $nombre_usuario,
                        'id' => $stmt->insert_id
                    );
                }else{
                    $respuesta = array(
                        'error' => 'pass_error'
                    );
                }
            }else{
                $respuesta = array(
                    'error' => 'error',
                    'accion' => 'login'
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
    
