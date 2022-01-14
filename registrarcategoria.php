<?php
    include "./instalacion/conexion.php";

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        
        $errores = array();

        $nombre = isset($_REQUEST['nombre']) ? $_REQUEST['nombre'] : '';

        if(empty($_POST['nombre'])){
            $errores['nombre'] = 'El nombre es obligatorio';
        }

        /* Verificar que no existe en la base de datos el mismo codigo */
        $miConsulta = $conect->prepare('SELECT COUNT(*) as length FROM categoria WHERE nombre = :nombre;');

        $miConsulta->execute([
            'nombre' => $nombre
        ]);
        
        $resultado = $miConsulta->fetch();

        if ((int) $resultado['length'] > 0) {
            $errores['nombre'] = 'La categoria ya esta registrada';
        }

        if (empty($errores)) {
            $miNuevoRegistro = $conect->prepare('INSERT INTO categoria(nombre) VALUES (:nombre)');

            $miNuevoRegistro->execute([
                'nombre'=>$nombre
                
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