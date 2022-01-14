<?php
    
    include "./instalacion/conexion.php";

    if($_SERVER['REQUEST_METHOD'] == 'POST') {

        $id = $_POST['id_producto'];

        $errores = array();

        $codigo = isset($_REQUEST['codigo']) ? $_REQUEST['codigo'] : '';
        $descripcion = isset($_REQUEST['descripcion']) ? $_REQUEST['descripcion'] : '';
        $categoria = isset($_REQUEST['categoria']) ? $_REQUEST['categoria'] : '';
        $precio = isset($_REQUEST['precio']) ? $_REQUEST['precio'] : '';
        $stock = isset($_REQUEST['stock']) ? $_REQUEST['stock'] : '';
        $estado = isset($_REQUEST['estado']) ? $_REQUEST['estado'] : '';

        if(empty($_POST['codigo'])){
            $errores['codigo'] = 'El codigo es obligatorio';
        }
        else if(!preg_match('/^[a-zA-Z0-9\s]{3}\-[a-zA-Z0-9\s]{3}\-[a-zA-Z0-9\s]{3}$/',$_POST['codigo'])){
            $errores['codigo'] = 'Por favor agregue correctamente el codigo';
        }

        if(empty($_POST['descripcion'])){
            $errores['descripcion'] = 'La descripcion es obligatorio';
        }

        if(empty($_POST['categoria']) || (strcmp($_POST['precio'],'off')===0)){
            $errores['categoria'] = 'La categoria es obligatoria';
        }

        if(empty($_POST['precio'])){
            $errores['precio'] = 'El precio es obligatorio';
        }
        else{
            $validarPrecio= filter_var($_POST['precio'],FILTER_VALIDATE_FLOAT);
            if(!$validarPrecio){
                $errores['precio'] = 'Agregue un precio valido';
            }
        }

        if(empty($_POST['stock'])){
            $errores['stock'] = 'El stock es obligatorio';
        }

        if(!isset($_POST['estado'])){
            $errores['estado'] = 'El estado es obligatorio';
        }

        if(empty($errores)){

            $actualizarDatos = $conect->prepare("UPDATE producto SET id_categoria = :id_categoria, codigo = :codigo, descripcion = :descripcion, precio = :precio, stock = :stock, estado = :estado WHERE id_producto = :id;");
            $actualizarDatos->execute([
                'id_categoria'=>$categoria,
                'codigo'=>$codigo,
                'descripcion' => $descripcion,
                'precio' => $precio,
                'stock' => $stock,
                'estado' => $estado,
                'id'=>$id
                
            ]);

            echo json_encode('./products.php');
        }
        else{
            echo json_encode($errores);
        }
    }
    else{
        header("Location: index.php");
    }

?>