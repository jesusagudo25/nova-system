<?php

    include "./instalacion/conexion.php";

    if($_SERVER['REQUEST_METHOD']=='POST'){
        $email = isset($_REQUEST['email']) ? $_REQUEST['email'] : '';
        $pass = isset($_REQUEST['password']) ? $_REQUEST['password'] : '';

        $consulta = $conect->prepare("SELECT r.nombre AS rol, u.password,u.id_usuario,u.nombre,u.apellido_paterno,u.estado FROM usuario u INNER JOIN usuario_rol r ON u.id_rol = r.id_rol WHERE email = :email");
        $consulta->execute(['email'=>$email]);
        $usuario = $consulta->fetch();

        if(!empty($usuario)){

            if(password_verify($pass, $usuario['password']) && $usuario['estado']==1){
                
                session_start();
                $_SESSION['email'] = $email;
                $_SESSION['rol'] = $usuario['rol'];
                $_SESSION['id'] = $usuario['id_usuario'];
                $_SESSION['nombre'] = $usuario['nombre'].' '.$usuario['apellido_paterno'];

                echo json_encode('./index.php');
            }
            else{
                echo json_encode('error');
            }

        }
        else{
            echo json_encode('error');
        }

    }
    else{
        header("Location: login.php");
    }

?>