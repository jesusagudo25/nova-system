<?php
    include "./instalacion/conexion.php";

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $errores = array();
        
        if(empty($_POST['pass2'])){
            $errores['password'] = 'La contraseña es obligatoria';
        }
        else if(!preg_match('/^[a-zA-Z0-9\s](?=.*[#$\-_@&%]).*$/',$_POST['pass2'])){
            $errores['password'] = 'La contraseña debe contener números, letras y al menos un caracter especial (#,$,-,_,&,%,@)';
        }

        if(empty($errores)){
            $token = isset($_REQUEST['token']) ? $_REQUEST['token'] : null;
            $password = isset($_REQUEST['pass2']) ? password_hash($_REQUEST['pass2'], PASSWORD_BCRYPT) : null;
    
            $consultarFecha = $conect->prepare('SELECT DATEDIFF(CURDATE(),fecha_token) >= 1 AS dias,id_rol,id_usuario,email,nombre,apellido_paterno FROM usuario WHERE token = :token');
            $consultarFecha->execute(['token' => $token]);
            $usuario = $consultarFecha->fetch();

            if($usuario['dias'] < 1){
    
                $actualizarDatos = $conect->prepare('UPDATE usuario SET password = :password,token = "No definido", fecha_token = null WHERE token = :token');
                // Ejecuta UPDATE con los datos
                $actualizarDatos->execute(
                    [
                        'password' => $password,
                        'token' => $token
                    ]
                );
    
                session_start();
                $_SESSION['email'] = $usuario['email'];
                $_SESSION['id'] = $usuario['id_rol'];
                if($usuario['id_rol']){
                    $_SESSION['rol'] = 'User';
                }
                else{
                    $_SESSION['rol'] = 'Admin';
                }
                $_SESSION['nombre'] = $usuario['nombre'].' '.$usuario['apellido_paterno'];

                echo json_encode('./index.php');
                
            }
            else{
                $errores['token'] = 'El codigo ha expirado';
                echo json_encode($errores);
            }
        }
        else{
            echo json_encode($errores);
        }


    } else{
        header("Location: login.php");
    }


?>