<?php
    
    include "./instalacion/conexion.php";

    if($_SERVER['REQUEST_METHOD'] == 'POST') {

        $id = $_POST['id_cliente'];

        $errores = array();

        $nombre = isset($_REQUEST['nombre']) ? $_REQUEST['nombre'] : '';
        $telefono = isset($_REQUEST['telefono']) ? $_REQUEST['telefono'] : '';
        $email = isset($_REQUEST['email']) ? $_REQUEST['email'] : '';
        $provincia = isset($_REQUEST['provincia']) ? $_REQUEST['provincia'] : '';
        $distrito = isset($_REQUEST['distrito']) ? $_REQUEST['distrito'] : '';
        $estado = isset($_REQUEST['estado']) ? $_REQUEST['estado'] : '';

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

        if(!isset($_POST['estado'])){
            $errores['estado'] = 'El estado es obligatorio';
        }

        if(empty($errores)){

            $actualizarDatos = $conect->prepare("UPDATE cliente SET nombre = :nombre, telefono = :telefono, email = :email, provincia = :provincia, distrito = :distrito, estado = :estado WHERE id_cliente = :id;");
            $actualizarDatos->execute([
                'nombre'=>$nombre,
                'telefono'=>$telefono,
                'email' => $email,
                'provincia' => $provincia,
                'distrito' => $distrito,
                'estado' => $estado,
                'id'=>$id
                
            ]);

            echo json_encode('./clients.php');
        }
        else{
            echo json_encode($errores);
        }
    }
    else{
        header("Location: index.php");
    }

?>