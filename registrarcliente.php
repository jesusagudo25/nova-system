<?php
    include "./instalacion/conexion.php";

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        
        $errores = array();

        $nombre = isset($_REQUEST['nombre']) ? $_REQUEST['nombre'] : '';
        $telefono = isset($_REQUEST['telefono']) ? $_REQUEST['telefono'] : '';
        $email = isset($_REQUEST['email']) ? $_REQUEST['email'] : '';
        $provincia = isset($_REQUEST['provincia']) ? $_REQUEST['provincia'] : '';
        $distrito = isset($_REQUEST['distrito']) ? $_REQUEST['distrito'] : '';

        if(empty($_POST['nombre'])){
            $errores['nombre'] = 'El nombre es obligatorio';
        }

        if(empty($_POST['telefono'])){
            $errores['telefono'] = 'El telefono es obligatorio';
        }
        else if(!preg_match('/^\(?([0-9]{3})\)?[-. ]?([0-9]{4})[-. ]?([0-9]{4})$/',$_POST['telefono'])){
            $errores['telefono'] = 'Por favor agregue correctamente su telefono';
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

        if(empty($_POST['provincia']) || (strcmp($_POST['provincia'],'off')===0)){
            $errores['provincia'] = 'La provincia es obligatoria';
        }

        if(empty($_POST['distrito']) || (strcmp($_POST['distrito'],'off')===0)){
            $errores['distrito'] = 'El distrito es obligatoria';
        }
        
        /* Verificar que no existe en la base de datos el mismo email */
        $miConsulta = $conect->prepare('SELECT COUNT(*) AS length FROM cliente WHERE email = :email;');

        $miConsulta->execute([
            'email' => $email
        ]);
        
        $resultado = $miConsulta->fetch();

        if ((int) $resultado['length'] > 0) {
            $errores['email'] = 'La dirección de email ya esta registrada.';
        }

        /* Verificar que no existe en la base de datos el mismo telefono */
        $miConsulta = $conect->prepare('SELECT COUNT(*) AS length FROM cliente WHERE telefono = :telefono;');

        $miConsulta->execute([
            'telefono' => $telefono
        ]);
        
        $resultado = $miConsulta->fetch();

        if ((int) $resultado['length'] > 0) {
            $errores['telefono'] = 'La dirección de telefono ya esta registrada.';
        }

        if (empty($errores)) {
            $miNuevoRegistro = $conect->prepare('INSERT INTO cliente(nombre, telefono,email,provincia,distrito) VALUES (:nombre, :telefono, :email, :provincia, :distrito)');

            $miNuevoRegistro->execute([
                'nombre'=>$nombre,
                'telefono'=>$telefono,
                'email' => $email,
                'provincia' => $provincia,
                'distrito' => $distrito
                
            ]);

            echo json_encode('./clients.php');
        }
        else{
            echo json_encode($errores);
        }
    }
    else{
        header("Location: login.php");
    }

?>