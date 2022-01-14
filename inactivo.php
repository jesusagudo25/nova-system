<?php

 include "./instalacion/conexion.php";

    if(isset($_GET['id_producto'])){
        if($_GET['id_producto'] != 0){

            $actualizarDatos = $conect->prepare('UPDATE producto SET estado = 0 WHERE id_producto = :id_producto');
            $actualizarDatos->execute(
                [
                    'id_producto' => $_GET['id_producto']
                ]
            );
        }


        header("Location: products.php");
    }
    else if(isset($_GET['id_usuario'])){
        if($_GET['id_usuario'] != 0){
            $actualizarDatos = $conect->prepare('UPDATE usuario SET estado = 0 WHERE id_usuario = :id_usuario');
            $actualizarDatos->execute(
                [
                    'id_usuario' => $_GET['id_usuario']
                ]
            );
        }


        header("Location: users.php");
    }
    else if(isset($_GET['id_categoria'])){
        if($_GET['id_categoria'] != 0){
            $actualizarDatos = $conect->prepare('UPDATE categoria SET estado = 0 WHERE id_categoria = :id_categoria');
            $actualizarDatos->execute(
                [
                    'id_categoria' => $_GET['id_categoria']
                ]
            );
        }


        header("Location: categories.php");
    }
    else if(isset($_GET['id_cliente'])){
        if($_GET['id_cliente'] != 0){
            $actualizarDatos = $conect->prepare('UPDATE cliente SET estado = 0 WHERE id_cliente = :id_cliente');
            $actualizarDatos->execute(
                [
                    'id_cliente' => $_GET['id_cliente']
                ]
            );
        }


        header("Location: clients.php");
    }
    else{
        header("Location: login.php");
    }

?>
