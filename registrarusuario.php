<?php
    include "./instalacion/conexion.php";

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        
        $errores = array();

        $nombre = isset($_REQUEST['nombre']) ? $_REQUEST['nombre'] : '';
        $apellido = isset($_REQUEST['apellido']) ? $_REQUEST['apellido'] : '';
        $email = isset($_REQUEST['email']) ? $_REQUEST['email'] : '';
        $password = isset($_REQUEST['password']) ? $_REQUEST['password'] : '';

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

        if(empty($_POST['password'])){
            $errores['password'] = 'La contraseña es obligatoria';
        }
        else if(!preg_match('/^[a-zA-Z0-9\s](?=.*[#$\-_@&%]).*$/',$_POST['password'])){
            $errores['password'] = 'La contraseña debe contener números, letras y al menos un caracter especial (#,$,-,_,&,%,@)';
        }
        
        /* Verificar que no existe en la base de datos el mismo email */
        $miConsulta = $conect->prepare('SELECT COUNT(*) as length FROM usuario WHERE email = :email;');

        $miConsulta->execute([
            'email' => $email
        ]);
        
        $resultado = $miConsulta->fetch();
        // Comprueba si existe

        if ((int) $resultado['length'] > 0) {
            $errores['email'] = 'La dirección de email ya esta registrada.';
        }

        if (count($errores) === 0) {
            $miNuevoRegistro = $conect->prepare('INSERT INTO usuario(nombre, apellido_paterno,email,password) VALUES (:nombre, :apellido, :email, :password)');
            // Ejecuta el nuevo registro en la base de datos
            $miNuevoRegistro->execute([
                'nombre'=>$nombre,
                'apellido'=>$apellido,
                'email' => $email,
                'password' => password_hash($password, PASSWORD_BCRYPT)
            ]);

            if(!isset($_POST['manageuser'])){
                $miConsulta = $conect->prepare('SELECT id_usuario FROM usuario WHERE email = :email;');

                $miConsulta->execute([
                    'email' => $email
                ]);
    
                $resultado = $miConsulta->fetch();
    
                session_start();
                $_SESSION['email'] = $email;
                $_SESSION['id'] = $resultado['id_usuario'];
                $_SESSION['rol'] = 'User';
                $_SESSION['nombre'] = $nombre.' '.$apellido;
    
                echo json_encode('./index.php');
            }
            else{
                echo json_encode('./users.php');
            }

        }
        else{
            echo json_encode($errores);
        }
    }
    else{
        header("Location: login.php");
    }

?>