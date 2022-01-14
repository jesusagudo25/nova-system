<?php
    
    include "./instalacion/conexion.php";
    
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
            
        $id = $_POST['id_categoria'];

        $errores = array();

        $nombre = isset($_REQUEST['nombre']) ? $_REQUEST['nombre'] : '';
        $estado = isset($_REQUEST['estado']) ? $_REQUEST['estado'] : '';

        if(empty($_POST['nombre'])){
            $errores['nombre'] = 'El nombre es obligatorio';
        }

        if(!isset($_POST['estado'])){
            $errores['estado'] = 'El estado es obligatorio';
        }


        if(empty($errores)){

            $actualizarDatos = $conect->prepare("UPDATE categoria SET nombre = :nombre, estado = :estado WHERE id_categoria = :id;");
            $actualizarDatos->execute([
                'nombre'=>$nombre,
                'estado'=>$estado,
                'id'=>$id
                
            ]);

            echo json_encode('./categories.php');
        }
        else{
            echo json_encode($errores);
        }
    }
    else{
        header("Location: index.php");
    }

?>