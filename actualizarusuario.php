<?php
    
    include "./instalacion/conexion.php";

    if($_SERVER['REQUEST_METHOD'] == 'POST') {

        $id = $_POST['id_usuario'];

        $errores = array();

        if(isset($_POST['password'])){
            $password = isset($_REQUEST['password']) ? $_REQUEST['password'] : '';

            if(empty($_POST['password'])){
                $errores['password'] = 'La contraseña es obligatoria';
            }
            else if(!preg_match('/^[a-zA-Z0-9\s](?=.*[#$\-_@&%]).*$/',$_POST['password'])){
                $errores['password'] = 'La contraseña debe contener números, letras y al menos un caracter especial (#,$,-,_,&,%,@)';
            }

            if(empty($errores)){

                $actualizarDatos = $conect->prepare("UPDATE usuario SET password = :password WHERE id_usuario = :id;");
                $actualizarDatos->execute([
                    'password'=>password_hash($password, PASSWORD_BCRYPT),
                    'id'=>$id
                    
                ]);
    
                echo json_encode('./users.php');
            }
            else{
                echo json_encode($errores);
            }
        }
        else{
            $nombre = isset($_REQUEST['nombre']) ? $_REQUEST['nombre'] : '';
            $apellido = isset($_REQUEST['apellido']) ? $_REQUEST['apellido'] : '';
            $email = isset($_REQUEST['email']) ? $_REQUEST['email'] : '';
            $id_rol = isset($_REQUEST['id_rol']) ? $_REQUEST['id_rol'] : '';
            $estado = isset($_REQUEST['estado']) ? $_REQUEST['estado'] : '';

            if(empty($_POST['nombre'])){
                $errores['nombre'] = 'El nombre es obligatorio';
            }
            else if(!preg_match('/^[a-zA-Z\s]+$/',$_POST['nombre'])){
                $errores['nombre'] = 'Por favor agregue correctamente su nombre';
            }
    
            if(empty($_POST['apellido'])){
                $errores['apellido'] = 'El apellido es obligatorio';
            }
            else if(!preg_match('/^[a-zA-Z\s]+$/',$_POST['apellido'])){
                $errores['apellido'] = 'Por favor agregue correctamente su apellido';
            }
    
            if(empty($_POST['email'])){
                $errores['email'] = 'El email es obligatorio';
            }
            else{
                $email= filter_var($_POST['email'],FILTER_VALIDATE_EMAIL);
                if(!$email){
                    $errores['email'] = 'Agregue un email valido';
                }
            }

            if(!isset($_POST['estado'])){
                $errores['estado'] = 'El estado es obligatorio';
            }

            if(!isset($_POST['id_rol'])){
                $errores['rol'] = 'El rol es obligatorio';
            }

            if(empty($errores)){

                $actualizarDatos = $conect->prepare("UPDATE usuario SET nombre = :nombre, apellido_paterno = :apellido, email = :email, id_rol = :id_rol, estado = :estado WHERE id_usuario = :id;");
                $actualizarDatos->execute([
                    'nombre'=>$nombre,
                    'apellido'=>$apellido,
                    'email' => $email,
                    'id_rol' => $id_rol,
                    'estado' => $estado,
                    'id'=>$id
                    
                ]);
    
                echo json_encode('./users.php');
            }
            else{
                echo json_encode($errores);
            }
        }


    }
    else{
        header("Location: index.php");
    }

?>