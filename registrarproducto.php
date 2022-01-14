<?php
    include "./instalacion/conexion.php";

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        
        $errores = array();

        $codigo = isset($_REQUEST['codigo']) ? $_REQUEST['codigo'] : '';
        $descripcion = isset($_REQUEST['descripcion']) ? $_REQUEST['descripcion'] : '';
        $categoria = isset($_REQUEST['categoria']) ? $_REQUEST['categoria'] : '';
        $precio = isset($_REQUEST['precio']) ? $_REQUEST['precio'] : '';
        $stock = isset($_REQUEST['stock']) ? $_REQUEST['stock'] : '';

        if(empty($_POST['codigo'])){
            $errores['codigo'] = 'El codigo es obligatorio';
        }
        else if(!preg_match('/^[a-zA-Z0-9\s]{3}\-[a-zA-Z0-9\s]{3}\-[a-zA-Z0-9\s]{3}$/',$_POST['codigo'])){
            $errores['codigo'] = 'Por favor agregue correctamente el codigo';
        }

        if(empty($_POST['descripcion'])){
            $errores['descripcion'] = 'La descripcion es obligatorio';
        }

        if(empty($_POST['categoria']) || (strcmp($_POST['categoria'],'off')===0)){
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
        
        /* Verificar que no existe en la base de datos el mismo codigo */
        $miConsulta = $conect->prepare('SELECT COUNT(*) as length FROM producto WHERE codigo = :codigo;');

        $miConsulta->execute([
            'codigo' => $codigo
        ]);
        
        $resultado = $miConsulta->fetch();

        if ((int) $resultado['length'] > 0) {
            $errores['codigo'] = 'El codigo del producto ya esta registrado';
        }

        if (count($errores) === 0) {
            $miNuevoRegistro = $conect->prepare('INSERT INTO producto(id_categoria, codigo,descripcion,precio,stock) VALUES (:id_categoria, :codigo, :descripcion, :precio, :stock)');

            $miNuevoRegistro->execute([
                'id_categoria'=>$categoria,
                'codigo'=>$codigo,
                'descripcion' => $descripcion,
                'precio' => $precio,
                'stock' => $stock
                
            ]);

            echo json_encode('./products.php');
        }
        else{
            echo json_encode($errores);
        }
    }
    else{
        header("Location: login.php");
    }

?>